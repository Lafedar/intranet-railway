<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Permiso;
use App\Empleado;
use App\User;
use Auth;
use Session;
use App\Models\MiAgenda;
use App\Empresa;
use Illuminate\Routing\Controller;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Services\PersonaService;

class PersonaController extends Controller
{
    private $personaService;
    public function __construct(PersonaService $personaService)
    {

        $this->personaService = $personaService;

    }

    public function buscar(Request $request)
    {
        try {

            $ciphertextBase64 = $request->input('ciphertext');
            $ivBase64 = $request->input('iv');

            if (!$ciphertextBase64 || !$ivBase64) {
                return response()->json(['message' => 'Faltan datos encriptados'], 400);
            }

            $ciphertext = base64_decode($ciphertextBase64);
            $iv = base64_decode($ivBase64);

            $aesKeyBase64 = $request->session()->get('aes_key');
            if (!$aesKeyBase64) {
                return response()->json(['message' => 'Clave AES no encontrada en la sesión'], 400);
            }
            $aesKey = base64_decode($aesKeyBase64);

            $tagLength = 16;
            if (strlen($ciphertext) < $tagLength) {
                return response()->json(['message' => 'Datos encriptados inválidos'], 400);
            }

            $tag = substr($ciphertext, -$tagLength);
            $ciphertextRaw = substr($ciphertext, 0, -$tagLength);

            $decrypted = openssl_decrypt(
                $ciphertextRaw,
                'aes-256-gcm',
                $aesKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );


            if ($decrypted === false) {
                return response()->json(['message' => 'Error al desencriptar'], 400);
            }

            $data = json_decode($decrypted, true);

            $dni = $data['data']['dni'];

            $persona = $this->personaService->getByDni($dni);

            if (!$persona) {
                return response()->json(['message' => 'Persona no encontrada'], 404);
            }

            // Crear y cifrar respuesta
            $responseData = [
                'nombre_p' => $persona->nombre_p,
                'apellido' => $persona->apellido,
                'activo' => $persona->activo,
            ];

            $jsonResponse = json_encode($responseData);

            $newIv = random_bytes(12);
            $tagOut = '';
            $ciphertextOut = openssl_encrypt(
                $jsonResponse,
                'aes-256-gcm',
                $aesKey,
                OPENSSL_RAW_DATA,
                $newIv,
                $tagOut
            );

            return response()->json([
                'ciphertext' => base64_encode($ciphertextOut . $tagOut),
                'iv' => base64_encode($newIv),
            ], 200);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error searching a person: ' . $e->getMessage());
            return response()->json(['error' => 'Error buscando la persona'], 500);
        }

    }



}
