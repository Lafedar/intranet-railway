<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ForceCors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Lista de orígenes permitidos
        $allowedOrigins = [
            'https://extranetlafedar.netlify.app',
            'http://localhost:3000',
            'https://intranet-railway-production.up.railway.app',
        ];

        // Obtener el origen de la request
        $origin = $request->headers->get('Origin');

        // Si el origen es válido, lo usamos. Si no, devolvemos 'null' para bloquear.
        $allowOrigin = in_array($origin, $allowedOrigins) ? $origin : 'null';

        // Manejar preflight request
        if ($request->getMethod() === 'OPTIONS') {
            Log::info('ForceCors middleware OPTIONS request origen: ' . $origin);
            return response('', 204)
                ->header('Access-Control-Allow-Origin', $allowOrigin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('X-Force-Cors', 'true');
        }

        // Continuar con la respuesta normal
        $response = $next($request);

        return $response
            ->header('Access-Control-Allow-Origin', $allowOrigin)
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Expose-Headers', 'Authorization')
            ->header('X-Force-Cors', 'true');
    }

}