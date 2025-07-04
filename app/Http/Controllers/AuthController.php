<?php

namespace App\Http\Controllers;
use App\Models\RegistroUser;
use App\User;
use App\Services\UserService;
use App\Services\EncryptService;
use App\Services\PersonaService;
use App\Services\SynchronizationService;
use Log;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\VerificationEmail;
use App\Mail\ResetPasswordApi;


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
                'id' => $user->id,
                'dni' => $user->dni,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password
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
    public function generateNewVerificationEmail(Request $request)
    {
        try {
            $decrypted = $this->encryptService->decrypt($request);
            $data = json_decode($decrypted, true);

            if (!isset($data['data']['email'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }
            $imagePath2 = storage_path('app/public/images/firma.jpg');

            $dni = $data['data']['dni'];
            $email = $data['data']['email'];

            $person = $this->personService->getByDni($dni);
            if ($person->activo == 1) {
                $nombre = $person->nombre_p . ' ' . $person->apellido;
                $token = $this->userService->createNewToken($dni);

                try {
                    Mail::to($email)->send(new VerificationEmail($nombre, $token, $imagePath2));
                } catch (Exception $e) {
                    Log::error('Error al enviar mail: ' . $e->getMessage());
                    return response()->json(['message' => 'Error, no se pudo enviar el mail. Por favor genere la verificación nuevamente'], 400);
                }

                return response()->json(['message' => 'Mail reenviado correctamente'], 200);
            } else {

                return response()->json(['message' => 'La persona no está activa en la empresa'], 400);
            }

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error generating new verification email: ' . $e->getMessage());
            return response()->json(['message' => 'Error al generar el mail de verificación'], 500);
        }


    }
    public function sendMailResetPassword(Request $request)
    {
        try {
            $imagePath2 = storage_path('app/public/images/firma.jpg');

            $decrypted = $this->encryptService->decrypt($request);
            $data = json_decode($decrypted, true);

            $dni = $data['data']['dni'];
            $email = $data['data']['email'];

            $person = $this->personService->getByDni($dni);
            if (!is_object($person)) {
                return response()->json(['message' => 'La persona no existe'], 400);
            }
            $user = $this->userService->getByDni($dni);
            if ($person->activo == 1) {
                if ($user->activo == 1) {
                    if ($user->email == $email) {
                        $nombre = $person->nombre_p . ' ' . $person->apellido;
                        $token = $this->userService->createNewTokenUser($dni);
                        try {
                            Mail::to($email)->send(new ResetPasswordApi($nombre, $token, $imagePath2));
                        } catch (Exception $e) {
                            Log::error('Error al enviar mail: ' . $e->getMessage());
                            return response()->json(['message' => 'Error, no se pudo enviar el mail. Por favor envie el mail nuevamente'], 400);
                        }

                        return response()->json(['message' => 'Mail enviado correctamente!'], 200);
                    } else {
                        return response()->json(['message' => 'El usuario no está registrado'], 400);
                    }

                } else {
                    return response()->json(['message' => 'El usuario no está activo'], 400);
                }


            } else {
                return response()->json(['message' => 'La persona no está activa en la empresa'], 400);
            }


        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error sending reset password email: ' . $e->getMessage());
            return response()->json(['message' => 'Error al enviar el mail de restablecimiento de contraseña'], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $decrypted = $this->encryptService->decrypt($request);
            $data = json_decode($decrypted, true);

            $dni = $data['data']['dni'];
            $password = $data['data']['password'];

            $user = $this->userService->resetPassword($dni, $password);
            if (is_object($user)) {
                $userArray = $user->toArray();
                $userArray['password'] = $user->password;
                $this->synchronizationService->updateUserWithAgenda($userArray);
                return response()->json(['message' => 'Contraseña restablecida correctamente!'], 200);
            } else {
                return response()->json(['message' => 'Error al restablecer la contraseña'], 500);
            }

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error resetting password: ' . $e->getMessage());
            return response()->json(['message' => 'Error al restablecer la contraseña'], 500);
        }
    }

    public function cleanTokens(Request $request)
    {
        try {
            $decrypted = $this->encryptService->decrypt($request);
            $data = json_decode($decrypted, true);

            $dni = $data['data']['dni'];
            Log::info('DNI para limpiar tokens: ' . $dni);
            if (!$this->userService->cleanTokens($dni)) {
                return response()->json(['message' => 'Error al limpiar los tokens'], 500);
            }
            return response()->json(['message' => 'Tokens limpiados correctamente'], 200);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error cleaning tokens: ' . $e->getMessage());
            return response()->json(['message' => 'Error al limpiar los tokens'], 500);
        }
    }




}
