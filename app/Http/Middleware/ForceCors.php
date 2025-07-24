<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
            'https://intranet.lafedar.net',
            'http://localhost:8087',
        ];

        // Obtener el origen de la request
        $origin = $request->headers->get('Origin');

        // Si el origen es válido, lo usamos. Si no, devolvemos 'null' para bloquear.
        //$allowOrigin = in_array($origin, $allowedOrigins) ? $origin : 'null';
        if (!in_array($origin, $allowedOrigins)) {
            return response('Origin not allowed', 403);
        }
        $allowOrigin = $origin;


        // Manejar preflight request
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 204)
                ->header('Access-Control-Allow-Origin', $allowOrigin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-AES-Key')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('X-Force-Cors', 'true');
        }

        // Continuar con la respuesta normal
        $response = $next($request);

        return $response
            ->header('Access-Control-Allow-Origin', $allowOrigin)
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-AES-Key')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Expose-Headers', 'Authorization')
            ->header('X-Force-Cors', 'true');
    }

}