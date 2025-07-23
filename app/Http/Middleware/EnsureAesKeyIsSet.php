<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAesKeyIsSet
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
        $key = $request->header('X-AES-Key');

        if (!$key) {
            return response()->json(['error' => 'Missing AES key'], 403);
        }

        // Validar longitud y formato si querés
        if (strlen(base64_decode($key, true)) !== 32) {
            return response()->json(['error' => 'Invalid AES key format'], 403);
        }

        // Guardar la clave para usarla en los controladores
        app()->instance('aes_key', base64_decode($key, true));

        return $next($request);
    }
}