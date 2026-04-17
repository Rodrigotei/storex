<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BlockSubdomainDashboardAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $parts = explode('.', $host);
        if (count($parts) > 2 || (app()->environment('local') && count($parts) > 1 && $parts[0] !== 'localhost')) {
            return redirect(env('APP_URL').'dashboard');
        }
        return $next($request);
    }
}
