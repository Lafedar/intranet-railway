<?php

namespace App\Http\Controllers;
use App\Models\RegistroUser;
use App\User;
use App\Services\UserService;
use App\Services\EncryptService;
use Carbon\Carbon;
use App\Services\PersonaService;
use App\Services\SynchronizationService;
use Log;
class AuthController extends Controller
{
    protected $userService;
    protected $encryptService;

    protected $personService;

    protected $synchronizationService;

    public function __construct(UserService $userService, EncryptService $encryptService, PersonaService $personService, SynchronizationService $synchronizationService)
    {

        $this->userService = $userService;
        $this->encryptService = $encryptService;
        $this->personService = $personService;
        $this->synchronizationService = $synchronizationService;
    }

    public function verificarEmail($token)
    {
        Log::info("Token recibido desde el controlador: " . $token);
        $registerUser = RegistroUser::on('mysql_write')->where('remember_token', $token)->first();

        if (!$registerUser) {
            Log::info("No encontro al usuario. Token: " . $token);
            return redirect()->away('https://extranetlafedar.netlify.app?message=token');
        }
        Log::info("Encontro al usuario. Token: " . $token);
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
        $registerUser->remember_token_expires_at = null;
        $registerUser->save();

        $user = $this->userService->createUserApi(
            $registerUser->dni,
            $registerUser->name,
            $registerUser->email,
            $registerUser->password
        );

        $this->synchronizationService->saveNewUserInAgenda([
            'dni' => $registerUser->dni,
            'name' => $registerUser->name,
            'email' => $registerUser->email,
            'password' => $registerUser->password
        ]);
        $person = $this->personService->getByDniWrite($registerUser->dni);
        $person->usuario = $user->id;
        $person->save();
        $this->synchronizationService->updatePersonWithAgenda($person->toArray());


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
