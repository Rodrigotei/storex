<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('dashboard.home', [
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalImagesProducts' => ProductImage::count(),
            'recentProducts' => Product::with('productImages')->latest()->take(5)->get(),
        ]);
    }
}
