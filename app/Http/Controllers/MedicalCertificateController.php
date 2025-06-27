<?php

namespace App\Http\Controllers;
use App\Models\RegistroUser;
use App\User;
use App\Services\UserService;
use App\Services\EncryptService;
use Carbon\Carbon;
use App\Services\PersonaService;
use Illuminate\Http\Request;
use App\Services\MedicalCertificateService;
use Illuminate\Support\Facades\Mail;
use App\Mail\MedicalCertificateMail;
use Illuminate\Support\Facades\Log;
use App\Services\GeneralParametersService;
use App\Mail\MedicalCertificateUser;
use App\Services\SynchronizationService;

class MedicalCertificateController extends Controller
{
    protected $userService;
    protected $encryptService;
    protected $personService;
    protected $medicalCertificateService;
    protected $genParametersService;

    protected $synchronizationService;

    public function __construct(UserService $userService, EncryptService $encryptService, PersonaService $personService, MedicalCertificateService $medicalCertificateService, GeneralParametersService $genParametersService, SynchronizationService $synchronizationService)
    {

        $this->userService = $userService;
        $this->encryptService = $encryptService;
        $this->personService = $personService;
        $this->medicalCertificateService = $medicalCertificateService;
        $this->genParametersService = $genParametersService;
        $this->synchronizationService = $synchronizationService;
    }


    public function store(Request $request)
    {
        try {
            $imagePath2 = storage_path('app/public/images/firma.jpg');
            $payload = $request->all();

            $decryptedDatos = $this->encryptService->decryptFile($payload['datos'] ?? []);

            if (!$decryptedDatos) {
                return response()->json(['error' => 'Datos inválidos'], 400);
            }

            $data = json_decode($decryptedDatos, true);

            $user = $this->userService->getByDni($data['data']['user_dni']);

            $decryptedFile = $this->encryptService->decryptFile($payload['archivo'] ?? []);

            if (!$decryptedFile) {
                return response()->json(['error' => 'Archivo inválido'], 400);
            }

            $fileName = $payload['archivo']['originalName'] ?? 'archivo.pdf';
            $mimeType = $payload['archivo']['type'] ?? 'application/octet-stream';

            $certificado = $this->medicalCertificateService->create(
                $user->id,
                $data['data']['title'],
                $data['data']['description'],
                $fileName
            );

            if (is_object($certificado)) {
                $this->synchronizationService->saveNewMedicalCertificateInAgenda($certificado->toArray());
                $emailsRaw = $this->genParametersService->getMailsToMedicationRequests();

                if (!empty(trim($emailsRaw))) {
                    $emails = array_filter(
                        array_map('trim', explode(',', $emailsRaw)),
                        fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL)
                    );

                    foreach ($emails as $email) {
                        try {
                            Mail::to($email)->send(
                                new MedicalCertificateMail($user, $certificado, $decryptedFile, $mimeType, $fileName, $imagePath2)
                            );
                        } catch (\Exception $e) {
                            Log::error('Error al enviar mail: ' . $e->getMessage());
                            return response()->json(['message' => 'Error, no se pudo enviar el mail. Por favor cargue el certificado nuevamente'], 400);
                        }

                    }
                } else {
                    return response()->json(['message' => 'Certificado cargado con éxito. Mail NO enviado a RRHH'], 200);
                }


                try {
                    Mail::to($user->email)->send(
                        new MedicalCertificateUser($user, $certificado, $imagePath2)
                    );
                } catch (\Exception $e) {
                    Log::error('Error al enviar mail: ' . $e->getMessage());
                    return response()->json(['message' => 'Error, no se pudo enviar el mail. Por favor cargue el certificado nuevamente'], 400);
                }


                return response()->json(['message' => 'Certificado cargado con éxito! Se enviará un email a RRHH'], 200);
            } else {
                return response()->json(['error' => 'Error al guardar el certificado'], 500);
            }


        } catch (\Throwable $e) {
            Log::error('Error al guardar certificado: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

}
