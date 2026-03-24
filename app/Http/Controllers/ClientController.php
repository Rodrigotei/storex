<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {   
        $slug = app('slug');

        $store = Store::with('address')->where('slug', $slug)->first();
        $categories = Category::where('status', true)->get();
        $lastProducts = Product::with('productImages')->latest()->take(10)->where('status', true)->get();
        return view('client.index', compact('store', 'categories', 'lastProducts'));
    }

    public function categories()
    {
        $categories = Category::where('status', true)->get();
        return view('client.categories', compact('categories'));
    }

    public function category(string $id)
    {
        $products = Product::with('category')->where('category_id', $id)->where('status', true)->get();
        $categoryName = Category::find($id,'name');
        return view('client.category', compact('categoryName', 'products'));
    }

    public function product(string $id)
    {
        $product = Product::with(['category', 'productImages', 'productVariations', 'productVariations.variation'])->find($id);
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
            $product = Product::with(['productImages'])->findOrFail($request->product_id);
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
                    'price' => $product->price,
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
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => $th->getMessage()]);
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
}
