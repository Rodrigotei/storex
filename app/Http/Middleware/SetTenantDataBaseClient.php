<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetTenantDataBaseClient
{
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('tenant');
        if (!$slug || $slug === 'www') {
            abort(404);
        }
        $store = Store::where('slug', $slug)->first();
        if (!$store) {
            abort(404);
        }
        app()->instance('store', $store);
        return $next($request);
    }
}
