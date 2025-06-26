<?php

namespace App\Http\Controllers;
use App\Models\RegistroUser;
use App\User;
use App\Services\UserService;
use App\Services\EncryptService;
use Carbon\Carbon;
use App\Services\PersonaService;

class AuthController extends Controller
{
    protected $userService;
    protected $encryptService;

    protected $personService;

    public function __construct(UserService $userService, EncryptService $encryptService, PersonaService $personService)
    {

        $this->userService = $userService;
        $this->encryptService = $encryptService;
        $this->personService = $personService;
    }

    public function verificarEmail($token)
    {
        $registerUser = RegistroUser::on('mysql_read')->where('remember_token', $token)->first();

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

        $registerUser = RegistroUser::on('mysql_write')->find($registerUser->id);
        // Token válido
        $registerUser->email_verified_at = 1;
        $registerUser->remember_token = null;
        $registerUser->save();

        $user = $this->userService->createUserApi(
            $registerUser->dni,
            $registerUser->name,
            $registerUser->email,
            $registerUser->password
        );

        $person = $this->personService->getByDniWrite($registerUser->dni);
        $person->usuario = $user->id;
        $person->save();

        return redirect()->away('https://extranetlafedar.netlify.app?message=success');
    }

    public function redirectToResetPassword($token)
    {
        $user = User::on('mysql_read')->where('remember_token', $token)->first();

        if (!$user) {
            return redirect()->away('https://extranetlafedar.netlify.app?message=token'); //al mensaje lo configuro en el frontend
        }

        
        if (now()->lessThan($user->remember_token_expires_at)) {

            $dni = $user->dni;
            $email = $user->email;

            $key = random_bytes(32);
            $iv = random_bytes(12);
            $data = json_encode(['dni' => $dni, 'email' => $email]);

            $tag = '';
            $ciphertext = openssl_encrypt($data, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
            $ciphertextWithTag = $ciphertext . $tag;

            // Redirección con datos cifrados
            $url = 'https://extranetlafedar.netlify.app/resetPassword?' .
                'ciphertext=' . urlencode(base64_encode($ciphertextWithTag)) .
                '&iv=' . urlencode(base64_encode($iv)) .
                '&key=' . urlencode(base64_encode($key));

            return redirect()->away($url);
        } else {
            return redirect()->away('https://extranetlafedar.netlify.app?message=expired'); //al mensaje lo configuro en el frontend
        }



    }

}
