<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/active-account', [WebhookController::class, 'handle'])->middleware('throttle:60,1');
