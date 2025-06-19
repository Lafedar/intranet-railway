<?php

namespace App\Http\Controllers;
use App\Models\RegistroUser;
use App\Services\UserService;
use App\Services\EncryptService;

class AuthController extends Controller
{
    protected $userService;
    protected $encryptService;

    public function __construct(UserService $userService, EncryptService $encryptService)
    {

        $this->userService = $userService;
        $this->encryptService = $encryptService;
    }

    public function verificarEmail($token)
    {
        $registerUser = RegistroUser::where('remember_token', $token)->first();

        if (!$registerUser) {
            return redirect()->away('https://extranetlafedar.netlify.app?message=token');
        }

        if (now()->greaterThan($registerUser->remember_token_expires_at)) {
            $dni = $registerUser->dni;
            $email = $registerUser->email;

            $key = random_bytes(32);
            $iv = random_bytes(12);
            $data = json_encode(['dni' => $dni, 'email' => $email]);

            $tag = '';
            $ciphertext = openssl_encrypt($data, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
            $ciphertextWithTag = $ciphertext . $tag;

            // Redirección con datos cifrados
            $url = 'https://extranetlafedar.netlify.app/verifyEmail?' .
                'ciphertext=' . urlencode(base64_encode($ciphertextWithTag)) .
                '&iv=' . urlencode(base64_encode($iv)) .
                '&key=' . urlencode(base64_encode($key));

            return redirect()->away($url);
        }

        // Token válido
        $registerUser->email_verified_at = 1;
        $registerUser->remember_token = null;
        $registerUser->save();

        $this->userService->createUserApi(
            $registerUser->dni,
            $registerUser->name,
            $registerUser->email,
            $registerUser->password
        );

        return redirect()->away('https://extranetlafedar.netlify.app?message=success');
    }


}
