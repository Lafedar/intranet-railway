<?php

namespace App\Http\Controllers;

use App\Services\MedicationsRequestService;
use App\Services\PersonaService;
use App\Services\EncryptService;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\MedicationApprovedMail;
use Illuminate\Http\Request;
use App\Mail\MedicationInfoMail;
use Illuminate\Support\Facades\Log;
use App\Services\GeneralParametersService;
use App\Services\UserService;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Mail\MedicationNotificationMail;
use App\Mail\ResetPasswordApi;
use App\Mail\MedicationNotificationUser;
use App\Mail\VerificationEmail;
use Illuminate\Support\Str;






class MedicationsRequestController extends Controller
{
    protected $medicationsRequestService;
    protected $personaService;

    protected $genParametersService;
    protected $encryptService;

    protected $userService;


    public function __construct(MedicationsRequestService $medicationsRequestService, PersonaService $personaService, GeneralParametersService $genParametersService, EncryptService $encryptService, UserService $userService)
    {
        $this->medicationsRequestService = $medicationsRequestService;
        $this->personaService = $personaService;
        $this->genParametersService = $genParametersService;
        $this->encryptService = $encryptService;
        $this->userService = $userService;
    }

    /*API*/
    public function saveNewMedicationRequest(Request $request)
    {
        try {
            $imagePath2 = storage_path('app/public/images/firma.jpg');
            $decrypted = $this->encryptService->decrypt($request);
            if (!$decrypted) {
                return response()->json(['message' => 'Error al desencriptar los datos'], 400);
            }
            $data = json_decode($decrypted, true);

            if (!isset($data['data'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }

            $payload = $data['data'];

            $person = $this->personaService->getByDni($payload['dni_user']);
            $user = $this->userService->getByDni($payload['dni_user']);
            $mailsString = $this->genParametersService->getMailsToMedicationRequests();
            $mails = explode(',', $mailsString);

            if (!$person) {
                return response()->json(['message' => 'La persona no existe'], 401);
            } else {
                $create = $this->medicationsRequestService->create($payload);

                if ($create) {
                    foreach ($mails as $mail) {
                        Mail::to(trim($mail))->send(new MedicationNotificationMail($payload, $person, $imagePath2));
                    }
                    Mail::to($user->email)->send(new MedicationNotificationUser($payload, $person, $imagePath2));
                    return response()->json(['message' => 'Solicitud creada exitosamente! Se enviará un correo de confirmación.'], 200);
                } else {
                    return response()->json(['message' => 'Hubo un problema al crear la solicitud'], 500);
                }
            }


        } catch (Exception $e) {
            Log::error('Error en saveNewMedicationRequest: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno del servidor'], 500);
        }
    }

    public function getAllMedicationRequestAndItemsByUserDni(Request $request)
    {
        try {
            //Desencripto los datos
            $decrypted = $this->encryptService->decrypt($request);
            $data = json_decode($decrypted, true);

            // Validación
            if (!isset($data['data']['dni_user'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }

            // Acceder al dni_user correctamente
            $dni = $data['data']['dni_user'];

            // Obtener las solicitudes con sus items
            $requestsData = $this->medicationsRequestService->getAllMedicationRequestAndItemsByUserDni($dni);



            //Encripto los datos
            $responseIv = random_bytes(12);
            $aesKeyBase64 = $request->session()->get('aes_key');
            $key = base64_decode($aesKeyBase64);

            $ciphertextWithTag = $this->encryptService->encrypt($requestsData, $key, $responseIv);
            return response()->json([
                'ciphertext' => base64_encode($ciphertextWithTag),
                'iv' => base64_encode($responseIv),
            ]);



        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting all medication requests by user dni: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar el item'], 500);
        }

    }



    public function createUserApi(Request $request)
    {
        try {
            $imagePath2 = storage_path('app/public/images/firma.jpg');
            $decrypted = $this->encryptService->decrypt($request);
            $data = json_decode($decrypted, true);

            if (!isset($data['data']['email'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }

            $dni = $data['data']['dni'];
            $email = $data['data']['email'];
            $password = $data['data']['password'];

            if (!is_object($this->personaService->getByDni($dni))) {
                return response()->json(['message' => 'El Dni no existe'], 400);
            }

            /*DESCOMENTAR*/
            if (is_object($this->userService->getByDni($dni))) {
                return response()->json(['message' => 'El Dni ingresado ya tiene un usuario registrado'], 400);
            }

            if ($this->userService->validateMail($email)) {
                return response()->json(['message' => 'El email ya esta registrado'], 400);
            }


            $person = $this->personaService->getByDni($dni);
            if ($person->activo == 0) {
                return response()->json(['message' => 'La persona no está activa en la empresa'], 400);
            }
            $nombre = $person->nombre_p . ' ' . $person->apellido;

            $user = $this->userService->createRegisterUserApi($dni, $person->nombre_p, $person->apellido, $email, $password);

            if ($user != null) {
                Mail::to($email)->send(new VerificationEmail($nombre, $user->remember_token, $imagePath2));
                return response()->json(['message' => 'Usuario creado exitosamente! Se enviará un correo de verificación.'], 200);
            } else {
                return response()->json(['message' => 'La persona ya tiene usuario registrado'], 400);
            }

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error creating user: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear el usuario'], 500);
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

            $person = $this->personaService->getByDni($dni);
            if ($person->activo == 1) {
                $nombre = $person->nombre_p . ' ' . $person->apellido;
                $token = $this->userService->createNewToken($dni);

                Mail::to($email)->send(new VerificationEmail($nombre, $token, $imagePath2));
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

            $person = $this->personaService->getByDni($dni);
            if (!is_object($person)) {
                return response()->json(['message' => 'La persona no existe'], 400);
            }
            $user = $this->userService->getByDni($dni);
            if ($person->activo == 1) {
                if ($user->activo == 1) {
                    if ($user->email == $email) {
                        $nombre = $person->nombre_p . ' ' . $person->apellido;
                        $token = $this->userService->createNewTokenUser($dni);
                        Mail::to($email)->send(new ResetPasswordApi($nombre, $token, $imagePath2));
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
            Log::info('DNI: ' . $dni);
            Log::info('Password: ' . $password);

            if ($this->userService->resetPassword($dni, $password)) {
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

            /*if (!isset($data['data']['dni'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }*/

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
