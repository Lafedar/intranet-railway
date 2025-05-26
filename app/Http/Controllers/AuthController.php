<?php

namespace App\Http\Controllers;
use App\Services\EncryptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;




class AuthController extends Controller
{
    protected $encryptService;

    public function __construct(EncryptService $encryptService)
    {
        $this->encryptService = $encryptService;
    }

    public function login(Request $request)
    {
        try {
            $data = $this->encryptService->decrypt(
                $request->input('key'),
                $request->input('iv'),
                $request->input('payload')
            );

            if (!$data) {
                return response()->json(['message' => 'No se pudo descifrar'], 400);
            }

            $credentials = [
                'email' => $data['email'],
                'password' => $data['password']
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                return response()->json([
                    'message' => 'Login exitoso',
                    'user' => $user,

                ]);
            }
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al procesar los datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
