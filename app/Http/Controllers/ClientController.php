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

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
