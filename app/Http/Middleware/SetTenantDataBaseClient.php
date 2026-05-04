<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Exception;
use Illuminate\Http\Request;

class SetTenantDataBaseClient
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $slug = $request->route('tenant');
            if (!$slug || $slug === 'www') {
                throw new Exception('Invalid tenant slug');
            }
            $store = Store::where('slug', $slug)->first();
            if (!$store) {
                throw new Exception("Store not found");
            }
            app()->instance('store', $store);
            return $next($request);
        } catch (\Throwable $th) {
            return response()->view('client.error');
        }
    }
}
