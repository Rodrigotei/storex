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
            if(app()->environment('production')){
                $host = $request->getHost();
                $parts = explode('.', $host);
                if (count($parts) < 3) {
                    throw new \Exception('Subdomínio não informado');
                }
                if ($parts[0] === 'www') {
                    throw new \Exception('Erro de subdomínio, por favor acesse sem o www');
                }
                $slug = $parts[0];
                $store = Store::where('slug', $slug)->first();
                if(!$store){
                    throw new \Exception('Empresa não encontrada');
                }
            }elseif(app()->environment('local')){
                $host = $request->getHost();
                $parts = explode('.', $host);
                if (count($parts) < 2) {
                    throw new \Exception('Subdomínio não informado');
                }
                if ($parts[0] === 'www') {
                    throw new \Exception('Erro de subdomínio, por favor acesse sem o www');
                }
                $slug = $parts[0];
                $store = Store::where('slug', $slug)->first();
                if(!$store){
                    throw new \Exception('Empresa não encontrada');
                }
            }else{
                throw new \Exception('Ambiente não configurado para multi-tenant');
            }
            app()->instance('store', $store);
            return $next($request);
        } catch (\Throwable $th) {
            return abort(403,$th->getMessage());
        }
    }
}
