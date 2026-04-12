<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $tenant_id = auth()->user()->store->id;
        return view('dashboard.home', [
            'totalProducts' => Product::where('tenant_id', $tenant_id)->count(),
            'totalCategories' => Category::where('tenant_id', $tenant_id)->count(),
            'totalImagesProducts' => ProductImage::where('tenant_id', $tenant_id)->count(),
            'totalServices' => Service::where('tenant_id', $tenant_id)->count(),
            'recentProducts' => Product::with('productImages')->latest()->take(5)->where('tenant_id', $tenant_id)->get(),
        ]);
    }
}
