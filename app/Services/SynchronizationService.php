<?php

namespace App\Services;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\PersonaService;
use App\Services\MedicationsRequestService;
use App\Services\MedicalCertificateService;

class SynchronizationService
{
    private string $baseUrl;
    private string $urlGetKey;
    private array $endpoints;
    private $personaService;
    private $userService;
    private $medicationRequestService;
    private $medicalCertificateService;

    public function __construct(UserService $userService, PersonaService $personaService, MedicationsRequestService $medicationRequestService, MedicalCertificateService $medicalCertificateService)
    {
        $this->userService = $userService;
        $this->personaService = $personaService;
        $this->medicationRequestService = $medicationRequestService;
        $this->medicalCertificateService = $medicalCertificateService;


        $this->baseUrl = config('services.agenda.base_url');
        $this->urlGetKey = $this->baseUrl . '/api/get-key-api';

        $this->endpoints = [
            'save_user' => '/api/save-user',
            'update_user' => '/api/update-user',
            'update_person' => '/api/update-person',
            'save_medication' => '/api/save-medication-request',
            'save_certificate' => '/api/save-medical-certificate',
        ];
    }



    /*GUARDAR LOS DATOS EN AGENDA - BD DE INTRANET*/
    public function saveNewUserInAgenda($registerUser)
    {
        try {

            DB::connection('mysql_write')->beginTransaction();
            $user = $this->userService->createUserApi(
                $registerUser->dni,
                $registerUser->name,
                $registerUser->email,
                $registerUser->password
            );

            $person = $this->personaService->getByDniWrite($registerUser->dni);
            $person->usuario = $user->id;
            $person->save();

            $responseKey = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('EMPRESA_API_TOKEN'),
            ])->post($this->urlGetKey);

            if (!$responseKey->successful()) {
                Log::error('No se pudo obtener la clave efímera: ' . $responseKey->body());
                return false;
            }

            $keyB64 = $responseKey->json('key');
            $key = base64_decode($keyB64);
            if (strlen($key) !== 32) {  // AES-256 requiere 32 bytes
                Log::error('Clave efímera inválida recibida');
                return false;
            }

            $encrypted = $this->encryptDataToAgenda([
                'id' => $user->id,
                'dni' => $user->dni,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
            ], $key);

            if ($encrypted === false) {
                return false;
            }
            $urlSave = $this->baseUrl . $this->endpoints['save_user'];
            $responseSave = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('EMPRESA_API_TOKEN'),
            ])->post($urlSave, [
                        'ciphertext' => $encrypted['ciphertext'],
                        'iv' => $encrypted['iv'],
                    ]);

            Log::info('Response Save: ' . $responseSave->body());
            Log::info('Update person: ' . $this->updatePersonWithAgenda($person->toArray()));
            if ($responseSave->successful() && $this->updatePersonWithAgenda($person->toArray())) {
                DB::connection('mysql_write')->commit();
                return true;
            }

            DB::connection('mysql_write')->rollBack();
            Log::error('Error al sincronizar: ' . $responseSave->body());
            return false;

        } catch (Exception $e) {
            DB::connection('mysql_write')->rollBack();
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error saving a new user to Intranet: ' . $e->getMessage());
            return false;
        }
    }


    public function updatePersonWithAgenda(array $persona)
    {
        try {
            $responseKey = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('EMPRESA_API_TOKEN'),
            ])->post($this->urlGetKey);

            if (!$responseKey->successful()) {
                Log::error('No se pudo obtener la clave efímera: ' . $responseKey->body());
                return false;
            }

            $keyB64 = $responseKey->json('key');
            $key = base64_decode($keyB64);
            if (strlen($key) !== 32) {  // AES-256 requiere 32 bytes
                Log::error('Clave efímera inválida recibida');
                return false;
            }
            Log::info("Persona: " . json_encode($persona));

            $encrypted = $this->encryptDataToAgenda([
                'dni' => $persona['dni'],
                'usuario' => $persona['usuario'],

            ], $key);

            if ($encrypted === false) {
                return false;
            }
            $url = $this->baseUrl . $this->endpoints['update_person'];
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('EMPRESA_API_TOKEN'),
            ])->post($url, [
                        'ciphertext' => $encrypted['ciphertext'],
                        'iv' => $encrypted['iv'],
                    ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Error al sincronizar persona: ' . $response->body());
            return false;

        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error updating person to Intranet: ' . $e->getMessage());
            return response()->json(['message' => 'Error al sincronizar la persona en Intranet.'], 500);
        }
    }


    public function updateUserWithAgenda($dni, $password)
    {
        try {
            DB::connection('mysql_write')->beginTransaction();
            $user = $this->userService->resetPassword($dni, $password);

            $responseKey = Http::timeout(30)->post($this->urlGetKey);
            if (!$responseKey->successful()) {
                Log::error('No se pudo obtener la clave efímera: ' . $responseKey->body());
                return false;
            }

            $keyB64 = $responseKey->json('key');
            $key = base64_decode($keyB64);
            if (strlen($key) !== 32) {  // AES-256 requiere 32 bytes
                Log::error('Clave efímera inválida recibida');
                return false;
            }

            $encrypted = $this->encryptDataToAgenda([
                'dni' => $user->dni,
                'password' => $user->password,

            ], $key);

            if ($encrypted === false) {
                return false;
            }
            $url = $this->baseUrl . $this->endpoints['update_user'];
            $response = Http::timeout(30)->post($url, [
                'ciphertext' => $encrypted['ciphertext'],
                'iv' => $encrypted['iv'],
            ]);

            if ($response->successful()) {
                DB::connection('mysql_write')->commit();
                return $user;
            }
            DB::connection('mysql_write')->rollBack();
            Log::error('Error al sincronizar usuario: ' . $response->body());
            return false;

        } catch (Exception $e) {
            DB::connection('mysql_write')->rollBack();
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error updating user to Intranet: ' . $e->getMessage());
            return response()->json(['message' => 'Error al sincronizar el usuario en Intranet.'], 500);
        }
    }

    public function saveNewMedicationRequestInAgenda($payload)
    {
        try {
            DB::connection('mysql_write')->beginTransaction();
            $create = $this->medicationRequestService->create($payload);
            if (!$create) {
                Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error saving a new medication request to Intranet');
                return false;
            }
            $responseKey = Http::timeout(30)->post($this->urlGetKey);
            if (!$responseKey->successful()) {
                Log::error('No se pudo obtener la clave efímera: ' . $responseKey->body());
                return false;
            }

            $keyB64 = $responseKey->json('key');
            $key = base64_decode($keyB64);
            if (strlen($key) !== 32) {  // AES-256 requiere 32 bytes
                Log::error('Clave efímera inválida recibida');
                return false;
            }

            $encrypted = $this->encryptDataToAgenda([
                'request' => $create['solicitud'],
                'items' => $create['items'],

            ], $key);

            if ($encrypted === false) {
                return false;
            }
            $url = $this->baseUrl . $this->endpoints['save_medication'];
            $response = Http::timeout(30)->post($url, [
                'ciphertext' => $encrypted['ciphertext'],
                'iv' => $encrypted['iv'],
            ]);

            if ($response->successful()) {
                DB::connection('mysql_write')->commit();
                return true;
            }
            DB::connection('mysql_write')->rollBack();
            Log::error('Error al sincronizar solicitud de medicamentos: ' . $response->body());
            return false;

        } catch (Exception $e) {
            DB::connection('mysql_write')->rollBack();
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error saving a new medication request to Intranet: ' . $e->getMessage());

        }
    }

    public function saveNewMedicalCertificateInAgenda(array $data, $fileName, $user)
    {
        try {
            DB::connection('mysql_write')->beginTransaction();
            $certificado = $this->medicalCertificateService->create(
                $user->dni,
                $data['data']['title'],
                $data['data']['description'],
                $fileName
            );

            $responseKey = Http::timeout(30)->post($this->urlGetKey);
            if (!$responseKey->successful()) {
                Log::error('No se pudo obtener la clave efímera: ' . $responseKey->body());
                return null;
            }

            $keyB64 = $responseKey->json('key');
            $key = base64_decode($keyB64);
            if (strlen($key) !== 32) {  // AES-256 requiere 32 bytes
                Log::error('Clave efímera inválida recibida');
                return null;
            }
            $encrypted = $this->encryptDataToAgenda([
                'id' => $certificado->id,
                'user_id' => $user->dni,
                'titulo' => $data['data']['title'],
                'descripcion' => $data['data']['description'],
                'archivo' => $fileName,

            ], $key);

            if ($encrypted === false) {
                return null;
            }
            $url = $this->baseUrl . $this->endpoints['save_certificate'];
            $response = Http::timeout(30)->post($url, [
                'ciphertext' => $encrypted['ciphertext'],
                'iv' => $encrypted['iv'],
            ]);

            if ($response->successful()) {
                DB::connection('mysql_write')->commit();
                return $certificado;
            }
            Log::error('Error al sincronizar certificado: ' . $response->body());
            DB::connection('mysql_write')->rollBack();
            return null;


        } catch (Exception $e) {
            DB::connection('mysql_write')->rollBack();
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error saving a new medical certificate to Intranet: ' . $e->getMessage());
            return null;
        }
    }


    public function encryptDataToAgenda(array $data, string $key)
    {
        try {
            $plaintext = json_encode($data);
            $iv = random_bytes(12); // Tamaño recomendado para GCM
            $tag = '';

            $ciphertext = openssl_encrypt(
                $plaintext,
                'aes-256-gcm',
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );

            if ($ciphertext === false) {
                Log::error('Error al encriptar datos');
                return false;
            }

            return [
                'ciphertext' => base64_encode($ciphertext . $tag),
                'iv' => base64_encode($iv),

            ];
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error encrypting data to Intranet: ' . $e->getMessage());
            return response()->json(['message' => 'Error al encriptar la informacion para Intranet.'], 500);
        }

    }





}
