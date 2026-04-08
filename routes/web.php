<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\SetTenantDatabase;
use App\Http\Middleware\SetTenantDataBaseClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::view('/', 'website.home'); 
Route::view('/register', 'website.register'); 
Route::view('/payment', 'website.payment')->name('payment'); 
Route::post('/register', [UsersController::class, 'store'])->name('register'); 

Route::middleware('auth')->prefix('dashboard')->group(function(){
    Route::get('/', [HomeController::class, 'index'])->name('dashboard.home');
    Route::resource('/categories', CategoriesController::class)->except(['show'])->names('dashboard.categories');
    Route::resource('/products', ProductsController::class)->except(['show'])->names('dashboard.products');
    Route::delete('/products/image/{id}', [ProductsController::class, 'deleteImage'])->name('dashboard.product.delete-image');

    Route::resource('/profile', UsersController::class)->except(['show'])->names('dashboard.profile');
});

Route::middleware(SetTenantDataBaseClient::class)->prefix('loja')->group(function(){
    Route::get('/', [ClientController::class, 'index'])->name('client.home');
    Route::get('/categories', [ClientController::class, 'categories'])->name('client.categories');
    Route::get('/category/{id}', [ClientController::class, 'category'])->name('client.category');
    Route::get('/product/{id}', [ClientController::class, 'product'])->name('client.product');
    Route::get('/cart', [ClientController::class, 'cart'])->name('client.cart');
    Route::post('/cart', [ClientController::class, 'add'])->name('client.cart.add');
    Route::delete('/cart', [ClientController::class, 'delete'])->name('client.cart.delete');
    Route::post('/order/finish', [ClientController::class, 'orderFinish'])->name('client.order.finish');
    Route::post('/search', [ClientController::class, 'search'])->name('client.search');
});