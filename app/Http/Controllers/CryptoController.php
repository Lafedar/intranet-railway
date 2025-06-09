<?php

namespace App\Http\Controllers;
use App\Services\EncryptService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\User;






class CryptoController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

    }
    public function getEncryptionKey(Request $request)
    {
        Log::info('Solicitud de clave AES recibida: ' . $request->session()->getId());
        if (!$request->session()->has('aes_key')) {

            $key = random_bytes(32); // 256 bits = 32 bytes

            $request->session()->put('aes_key', base64_encode($key));

        } else {

            $key = base64_decode($request->session()->get('aes_key'));

        }

        Log::info('Clave generada: ' . base64_encode($key));

        return response()->json([

            'key' => base64_encode($key)



        ]);


    }
    public function login(Request $request)
    {
        try {
            Log::info('Solicitud de login recibida: ' . $request->session()->getId());
            if (!$request->session()->has('aes_key')) {
                return response()->json(['error' => 'Missing AES session key'], 400);
            }

            $key = base64_decode($request->session()->get('aes_key'));

            $ciphertext = base64_decode($request->input('ciphertext'));
            $iv = base64_decode($request->input('iv'));

            $tag = substr($ciphertext, -16);
            $ciphertextOnly = substr($ciphertext, 0, -16);

            $plaintext = openssl_decrypt(
                $ciphertextOnly,
                'aes-256-gcm',
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );

            if ($plaintext === false) {
                return response()->json(['error' => 'Decryption failed'], 400);
            }

            Log::info('Mensaje desencriptado: ' . $plaintext);


            $credentials = json_decode($plaintext, true);
            if (!isset($credentials['usuario']) || !isset($credentials['password'])) {
                return response()->json(['error' => 'Formato inválido'], 400);
            }

            //Validar el usuario y contraseña

            $user = $this->userService->validate($credentials['usuario'], $credentials['password']);
            if (!is_object($user)) {
                $respuesta = json_encode([
                    'error' => 'Credenciales inválidas'
                ]);
            } elseif (is_null($user->dni)) {
                $respuesta = json_encode([
                    'error' => 'El usuario no tiene DNI registrado'
                ]);
            } else {
                $respuesta = json_encode([
                    'id' => $user->id,
                    'nombre' => $user->name,
                    'email' => $user->email,
                    'dni' => $user->dni,
                ]);
            }


            // Encriptar respuesta
            $responseIv = random_bytes(12);

            $ciphertextResponse = openssl_encrypt(
                $respuesta,
                'aes-256-gcm',
                $key,
                OPENSSL_RAW_DATA,
                $responseIv,
                $responseTag
            );

            $ciphertextWithTag = $ciphertextResponse . $responseTag;

            return response()->json([
                'ciphertext' => base64_encode($ciphertextWithTag),
                'iv' => base64_encode($responseIv),
            ]);
        } catch (Exception $e) {
            Log::error('Error en el login: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el login'], 500);
        }

    }


}






