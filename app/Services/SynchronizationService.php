<?php

namespace App\Services;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Exception;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class SynchronizationService
{
    private string $baseUrl;
    private string $urlGetKey;
    private array $endpoints;

    public function __construct()
    {
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
    public function saveNewUserInAgenda(array $datosUsuario)
    {
        try {
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
                'id' => $datosUsuario['id'],
                'dni' => $datosUsuario['dni'],
                'name' => $datosUsuario['name'],
                'email' => $datosUsuario['email'],
                'password' => $datosUsuario['password'],
            ], $key);

            if ($encrypted === false) {
                return false;
            }
            $urlSave = $this->baseUrl . $this->endpoints['save_user'];
            $responseSave = Http::timeout(30)->post($urlSave, [
                'ciphertext' => $encrypted['ciphertext'],
                'iv' => $encrypted['iv'],
            ]);

            if ($responseSave->successful()) {
                return true;
            }

            Log::error('Error al sincronizar: ' . $responseSave->body());
            return false;

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error saving a new user to Intranet' . $e->getMessage());
            return response()->json(['message' => 'Error al sincronizar el nuevo usuario en Intranet.'], 500);
        }
    }


    public function updatePersonWithAgenda(array $persona)
    {
        try {
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
            Log::info("Persona: " . json_encode($persona));

            $encrypted = $this->encryptDataToAgenda([
                'dni' => $persona['dni'],
                'usuario' => $persona['usuario'],

            ], $key);

            if ($encrypted === false) {
                return false;
            }
            $url = $this->baseUrl . $this->endpoints['update_person'];
            $response = Http::timeout(30)->post($url, [
                'ciphertext' => $encrypted['ciphertext'],
                'iv' => $encrypted['iv'],
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Error al sincronizar persona: ' . $response->body());
            return false;

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating person to Intranet' . $e->getMessage());
            return response()->json(['message' => 'Error al sincronizar la persona en Intranet.'], 500);
        }
    }


    public function updateUserWithAgenda(array $user)
    {
        try {
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
                'dni' => $user['dni'],
                'password' => $user['password'],

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
                return true;
            }
            Log::error('Error al sincronizar usuario: ' . $response->body());
            return false;

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating user to Intranet' . $e->getMessage());
            return response()->json(['message' => 'Error al sincronizar el usuario en Intranet.'], 500);
        }
    }

    public function saveNewMedicationRequestInAgenda(array $data)
    {
        try {
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
                'request' => $data['request'],
                'items' => $data['items'],

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
                return true;
            }

            Log::error('Error al sincronizar solicitud de medicamentos: ' . $response->body());
            return false;

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error saving a new medication request to Intranet' . $e->getMessage());
            return response()->json(['message' => 'Error al sincronizar la nueva solicitud de medicamentos en Intranet.'], 500);
        }
    }

    public function saveNewMedicalCertificateInAgenda(array $data)
    {
        try {
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
                'id' => $data['id'],
                'user_id' => $data['user_id'],
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'],
                'archivo' => $data['archivo'],

            ], $key);

            if ($encrypted === false) {
                return false;
            }
            $url = $this->baseUrl . $this->endpoints['save_certificate'];
            $response = Http::timeout(30)->post($url, [
                'ciphertext' => $encrypted['ciphertext'],
                'iv' => $encrypted['iv'],
            ]);

            if ($response->successful()) {
                return true;
            }
            Log::error('Error al sincronizar certificado: ' . $response->body());
            return false;

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error saving a new medical certificate to Intranet' . $e->getMessage());
            return false;
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
            Log::error('Error in class: ' . get_class($this) . ' .Error encrypting data to Intranet' . $e->getMessage());
            return response()->json(['message' => 'Error al encriptar la informacion para Intranet.'], 500);
        }

    }





}
