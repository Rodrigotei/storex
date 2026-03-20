<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Middleware\SetTenantDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Home page';
});

Route::middleware('auth')->prefix('dashboard')->group(function(){
    Route::view('/', 'dashboard.home')->name('dashboard.home');
    Route::resource('/categories', CategoriesController::class)->except(['show'])->middleware(SetTenantDatabase::class)->names('dashboard.categories');
});