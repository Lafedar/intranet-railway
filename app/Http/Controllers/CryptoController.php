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
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


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
            // 🔐 Obtener clave AES desde encabezado
            $aesKeyHeader = $request->header('X-AES-Key');
            if (!$aesKeyHeader) {
                return response()->json(['error' => 'Missing AES key header'], 400);
            }

            $base64Key = strtr($aesKeyHeader, '-_', '+/');
            $aesKey = base64_decode($base64Key);
            if (!$aesKey || strlen($aesKey) !== 32) {
                return response()->json(['error' => 'Clave AES inválida'], 400);
            }

            // 🔓 Desencriptar payload
            $plaintext = $this->encryptService->decrypt($request, $aesKey);
            if ($plaintext === null) {
                return response()->json(['error' => 'Decryption failed'], 400);
            }

            $credentials = json_decode($plaintext, true);
            if (!isset($credentials['usuario']) || !isset($credentials['password'])) {
                return response()->json(['error' => 'Formato inválido'], 400);
            }

            // ✅ Validación de usuario
            $flag = false;
            $respuesta = null;
            $refreshToken = null;

            $registerUser = $this->userService->validateRegisterUser($credentials['usuario'], $credentials['password']);
            if (is_object($registerUser) && $registerUser->email_verified_at == 0) {
                $respuesta = json_encode(['error' => 'Debes verificar tu email antes de iniciar sesión']);
                $flag = true;
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
                    //Generar Access Token (30 min)
                    $accessTokenPayload = [
                        'sub' => $user->id,
                        'email' => $user->email,
                        'iat' => time(),
                        'exp' => time() + (1 * 60), // 1 minuto
                    ];
                    $accessToken = JWT::encode($accessTokenPayload, env('JWT_SECRET'), 'HS256');

                    //Generar Refresh Token (7 días)
                    $refreshTokenPayload = [
                        'sub' => $user->id,
                        'iat' => time(),
                        'exp' => time() + (7 * 24 * 60 * 60), // 7 días
                    ];
                    $refreshToken = JWT::encode($refreshTokenPayload, env('JWT_REFRESH_SECRET'), 'HS256');

                    //Armar respuesta
                    $respuesta = json_encode([
                        'token' => $accessToken,
                        'id' => $user->id,
                        'nombre' => $user->name,
                        'email' => $user->email,
                        'dni' => $user->dni,
                    ]);
                }
            }

            //Encriptar respuesta con AES
            $responseIv = random_bytes(12);
            $ciphertextWithTag = $this->encryptService->encrypt($respuesta, $aesKey, $responseIv);


            Log::info('Seteando cookie con refresh token: ' . $refreshToken);
            //Devolver respuesta con cookie
            return response()->json([
                'ciphertext' => base64_encode($ciphertextWithTag),
                'iv' => base64_encode($responseIv),
                'token' => $accessToken,
            ])->withCookie(
                    cookie(
                        'refresh_token',
                        $refreshToken,
                        60 * 24 * 7, // 7 días
                        '/',
                        null, // dominio automático (o definilo)
                        true, // secure
                        true, // httpOnly
                        false,
                        'None'
                    )
                );

        } catch (Exception $e) {
            Log::error('Error en loginApi: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el login'], 500);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
        
            $refreshToken = $request->cookie('refresh_token');

            if (!$refreshToken) {
                return response()->json(['error' => 'Refresh token missing'], 401);
            }

            $decoded = JWT::decode($refreshToken, new Key(env('JWT_REFRESH_SECRET'), 'HS256'));

            // Verificar expiración
            if ($decoded->exp < time()) {
                return response()->json(['error' => 'Refresh token expired'], 401);
            }
            
            // Obtener usuario
            $userId = $decoded->sub;
            $user = User::find($userId);
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
            

            // Generar nuevo Access Token
            $accessTokenPayload = [
                'sub' => $user->id,
                'email' => $user->email,
                'iat' => time(),
                'exp' => time() + (1 * 60), // 1 minuto
            ];
            $accessToken = JWT::encode($accessTokenPayload, env('JWT_SECRET'), 'HS256');

            return response()->json([
                'access_token' => $accessToken,
            ])->withCookie(
                    cookie(
                        'refresh_token',
                        $refreshToken,       // 🔁 Reutilizás o rotás el refresh token
                        60 * 24 * 7,         // 7 días
                        '/',
                        null,                // Dominio, podés usar null o tu dominio si lo necesitás
                        true,                // Secure
                        true,                // HttpOnly
                        false,
                        'None'               // SameSite=None para cross-site
                    )
                );

        } catch (Exception $e) {
            Log::error('Error al refrescar token: ' . $e->getMessage());
            return response()->json(['error' => 'Token inválido'], 401);
        }
    }




}