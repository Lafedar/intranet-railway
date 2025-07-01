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
        $url = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/save-user';

        try {
            $response = Http::timeout(20)->post($url, [
                'dni' => $datosUsuario['dni'],
                'name' => $datosUsuario['name'],
                'email' => $datosUsuario['email'],
                'password' => $datosUsuario['password'],
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Error al sincronizar: ' . $response->body());
            return false;

        } catch (Exception $e) {
            Log::error('Excepción al sincronizar con agenda: ' . $e->getMessage());
            return false;
        }
    }

    public function updatePersonWithAgenda(array $persona)
    {
        $url = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/update-person';

        try {
            $response = Http::timeout(20)->post($url, $persona);

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
        $url = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/update-user';

        try {
            $response = Http::timeout(20)->post($url, $user);

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
        $url = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/save-medication-request';

        try {
            $response = Http::timeout(20)->post($url, $data);

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
        $url = 'https://songs-alexandria-won-lawyer.trycloudflare.com/api/save-medical-certificate';

        try {
            $response = Http::timeout(20)->post($url, $data);

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

}
