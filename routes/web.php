<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Home page';
});

Route::middleware('auth')->prefix('dashboard')->group(function(){
    Route::get('/', function(){
        return 'dashboard';
    });
});