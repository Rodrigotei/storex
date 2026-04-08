<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetTenantDataBaseClient
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $slug = explode('.', $request->getHost())[0];
            $store = Store::where('slug', $slug)->firstOrFail();
            app()->instance('slug', $slug);
            return $next($request);
        } catch (\Throwable $th) {
            return abort(403,'Empresa não encontrada');
        }
    }
}
