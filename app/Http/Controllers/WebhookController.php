<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $secret = config('services.checkout.secret');

        if ($request->input('secret') !== $secret) {
            Log::warning('Webhook inválido - secret incorreto', $request->all());
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Log::info('Webhook recebido', [
            'event' => $request->input('event'),
            'order_id' => $request->input('data.id'),
        ]);

        $event = $request->input('event');
        $data = $request->input('data');

        if (!$event || !$data) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }
        try {
            switch ($event) {
                case 'purchase_approved':
                    $this->handlePurchaseApproved($data);
                    break;
                default:
                    Log::info('Evento não tratado', ['event' => $event]);
                    break;
            }

        } catch (\Exception $e) {
            Log::error('Erro no webhook', [
                'message' => $e->getMessage(),
                'data' => $data
            ]);

            return response()->json(['error' => 'Internal error'], 500);
        }

        return response()->json(['status' => 'ok']);
    }

    private function handlePurchaseApproved(array $data)
    {
       try {
            $orderId = $data['id'];
            $email = $data['customer']['email'];
            $product = $data['product']['name'];

            Log::info('Pagamento aprovado', [
                'order_id' => $orderId,
                'email' => $email,
                'product' => $product
            ]);
            $user = new UsersController();
            $user->active($email);
       } catch (\Throwable $th) {
            return abort(500, 'Ocorreu um erro inesperado.');
       }
    }
}