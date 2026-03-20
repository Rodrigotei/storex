<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetTenantDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $dbName = str_replace('-', '_', auth()->user()->slug);
        config(['database.connections.store.database' => $dbName]);
        DB::purge('store');
        DB::reconnect('store');
        return $next($request);
    }
}
