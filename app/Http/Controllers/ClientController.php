<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    public function index()
    {   
        try {
            $store = app('store');
            $store = Store::with('address')->where('slug', $store->slug)->first();
            $tenant_id = $store->id;
            $categories = Category::where('status', true)->where('tenant_id', $tenant_id)->get()->take(5);
            $lastProducts = Product::with('productImages')->latest()->take(10)->where('status', true)->where('tenant_id', $tenant_id)->get();
            $promotionalProducts = Product::with('productImages')->whereNot('promotional_price', null)->where('status', true)->where('tenant_id', $tenant_id)->get()->take(10);
            $services = Service::with('serviceImages')->where('status', true)->where('tenant_id', $tenant_id)->get()->take(10);
            return view('client.index', compact('store', 'categories', 'lastProducts', 'promotionalProducts', 'services'));
        } catch (\Throwable $th) {
            return view('client.error');
        }
    }
    public function categories()
    {
        try {
            $tenant_id = $this->getTenantId();
            $categories = Category::where('status', true)->where('tenant_id', $tenant_id)->get();
            if($categories->isEmpty()){
                return redirect()->route('client.home', ['tenant' => app('store')->slug])->withErrors(['error' => 'Nenhuma categoria foi encontrada.']);
            }
            return view('client.categories', compact('categories'));
        } catch (\Throwable $th) {
            return view('client.error');
        }
    }
    public function category(string $tenant, string $id) // tenant required by route binding
    {
        try {
            $tenant_id = $this->getTenantId();
            $products = Product::with('category')->where('category_id', $id)->where('status', true)->where('tenant_id', $tenant_id)->get();
            if($products->isEmpty()){
                return redirect()->route('client.home', ['tenant' => app('store')->slug])->withErrors(['error' => 'Nenhum produto encontrado para esta categoria.']);
            }
            $categoryName = Category::where('status', true)->where('tenant_id', $tenant_id)->find($id,'name');
            return view('client.category', compact('categoryName', 'products'));
        } catch (\Throwable $th) {
            return view('client.error');
        }
    }
    public function product(string $tenant, string $id) // tenant required by route binding
    {
        try {
            $tenant_id = $this->getTenantId();
            $product = Product::with(['category', 'productImages', 'productVariations', 'productVariations.variation'])->where('status', true)->where('tenant_id', $tenant_id)->find($id);
            if(!$product){
                return redirect()->route('client.home', ['tenant' => app('store')->slug])->withErrors(['error' => 'Produto não encontrado.']);
            }
            return view('client.product', compact('product'));
        } catch (\Throwable $th) {
            return view('client.error');
        }
    }
    public function service(string $tenant, string $id) // tenant required by route binding
    {
        try {
            $tenant_id = $this->getTenantId();
            $service = Service::with(['serviceImages'])->where('status', true)->where('tenant_id', $tenant_id)->find($id);
            if(!$service){
                return redirect()->route('client.home', ['tenant' => app('store')->slug])->withErrors(['error' => 'Serviço não encontrado.']);
            }
            return view('client.service', compact('service'));
        } catch (\Throwable $th) {
            return view('client.error');
        }
    }
    public function cart()
    {
        try {
            $delivery_fee = $this->getStore()->delivery_fee;
            return view('client.cart', compact('delivery_fee'));
        } catch (\Throwable $th) {
            return view('client.error');
        }
    }
    public function add(Request $request)
    {
        $tenant_id = $this->getTenantId();
        try {
            $cart = session()->get('cart', []);
            $product = Product::with(['productImages'])->where('status', true)->where('tenant_id', $tenant_id)->findOrFail($request->product_id);
            $found = false;
            foreach ($cart as &$item) {
                if ($item['product_id'] == $product->id && $item['variation_id'] == $request->variation_id && $item['observation'] == $request->observation){
                    $item['qty'] += $request->quantity;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $cart[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->promotional_price ?? $product->price,
                    'qty' => $request->quantity,
                    'variation_id' => $request->variation_id,
                    'variation' => $request->variation_name,
                    'image' => $product->productImages->first()->img ?? null,
                    'observation' => $request->observation
                ];
            }
            session()->put('cart', $cart);
            $totalQty = collect($cart)->sum('qty');
            session()->put('cart_count', $totalQty);
            return back()->with('success', 'Produto adicionado!');
        } catch( ModelNotFoundException $e){
            return back()->withErrors(['error' => 'Produto não encontrado.']);
        } 
        catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
    }
    public function delete(Request $request)
    {
        try {
            $cart = session()->get('cart', []);
            if (empty($cart)) {
                return back()->withErrors(['error' => 'O carrinho está vazio.']);
            }
            if (!isset($cart[$request->index])) {
                return back()->withErrors(['error' => 'Item inválido.']);
            }
            unset($cart[$request->index]);
            $cart = array_values($cart);
            session()->put('cart', $cart);
            $totalQty = collect($cart)->sum('qty');
            session()->put('cart_count', $totalQty);
            return back()->with('success', 'Produto removido com sucesso.');
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
    }
    public function orderFinish(Request $request)
    {
       try {
            $validated = $request->validate([
                'name'            => ['required', 'string', 'max:100'],
                'type'            => ['required', 'in:delivery,pickup'],
                'payment_method'  => ['required', 'in:pix,cash,card'],
                'address'         => ['required_if:type,delivery', 'nullable', 'string', 'max:255'],
                'number'          => ['required_if:type,delivery', 'nullable', 'string', 'max:20'],
                'neighborhood'    => ['required_if:type,delivery', 'nullable', 'string', 'max:100'],
                'complement'      => ['nullable', 'string', 'max:255'],
                'change_for'      => ['nullable', 'string', 'max:50'],
            ], [
                'name.required' => 'Informe seu nome',
                'phone.required' => 'Informe seu WhatsApp',
                'type.required' => 'Selecione entrega ou retirada',
                'payment_method.required' => 'Selecione a forma de pagamento',
                'address.required_if' => 'Informe o endereço para entrega',
                'number.required_if' => 'Informe o número',
                'neighborhood.required_if' => 'Informe o bairro',
            ]);
            $cart = session('cart', []);
            if (empty($cart)) {
                return back()->withErrors(['error' => 'Seu carrinho está vazio']);
            }
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['qty'];
            }
            $delivery_fee = $request->type === 'delivery' ? (float) ($request->delivery_fee ?? 0) : 0;
            $total += $delivery_fee;
            $paymentMap = [
                'pix'  => 'PIX',
                'cash' => 'Dinheiro',
                'card' => 'Cartão',
            ];
            $typeMap = [
                'delivery' => 'Entrega',
                'pickup'   => 'Retirada',
            ];
            $message  = "*NOVO PEDIDO*\n";
            $message .= "━━━━━━━━━━━━━━━\n\n";
            foreach ($cart as $item) {
                $subtotal = $item['price'] * $item['qty'];
                $message .= " *{$item['name']}*\n";
                $message .= "   {$item['qty']}x R$ " . number_format($item['price'], 2, ',', '.') . "\n";
                if (!empty($item['variation'])) {
                    $message .= "   Opção: {$item['variation']}\n";
                }
                if (!empty($item['observation'])) {
                    $message .= "   Obs: {$item['observation']}\n";
                }
                $message .= "   Subtotal: R$ " . number_format($subtotal, 2, ',', '.') . "\n\n";
            }
            $message .= "━━━━━━━━━━━━━━━\n";
            $message .= "*RESUMO*\n";
            $message .= "Subtotal: R$ " . number_format($total - $delivery_fee, 2, ',', '.') . "\n";
            if ($request->type === 'delivery') {
                $message .= "Entrega: " . ($delivery_fee > 0  ? "R$ " . number_format($delivery_fee, 2, ',', '.')  : "Grátis") . "\n";
            }
            $message .= "*Total: R$ " . number_format($total, 2, ',', '.') . "*\n\n";
            $message .= "*CLIENTE*\n";
            $message .= "Nome: {$request->name}\n";
            $message .= "*{$typeMap[$request->type]}*\n";
            if ($request->type === 'delivery') {
                $message .= "{$request->address}, {$request->number}\n";
                $message .= "{$request->neighborhood}\n";
                if (!empty($request->complement)) {
                    $message .= "Complemento: {$request->complement}\n";
                }
                $message .= "\n";
            }
            $message .= "*PAGAMENTO*\n";
            $message .= $paymentMap[$request->payment_method] . "\n";
            if ($request->payment_method === 'cash' && !empty($request->change_for)) {
                $message .= "Troco para: R$ {$request->change_for}\n";
            }
            $store = $this->getStore();
            $url = "https://wa.me/55{$store->phone}?text=".urlencode($message);
            return redirect()->away($url);
       } catch (\Throwable $th) {
        return back()->withErrors(['error' => 'Ocorreu um erro ao finalizar seu pedido.'])->withInput();
       }
    }
    public function serviceFinish(Request $request)
    {
        try {
            $data = $request->validate(
                [
                    'service_id' => 'required|exists:services,id',
                    'date' => 'required|date',
                    'time' => 'required',
                    'message' => 'nullable|string|max:1000',
                    'name' => 'required|string|max:255',
                ],
                [
                    'service_id.required' => 'Serviço não identificado',
                    'date.required' => 'Informe a data',
                    'date.date' => 'Data inválida',
                    'time.required' => 'Informe o horário',
                    'name.required' => 'Informe seu nome'
                ]
            );

            $service = Service::findOrFail($data['service_id']);
            $formattedDate = '-';

            $days = [
                'Sunday' => 'domingo',
                'Monday' => 'segunda-feira',
                'Tuesday' => 'terça-feira',
                'Wednesday' => 'quarta-feira',
                'Thursday' => 'quinta-feira',
                'Friday' => 'sexta-feira',
                'Saturday' => 'sábado',
            ];
            if (!empty($data['date'])) {
                $date = Carbon::parse($data['date']);
                $dayName = $days[$date->format('l')];
                $formattedDate = "{$dayName}, " . $date->format('d/m/Y');
            }
            $formattedTime = $data['time'] ?? '-';
            $details = $data['message'] ?? 'Sem detalhes adicionais.';
            $name = $data['name'];

            $message = "Olá!\n\n";
            $message .= "Me chamo *{$name}*.\n\n";
            $message .= "Tenho interesse no serviço *{$service->name}*.\n\n";
            $message .= "Data: *{$formattedDate}*\n";
            $message .= "Horário: *{$formattedTime}*\n\n";
            $message .= "Detalhes:\n{$details}\n\n";
            $message .= "Pode me confirmar a disponibilidade?";

            $encodedMessage = urlencode($message);
            $store = $this->getStore();

            return redirect()->away("https://wa.me/55{$store->phone}?text={$encodedMessage}");

        } catch(ValidationException $e){
            return back()->withErrors($e->validator)->with('error_name', 'true')->withInput();
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro  ao finalizar sua solicitação.'])->with('error_name', 'true')->withInput();
        }
    }
    public function search(Request $request)
    {
       $tenant_id = $this->getTenantId();
       try {
            $request->validate(
                [
                    'search' => 'required'
                ],
            );
            $search = $request->search;
            $products = Product::whereLike('name', '%'.$search.'%')->where('status', true)->where('tenant_id', $tenant_id)->get();
            $services = Service::whereLike('name', '%'.$search.'%')->where('status', true)->where('tenant_id', $tenant_id)->get();
            if($products->isEmpty() && $services->isEmpty() ){
                return redirect()->route('client.home', ['tenant' => app('store')->slug])->withErrors(['error' => 'Nada foi encontrado.']);
            }
            return view('client.search', compact('search', 'products', 'services'));
       } catch (ValidationException $e) {
            return back()->withErrors(['error' => 'Nada encontrado.']);
       } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
       }
    }
    private function getTenantId()
    {
        return $this->getStore()->id;
    }
    private function getStore()
    {
        $store = app('store');

        if (!$store) {
            abort(403, 'Store não definida');
        }
        return $store;
    }
}