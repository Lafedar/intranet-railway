<?php

namespace App\Http\Controllers;

use App\Services\MedicationsRequestService;
use App\Services\PersonaService;
use App\Services\EncryptService;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\GeneralParametersService;
use App\Services\UserService;
use App\Mail\MedicationNotificationMail;
use App\Mail\MedicationNotificationUser;
use App\Services\SynchronizationService;


class MedicationsRequestController extends Controller
{
    protected $medicationsRequestService;
    protected $personaService;

    protected $genParametersService;
    protected $encryptService;

    protected $userService;
    protected $synchronizationService;


    public function __construct(MedicationsRequestService $medicationsRequestService, PersonaService $personaService, GeneralParametersService $genParametersService, EncryptService $encryptService, UserService $userService, SynchronizationService $synchronizationService)
    {
        $this->medicationsRequestService = $medicationsRequestService;
        $this->personaService = $personaService;
        $this->genParametersService = $genParametersService;
        $this->encryptService = $encryptService;
        $this->userService = $userService;
        $this->synchronizationService = $synchronizationService;
    }

    /*API*/
    public function saveNewMedicationRequest(Request $request)
    {
        try {
            $imagePath2 = storage_path(config('images.public_path') . '/firma.jpg');

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
                        try {
                            Mail::to(trim($mail))->send(new MedicationNotificationMail($payload, $person, $imagePath2));
                        } catch (Exception $e) {
                            Log::error('Error al enviar mail: ' . $e->getMessage());
                            return response()->json(['message' => 'Error, no se pudo enviar el mail. Por favor cargue la solicitud nuevamente'], 400);
                        }

                    }
                    try {
                        Mail::to($user->email)->send(new MedicationNotificationUser($payload, $person, $imagePath2));
                    } catch (Exception $e) {
                        Log::error('Error al enviar mail: ' . $e->getMessage());
                        return response()->json(['message' => 'Error, no se pudo enviar el mail. Por favor cargue la solicitud nuevamente'], 400);
                    }

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






}
