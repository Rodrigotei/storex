<?php

namespace App\Http\Middleware;

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
        $slug = 'rt-lanchonete';

        $dbName = str_replace('-','_',$slug);
        config(['database.connections.store.database' => $dbName]);
        DB::purge('store');
        DB::reconnect('store');

        app()->instance('slug', $slug);

        return $next($request);
    }
}
