<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Closure;
use Log;

class VerifyServiceApiToken extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $token = $request->bearerToken();
        Log::info('Token recibido: ' . $token);
        Log::info('Token esperado: ' . env('EMPRESA_API_TOKEN'));

        if (!$token || $token !== env('EMPRESA_API_TOKEN')) {

            return response()->json(['error' => 'Unauthorized'], 401);

        }


        return $next($request);

    }
}