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
use Illuminate\Support\Facades\DB;
use Exception;
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
        $registerUser = RegistroUser::on('mysql_write')->where('remember_token', $token)->first();

        if (!$registerUser) {
            return redirect()->away('https://extranetlafedar.netlify.app?message=token');
        }

        if ($registerUser->email_verified_at == 1) {
            return redirect()->away('https://extranetlafedar.netlify.app?message=success');
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

            $url = 'https://extranetlafedar.netlify.app/verifyEmail?' .
                'ciphertext=' . urlencode(base64_encode($ciphertextWithTag)) .
                '&iv=' . urlencode(base64_encode($iv)) .
                '&key=' . urlencode(base64_encode($key));

            return redirect()->away($url);
        }

        DB::beginTransaction();

        try {
            $registerUser->email_verified_at = 1;
            $registerUser->remember_token_expires_at = null;
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

            // 🧨 CRÍTICO: sincronización con la otra base
            $success = $this->synchronizationService->saveNewUserInAgenda([
                'dni' => $registerUser->dni,
                'name' => $registerUser->name,
                'email' => $registerUser->email,
                'password' => $registerUser->password
            ]);

            if (!$success) {
                throw new Exception("Falló la sincronización con Agenda");
            }

            $this->synchronizationService->updatePersonWithAgenda($person->toArray());


            DB::commit();

            return redirect()->away('https://extranetlafedar.netlify.app?message=success');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in class: ' . get_class($this) . ' .Error verifiying user email' . $e->getMessage());
            return redirect()->away('https://extranetlafedar.netlify.app?message=error');
        }
    }

    public function redirectToResetPassword($token)
    {
        try {
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
                return redirect()->away('https://extranetlafedar.netlify.app?message=expired');
            }


        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error redirecting to reset password' . $e->getMessage());
        }


    }

}
