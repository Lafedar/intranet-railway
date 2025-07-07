<?php

namespace App\Http\Controllers;
use App\Services\UserService;
use App\Services\EncryptService;
use App\Services\PersonaService;
use Illuminate\Http\Request;
use App\Services\MedicalCertificateService;
use Illuminate\Support\Facades\Mail;
use App\Mail\MedicalCertificateMail;
use Illuminate\Support\Facades\Log;
use App\Services\GeneralParametersService;
use App\Mail\MedicalCertificateUser;
use App\Services\SynchronizationService;
use Exception;

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
            $imagePath2 = storage_path(str_replace('storage/', '', config('images.public_path')) . '/firma.jpg');
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

            $allowedExtensions = ['pdf', 'jpg', 'jpeg'];
            $allowedMimeTypes = ['application/pdf', 'image/jpeg'];

            // Validar extensión
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedExtensions)) {
                return response()->json(['error' => 'Extensión de archivo no permitida'], 400);
            }

            // Validar tipo MIME
            if (!in_array($mimeType, $allowedMimeTypes)) {
                return response()->json(['error' => 'Tipo MIME de archivo no permitido'], 400);
            }
            // Validar tamaño (máx. 10 MB)
            $maxSizeInBytes = 10 * 1024 * 1024; // 10 MB

            if (strlen($decryptedFile) > $maxSizeInBytes) {
                return response()->json(['error' => 'El archivo supera el tamaño máximo permitido de 10 MB.'], 400);
            }

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
                        } catch (Exception $e) {
                            Log::error('Error in class: ' . get_class($this) . ' .Error sending an email to RRHH: ' . $e->getMessage());
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
                } catch (Exception $e) {
                    Log::error('Error in class: ' . get_class($this) . ' .Error sending an email to a user: ' . $e->getMessage());
                    return response()->json(['message' => 'Error, no se pudo enviar el mail. Por favor cargue el certificado nuevamente'], 400);
                }


                return response()->json(['message' => 'Certificado cargado con éxito! Se enviará un email a RRHH'], 200);
            } else {
                return response()->json(['error' => 'Error al guardar el certificado'], 500);
            }


        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error saving medical certificate to Intranet' . $e->getMessage());
            return response()->json(['message' => 'Error al sincronizar el usuario en Intranet.'], 500);
        }
    }

}
