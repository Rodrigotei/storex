<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\BlockSubdomainAccess;
use App\Http\Middleware\BlockSubdomainDashboardAccess;
use App\Http\Middleware\EnsureRegisterSuccess;
use App\Http\Middleware\SetTenantDataBaseClient;
use Illuminate\Support\Facades\Route;

Route::domain(config('app.domain'))->middleware(BlockSubdomainAccess::class)->group(function () {
    Route::view('/', 'website.home')->name('home');
    Route::view('/register', 'website.register');
    Route::view('/payment', 'website.payment')->middleware(EnsureRegisterSuccess::class)->name('payment');
    Route::post('/register', [UsersController::class, 'store'])->name('register');
});

Route::domain(config('app.domain'))->middleware(['auth', BlockSubdomainDashboardAccess::class])->prefix('dashboard')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard.home');
    Route::resource('/categories', CategoriesController::class)->except(['show'])->names('dashboard.categories');
    Route::resource('/products', ProductsController::class)->except(['show'])->names('dashboard.products');
    Route::delete('/products/image/{id}', [ProductsController::class, 'deleteImage'])->name('dashboard.product.delete-image');
    Route::get('/profile/edit', [UsersController::class, 'edit'])->name('dashboard.profile.edit');
    Route::patch('/profile', [UsersController::class, 'update'])->name('dashboard.profile.update');
});

Route::domain('{tenant}.'.config('app.domain'))->middleware(SetTenantDataBaseClient::class)->prefix('loja')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('client.home');
    Route::get('/categories', [ClientController::class, 'categories'])->name('client.categories');
    Route::get('/category/{id}', [ClientController::class, 'category'])->name('client.category');
    Route::get('/product/{id}', [ClientController::class, 'product'])->whereNumber('id')->name('client.product');
    Route::get('/cart', [ClientController::class, 'cart'])->name('client.cart');
    Route::post('/cart', [ClientController::class, 'add'])->name('client.cart.add');
    Route::delete('/cart', [ClientController::class, 'delete'])->name('client.cart.delete');
    Route::post('/order/finish', [ClientController::class, 'orderFinish'])->name('client.order.finish');
    Route::post('/search', [ClientController::class, 'search'])->name('client.search');
});

Route::fallback(function () {
    return response()->view('404', [], 404);
});
