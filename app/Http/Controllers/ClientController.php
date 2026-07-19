<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Service;
use App\Models\ProductImage;
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
            return view('client.index', compact('store', 'categories', 'lastProducts', 'promotionalProducts'));
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
            $product = Product::with([
                'category', 
                'productImages', 
                'variationGroups', 
                'variationGroups.variation', 
                'variationGroups.productVariations' => function ($query){
                    $query->where('status', true)->orderBy('value');
                }
            ])->where('status', true)->where('tenant_id', $tenant_id)->find($id);
            if(!$product){
                return redirect()->route('client.home', ['tenant' => app('store')->slug])->withErrors(['error' => 'Produto não encontrado.']);
            }
            return view('client.product', compact('product'));
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
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'product_variations' => 'nullable|array',
                'product_variations.*' => 'exists:product_variations,id',
            ]);

            $cart = session()->get('cart', []);
            $product = Product::with([ 'productImages' => function ($query){$query->first();}, 'variationGroups.variation'])->where('status', true)->where('tenant_id', $tenant_id)->findOrFail($request->product_id);
            
            $selectedVariationIds = $request->product_variations ?? [];
            $selectedVariations = collect();
            if(!empty($selectedVariationIds)){
                $selectedVariations = ProductVariation::whereIn('id', $selectedVariationIds)->where('status', true)->get();
                if($selectedVariations->count() !== count($selectedVariationIds)){
                    return back()->withErrors(['error' => 'Variação inválida selecionada.'])->withInput();
                }
            }
            
            $variations = [];
            $additionalPrice = 0;
            foreach ($selectedVariations as $variation) {
                $variations[] = [
                    'id' => $variation->id,
                    'value' => $variation->value,
                    'additional_price' => $variation->additional_price,
                ];
                $additionalPrice += $variation->additional_price;
            }
          
            $basePrice = $product->promotional_price ?? $product->price;
            $finalPrice = $basePrice + $additionalPrice;

            $variationKey = collect($selectedVariationIds)->sort()->implode('-');

            $observation = trim($request->observation);
            $found = false;
            foreach ($cart as &$item) {
                if ($item['product_id'] == $product->id && $item['variation_key'] == $variationKey && $item['observation'] == $observation) {
                    $item['qty'] += $request->quantity;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $cart[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $basePrice,
                    // 'additional_price' => $additionalPrice,
                    'final_price' => $finalPrice,
                    'qty' => $request->quantity,
                    'variation' => $product->variationGroups->first()->variation->name ?? null,
                    'variation_key' => $variationKey,
                    'variations' => $variations,
                    'image' => $product->productImages->first()->img ?? null,
                    'observation' => $observation,
                ];
            }
            session()->put('cart', $cart);
            $totalQty = collect($cart)->sum('qty');
            session()->put('cart_count', $totalQty);
            return back()->with('success', 'Produto adicionado!');
        } catch (ValidationException $e) {
            return back()->withErrors(['error' => 'Erro ao adicionar produto.'])->withInput();
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Produto não encontrado.']);
        } catch (\Throwable $th) {
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
            $request->validate(
                [
                    'name'            => ['required', 'string', 'max:100'],
                    'type'            => ['required', 'in:delivery,pickup'],
                    'payment_method'  => ['required', 'in:pix,cash,card'],
                    'address'         => ['required_if:type,delivery', 'nullable', 'string', 'max:255'],
                    'number'          => ['required_if:type,delivery', 'nullable', 'string', 'max:20'],
                    'neighborhood'    => ['required_if:type,delivery', 'nullable', 'string', 'max:100'],
                    'complement'      => ['nullable', 'string', 'max:255'],
                    'change_for'      => ['nullable', 'string', 'max:50'],
                ], 
                [
                    'name.required' => 'Informe seu nome',
                    'phone.required' => 'Informe seu WhatsApp',
                    'type.required' => 'Selecione entrega ou retirada',
                    'payment_method.required' => 'Selecione a forma de pagamento',
                    'address.required_if' => 'Informe o endereço para entrega',
                    'number.required_if' => 'Informe o número',
                    'neighborhood.required_if' => 'Informe o bairro',
                ]
            );
            $tenant_id = $this->getTenantId();
            $cart = session('cart', []);
            if (empty($cart)) {
                return back()->withErrors(['error' => 'Seu carrinho está vazio']);
            }

            $productIds = collect($cart)->pluck('product_id')->unique()->values();
            $products = Product::where('tenant_id', $tenant_id)->where('status', true)->whereIn('id', $productIds)->get()->keyBy('id');

            $total = 0;
            foreach ($cart as $item) {
                if(!isset($products[$item['product_id']])){
                    return back()->withErrors(['error' => "O produto ".$item['name']." não está mais disponível."]);
                }
                $total += $item['final_price'] * $item['qty'];
            }

            $delivery_fee = $request->type === 'delivery' ? (float) (app('store')->delivery_fee ?? 0) : 0;
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
            $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
            foreach ($cart as $item) {
                $subtotal = $item['final_price'] * $item['qty'];
                $message .= " *{$item['name']}*\n";
                $message .= "   {$item['qty']}x R$ " . number_format($item['price'], 2, ',', '.') . "\n";
                if (!empty($item['variations'])) {
                    $message .= "   {$item['variation']}:\n";
                    foreach($item['variations'] as $variationValue){
                        $add_price = number_format($variationValue['additional_price'], 2, ',', '.');
                        $message .= "      ↳{$variationValue['value']} (+ R$ {$add_price})\n";
                    }
                }
                if (!empty($item['observation'])) {
                    $message .= "   Obs: {$item['observation']}\n";
                }
                $message .= "   Subtotal: R$ " . number_format($subtotal, 2, ',', '.') . "\n\n";
            }
            $message .= "━━━━━━━━━━━━━━━━━━━━━━\n";
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
            $message .= "*PAGAMENTO*: ";
            $message .= $paymentMap[$request->payment_method] . "\n";
            if ($request->payment_method === 'cash' && !empty($request->change_for)) {
                $message .= "Troco para: R$ {$request->change_for}\n";
            }
            $store = $this->getStore();
            $url = "https://wa.me/55{$store->phone}?text=".urlencode($message);
            return redirect()->away($url);
       }catch (ValidationException $e) {
        return back()->withErrors(['error' => 'Informe os dados corretamente.'])->withInput();
       }catch (\Throwable $th) {
        return back()->withErrors(['error' => 'Ocorreu um erro ao finalizar seu pedido.'])->withInput();
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
            if($products->isEmpty() ){
                return redirect()->route('client.home', ['tenant' => app('store')->slug])->withErrors(['error' => 'Nada foi encontrado.']);
            }
            return view('client.search', compact('search', 'products'));
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