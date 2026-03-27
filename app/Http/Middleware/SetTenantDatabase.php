<?php

namespace App\Http\Middleware;

use App\Models\Store;
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
        $user = auth()->user()->load('store.address');
        $dbName = $user->store->db_name;
        config(['database.connections.store.database' => $dbName]);
        DB::purge('store');
        DB::reconnect('store');     
        return $next($request);
    }
}
