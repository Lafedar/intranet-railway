<?php

namespace App\Http\Controllers;
use App\Services\EncryptService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\User;
use Maatwebsite\Excel\Concerns\ToArray;


class CryptoController extends Controller
{
    protected $userService;
    protected $encryptService;
    public function __construct(UserService $userService, EncryptService $encryptService)
    {
        $this->userService = $userService;
        $this->encryptService = $encryptService;

    }
    public function getEncryptionKey(Request $request)
    {
        try {
            Log::info('Cookies recibidas:', $request->cookies->all());
            Log::info('Sesión actual:', session()->all());
            Log::info('Session ID: ' . session()->getId());


            if (!$request->session()->has('aes_key')) {

                $key = random_bytes(32); // 256 bits = 32 bytes

                $request->session()->put('aes_key', base64_encode($key));

            } else {

                $key = base64_decode($request->session()->get('aes_key'));

            }

            return response()->json([

                'key' => base64_encode($key)



            ]);
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error getting encryption key: ' . $e->getMessage());
            return response()->json(['error' => 'Error obteniendo la clave de encriptacion'], 500);
        }

    }
    public function loginApi(Request $request)
    {
        try {

            if (!$request->session()->has('aes_key')) {
                return response()->json(['error' => 'Missing AES session key'], 400);
            }
            $plaintext = $this->encryptService->decrypt($request);

            if ($plaintext === null) {
                return response()->json(['error' => 'Decryption failed'], 400);
            }

            $credentials = json_decode($plaintext, true);
            if (!isset($credentials['usuario']) || !isset($credentials['password'])) {
                return response()->json(['error' => 'Formato inválido'], 400);
            }

            $flag = false;
            $registerUser = $this->userService->validateRegisterUser($credentials['usuario'], $credentials['password']);
            if (is_object($registerUser)) {
                if ($registerUser->email_verified_at == 0) {
                    $respuesta = json_encode(['error' => 'Debes verificar tu email antes de iniciar sesión']);
                    $flag = true;
                }
            }
            $user = $this->userService->validate($credentials['usuario'], $credentials['password']);
            if (!$flag) {
                if (!is_object($user)) {
                    $respuesta = json_encode(['error' => 'Credenciales inválidas']);
                } elseif ($user->activo == 0) {
                    $respuesta = json_encode(['error' => 'El usuario no está activo']);
                } elseif ($user->email_verified_at == 0) {
                    $respuesta = json_encode(['error' => 'Debes verificar tu email antes de iniciar sesión']);
                } elseif (is_null($user->dni)) {
                    $respuesta = json_encode(['error' => 'El usuario no tiene DNI registrado']);
                } else {
                    $respuesta = json_encode([
                        'id' => $user->id,
                        'nombre' => $user->name,
                        'email' => $user->email,
                        'dni' => $user->dni,
                    ]);
                }
            }

            // Encriptar respuesta
            $responseIv = random_bytes(12);
            $aesKeyBase64 = $request->session()->get('aes_key');
            $key = base64_decode($aesKeyBase64);

            $ciphertextWithTag = $this->encryptService->encrypt($respuesta, $key, $responseIv);
            return response()->json([
                'ciphertext' => base64_encode($ciphertextWithTag),
                'iv' => base64_encode($responseIv),
            ]);
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - User login failed: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el login'], 500);
        }

    }


}






