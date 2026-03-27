<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    public function index()
    {   
        $slug = app('slug');

        $store = Store::with('address')->where('slug', $slug)->first();
        $categories = Category::where('status', true)->get();
        $lastProducts = Product::with('productImages')->latest()->take(10)->where('status', true)->get();
        $promotionalProducts = Product::with('productImages')->whereNot('promotional_price', null)->where('status', true)->get();
        return view('client.index', compact('store', 'categories', 'lastProducts', 'promotionalProducts'));
    }
    public function categories()
    {
        $categories = Category::where('status', true)->get();
        if($categories->isEmpty()){
            return redirect()->route('client.home')->withErrors(['error' => 'Nenhuma categoria foi encontrada.']);
        }
        return view('client.categories', compact('categories'));
    }
    public function category(string $id)
    {
        $categoryName = Category::where('status', true)->find($id,'name');
        if(!$categoryName){
            return redirect()->route('client.home')->withErrors(['error' => 'Categoria não encontrada.']);
        }
        $products = Product::with('category')->where('category_id', $id)->where('status', true)->get();
        return view('client.category', compact('categoryName', 'products'));
    }
    public function product(string $id)
    {
        $product = Product::with(['category', 'productImages', 'productVariations', 'productVariations.variation'])->where('status', true)->find($id);
        if(!$product){
            return redirect()->route('client.home')->withErrors(['error' => 'Produto não encontrado.']);
        }
        return view('client.product', compact('product'));
    }
    public function cart()
    {
        $store  = Store::where('slug', app('slug'))->get('delivery_fee')->first();
        $delivery_fee = $store->delivery_fee;
        return view('client.cart', compact('delivery_fee'));
    }
    public function add(Request $request)
    {
        try {
            $cart = session()->get('cart', []);
            $product = Product::with(['productImages'])->where('status', true)->findOrFail($request->product_id);
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
        $store = Store::where('slug', app('slug'))->get('phone')->first();
        $url = "https://wa.me/55{$store->phone}?text=".urlencode($message);
        return redirect()->away($url);
       } catch (\Throwable $th) {
        return back()->withErrors(['error' => $th->getMessage()]);
       }
    }
    public function search(Request $request)
    {
       try {
            $request->validate(
                [
                    'search' => 'required'
                ],
            );
            $search = $request->search;
            $products = Product::whereLike('name', '%'.$search.'%')->where('status', true)->get();
            if($products->isEmpty() ){
                return redirect()->route('client.home')->withErrors(['error' => 'Nada foi encontrado.']);
            }
            return view('client.search', compact('search', 'products'));
       } catch (ValidationException $e) {
            return back()->withErrors(['error' => 'Nada encontrado.']);
       } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
       }
    }
}
