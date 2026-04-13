<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockSubdomainAccess
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
            return redirect(env('APP_URL'));
        }
        return $next($request);
    }
}
