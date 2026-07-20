<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $store = auth()->user()->store()->with('address')->firstOrFail();
            $tenant_id = $store->id;
            $totalProducts = Product::where('tenant_id', $tenant_id)->count();
            $totalCategories = Category::where('tenant_id', $tenant_id)->count();

            return view('dashboard.home', [
                'store' => $store,
                'storeUrl' => route('client.home', ['tenant' => $store->slug]),
                'totalProducts' => $totalProducts,
                'totalCategories' => $totalCategories,
                'totalImagesProducts' => ProductImage::where('tenant_id', $tenant_id)->count(),
                'recentProducts' => Product::with('productImages')->latest()->take(5)->where('tenant_id', $tenant_id)->get(),
                'onboarding' => [
                    [
                        'title' => 'Complete os dados da loja',
                        'description' => 'Adicione descrição, endereço e telefone para seus clientes.',
                        'complete' => filled($store->description) && filled($store->phone) && $store->address !== null,
                        'url' => route('dashboard.profile.edit'),
                    ],
                    [
                        'title' => 'Crie sua primeira categoria',
                        'description' => 'Organize os produtos para facilitar a navegação.',
                        'complete' => $totalCategories > 0,
                        'url' => route('dashboard.categories.create'),
                    ],
                    [
                        'title' => 'Cadastre seu primeiro produto',
                        'description' => 'Informe foto, preço e os detalhes do produto.',
                        'complete' => $totalProducts > 0,
                        'url' => route('dashboard.products.create'),
                    ],
                    [
                        'title' => 'Veja e compartilhe seu catálogo',
                        'description' => 'Confira como a loja aparece para seus clientes.',
                        'complete' => $totalProducts > 0,
                        'url' => route('client.home', ['tenant' => $store->slug]),
                        'external' => true,
                    ],
                ],
            ]);
        } catch (\Throwable $th) {
            return view('dashboard.error');
        }
    }
}
