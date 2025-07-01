<?php

namespace App\Services;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Exception;
use DB;
use Illuminate\Support\Facades\Http;

class SynchronizationService
{

    /*GUARDAR LOS DATOS EN AGENDA - BD DE INTRANET*/
    public function saveNewUserInAgenda(array $datosUsuario)
    {
        $urlKey = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/get-key-api';        // Endpoint para obtener la clave
        $urlSave = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/save-user';      // Endpoint para enviar datos encriptados

        try {
            // 1) Solicitar clave efímera
            $responseKey = Http::timeout(30)->post($urlKey);
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
                'dni' => $datosUsuario['dni'],
                'name' => $datosUsuario['name'],
                'email' => $datosUsuario['email'],
                'password' => $datosUsuario['password'],
            ], $key);

            if ($encrypted === false) {
                return false;
            }

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
            Log::error('Excepción al sincronizar con agenda: ' . $e->getMessage());
            return false;
        }
    }


    public function updatePersonWithAgenda(array $persona)
    {
        $urlKey = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/get-key-api';
        $url = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/update-person';

        try {

            $responseKey = Http::timeout(30)->post($urlKey);
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
            Log::error('Excepción al sincronizar persona con agenda: ' . $e->getMessage());
            return false;
        }
    }


    public function updateUserWithAgenda(array $user)
    {
        $urlKey = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/get-key-api';
        $url = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/update-user';

        try {
            $responseKey = Http::timeout(30)->post($urlKey);
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
            Log::error('Excepción al sincronizar usuario con agenda: ' . $e->getMessage());
            return false;
        }
    }

    public function saveNewMedicationRequestInAgenda(array $data)
    {
        $urlKey = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/get-key-api';
        $url = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/save-medication-request';

        try {
            $responseKey = Http::timeout(30)->post($urlKey);
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
            Log::error('Excepción al sincronizar solicitud de medicamentos con agenda: ' . $e->getMessage());
            return false;
        }
    }

    public function saveNewMedicalCertificateInAgenda(array $data)
    {
        $urlKey = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/get-key-api';
        $url = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/save-medical-certificate';

        try {

            $responseKey = Http::timeout(30)->post($urlKey);
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
                'user_id' => $data['user_id'],
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'],
                'archivo' => $data['archivo'],

            ], $key);

            if ($encrypted === false) {
                return false;
            }

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
            Log::error('Excepción al sincronizar certificado con agenda: ' . $e->getMessage());
            return false;
        }
    }


    public function encryptDataToAgenda(array $data, string $key)
    {
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
    }

}
