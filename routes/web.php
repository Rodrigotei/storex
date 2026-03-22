<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\SetTenantDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Home page';
});

Route::middleware('auth')->prefix('dashboard')->group(function(){
    Route::get('/', [HomeController::class, 'index'])->middleware(SetTenantDatabase::class)->name('dashboard.home');
    Route::resource('/categories', CategoriesController::class)->except(['show'])->middleware(SetTenantDatabase::class)->names('dashboard.categories');
    Route::resource('/products', ProductsController::class)->except(['show'])->middleware(SetTenantDatabase::class)->names('dashboard.products');
    Route::delete('/products/image/{id}', [ProductsController::class, 'deleteImage'])->middleware(SetTenantDatabase::class)->name('dashboard.product.delete-image');

    Route::resource('/profile', UsersController::class)->except(['show'])->names('dashboard.profile');
});