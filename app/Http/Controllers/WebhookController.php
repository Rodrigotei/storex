<?php

namespace App\Http\Controllers;

use App\Mail\ActiveAccount;
use App\Models\User;
use App\Models\WebhookEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class WebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $secret = config('services.checkout.secret');
        $providedSecret = $request->input('secret');

        if (! is_string($secret) || $secret === '' || ! is_string($providedSecret) || ! hash_equals($secret, $providedSecret)) {
            Log::warning('Webhook rejeitado por autenticação inválida.', [
                'event' => $request->input('event'),
                'ip' => $request->ip(),
            ]);

            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'event' => ['required', 'string', 'max:100'],
            'data' => ['required', 'array'],
            'data.id' => ['required', 'string', 'max:191'],
            'data.customer.email' => ['required', 'email:rfc', 'max:255'],
        ]);

        if ($data['event'] !== 'purchase_approved') {
            return response()->json(['status' => 'ignored']);
        }

        try {
            $processed = $this->activateSubscription(
                externalId: $data['data']['id'],
                email: $data['data']['customer']['email'],
            );

            return response()->json(['status' => $processed ? 'ok' : 'already_processed']);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json(['error' => 'Internal error'], 500);
        }
    }

    private function activateSubscription(string $externalId, string $email): bool
    {
        $user = null;

        $processed = DB::transaction(function () use ($externalId, $email, &$user): bool {
            if (WebhookEvent::where('external_id', $externalId)->lockForUpdate()->exists()) {
                return false;
            }

            $user = User::with('store')->where('email', $email)->lockForUpdate()->firstOrFail();
            $subscriptionStartsAt = $user->expires_at?->isFuture() ? $user->expires_at : now();

            $user->update([
                'status' => 'active',
                'expires_at' => $subscriptionStartsAt->copy()->addYear(),
            ]);

            WebhookEvent::create([
                'external_id' => $externalId,
                'event' => 'purchase_approved',
                'user_id' => $user->id,
                'processed_at' => now(),
            ]);

            return true;
        });

        if ($processed && $user?->store) {
            Mail::to($user->email)->send(new ActiveAccount($user->name, $user->store->slug));
        }

        return $processed;
    }
}
