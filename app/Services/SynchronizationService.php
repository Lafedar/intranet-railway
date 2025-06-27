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

    public function saveNewUserInAgenda(array $datosUsuario)
    {
        $url = 'https://qt-hose-alloy-gym.trycloudflare.com/api/save-user';

        try {
            $response = Http::post($url, [
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
        $url = 'https://qt-hose-alloy-gym.trycloudflare.com/api/update-person';

        try {
            $response = Http::post($url, $persona);

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
        $url = 'https://qt-hose-alloy-gym.trycloudflare.com/api/update-user';

        try {
            $response = Http::post($url, $user);

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
}
