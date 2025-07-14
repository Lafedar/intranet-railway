<?php

namespace App\Http\Controllers;
use App\Models\RegistroUser;
use App\User;
use App\Services\UserService;
use App\Services\EncryptService;
use Carbon\Carbon;
use App\Services\PersonaService;
use App\Services\SynchronizationService;
use App\Services\MedicationsRequestService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\MedicationRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
class SynchronizationController extends Controller
{
    protected $userService;
    protected $encryptService;
    protected $personService;
    protected $synchronizationService;

    protected $medicationRequestService;

    public function __construct(UserService $userService, EncryptService $encryptService, PersonaService $personService, SynchronizationService $synchronizationService, MedicationsRequestService $medicationRequestService)
    {

        $this->userService = $userService;
        $this->encryptService = $encryptService;
        $this->personService = $personService;
        $this->synchronizationService = $synchronizationService;
        $this->medicationRequestService = $medicationRequestService;
    }

    /*RECIBIR DATOS DESDE INTRANET*/
    public function createPersonFromIntranet(Request $request)
    {
        try {
            $validated = $request->validate([
                'ciphertext' => 'required|string',
                'iv' => 'required|string',
            ]);

            $data = $this->decryptFromAgenda(
                $validated['ciphertext'],
                $validated['iv']
            );

            if (!$data) {
                return response()->json(['message' => 'No se pudieron desencriptar los datos.'], 400);
            }

            $person = $this->personService->createFromIntranetData($data);
            if (!$person) {
                return response()->json(['message' => 'Error al guardar el empleado.'], 500);
            }
            return response()->json(['message' => 'Empleado sincronizado con éxito'], 201);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error creating a new person from Intranet' . $e->getMessage());
            return response()->json(['message' => 'Error al sincronizar la persona desde Intranet.'], 500);
        }
    }


    public function updatePersonFromIntranet(Request $request)
    {
        try {
            
            $validated = $request->validate([
                'ciphertext' => 'required|string',
                'iv' => 'required|string',
            ]);
            

            $data = $this->decryptFromAgenda(
                $validated['ciphertext'],
                $validated['iv']
            );

            if (!$data) {
                return response()->json(['message' => 'No se pudieron desencriptar los datos.'], 400);
            }

            $employee = $this->personService->getByIdWrite($data['id_p']);
            if (!$employee) {
                return response()->json(['error' => 'Empleado no encontrado'], 404);
            }
            if (!$this->personService->updateFromIntranetData($employee, $data)) {
                return response()->json(['error' => 'Error al actualizar al empleado desde Intranet'], 404);
            }
            $this->userService->updateUser($employee->dni, $employee->activo);

            return response()->json(['message' => 'Empleado actualizado con éxito'], 200);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating person from Intranet' . $e->getMessage());
            return response()->json(['message' => 'Error al sincronizar la persona desde Intranet.'], 500);
        }
    }

    public function updateMedicationRequestFromIntranet(Request $request)
    {
        try {
            $validated = $request->validate([
                'ciphertext' => 'required|string',
                'iv' => 'required|string',
            ]);

            $data = $this->decryptFromAgenda(
                $validated['ciphertext'],
                $validated['iv']
            );

            if (!$data) {
                return response()->json(['message' => 'No se pudieron desencriptar los datos.'], 400);
            }

            $items = $data['items'] ?? [];

            if (!isset($data['id'])) {
                return response()->json(['error' => 'Falta el ID de la solicitud'], 400);
            }
            
            $requestModel = $this->medicationRequestService->getRequestByIdWrite($data['id']);
            if (!$requestModel) {
                return response()->json(['error' => 'Solicitud no encontrada'], 404);
            }

            if (!$this->medicationRequestService->update($requestModel, $data)) {
                return response()->json(['error' => 'Error al actualizar la solicitud de medicamentos desde Intranet'], 404);
            }

            if (!$this->medicationRequestService->updateItemsMedicamentos($items, $requestModel->id)) {
                return response()->json(['error' => 'Error al actualizar los items de la solicitud de medicamentos desde Intranet'], 404);
            }

            return response()->json(['message' => 'Solicitud actualizada con éxito'], 200);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating a medication request from Intranet' . $e->getMessage());
            return response()->json(['message' => 'Error al sincronizar la solicitud de medicamentos desde Intranet.'], 500);
        }
    }


    public function destroyPerson(Request $request)
    {
        try {
            $validated = $request->validate([
                'ciphertext' => 'required|string',
                'iv' => 'required|string',
            ]);

            $data = $this->decryptFromAgenda(
                $validated['ciphertext'],
                $validated['iv']
            );

            if (!$data) {
                return response()->json(['message' => 'No se pudieron desencriptar los datos.'], 400);
            }

            $person = $this->personService->getByDniWrite($data['dni']);

            if (!$person) {
                return response()->json(['error' => 'Empleado no encontrado'], 404);
            }
            $person->activo = 0;
            $person->save();

            $user = $this->userService->getByDniWrite($data['dni']);

            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
            $user->activo = 0;
            $user->save();

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating the person and user from Intranet' . $e->getMessage());
            return response()->json(['message' => 'Error al sincronizar la persona y el usuario desde Intranet.'], 500);
        }

    }

    public function syncPasswordFromIntranet(Request $request)
    {
        try {
            $validated = $request->validate([
                'ciphertext' => 'required|string',
                'iv' => 'required|string',
            ]);

            $data = $this->decryptFromAgenda(
                $validated['ciphertext'],
                $validated['iv']
            );

            if (!$data) {
                return response()->json(['message' => 'No se pudieron desencriptar los datos.'], 400);
            }
            $user = $this->userService->getByDniWrite($data['dni']);
            $registerUser = $this->userService->getRegisterUserByDni($data['dni']);

            if ($user) {
                if (!empty($data['password'])) {
                    $user->password = $data['password'];
                    $user->save();
                    if ($registerUser) {
                        $registerUser->password = $data['password'];
                        $registerUser->save();
                    }
                    return response()->json(['message' => 'Contraseña sincronizada correctamente']);
                } else {
                    return response()->json(['error' => 'La password es null'], 500);
                }
            } else {
                return response()->json(['error' => 'El usuario no existe'], 500);
            }

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating a user password from Intranet' . $e->getMessage());
            return response()->json(['message' => 'Error al sincronizar la contraseña del usuario desde Intranet.'], 500);
        }
    }

    /*----------------------------------------------------------------------------------------------*/


    public function getKey(Request $request)
    {
        try {
            // Clave cacheada para que sea efímera y se use durante un tiempo (ej: 5 minutos)
            $cacheKey = 'efimera_key';

            // Intentamos obtenerla del cache
            $key = Cache::get($cacheKey);

            if (!$key) {
                // Generamos una nueva clave aleatoria de 32 bytes (AES-256)
                $key = base64_encode(random_bytes(32));

                // Guardamos la clave en cache con TTL (ej 5 minutos)
                Cache::put($cacheKey, $key, now()->addMinutes(5));
            }

            // Devolvemos la clave en formato JSON
            return response()->json(['key' => $key]);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting a epimeral key' . $e->getMessage());
            return response()->json(['message' => 'Error al obtener la clave para desencriptar.'], 500);
        }

    }
    public function decryptFromAgenda(string $ciphertextB64, string $ivB64): array|null
    {
        try {
            $ciphertextWithTag = base64_decode($ciphertextB64);
            $iv = base64_decode($ivB64);

            // Obtener clave efímera desde caché
            $keyB64 = Cache::get('efimera_key');
            if (!$keyB64) {
                Log::error('Clave efímera no disponible en caché.');
                return null;
            }

            $key = base64_decode($keyB64);

            // Separar tag y mensaje
            $tagLength = 16;
            $tag = substr($ciphertextWithTag, -$tagLength);
            $ciphertext = substr($ciphertextWithTag, 0, -$tagLength);

            // Desencriptar
            $plaintext = openssl_decrypt(
                $ciphertext,
                'aes-256-gcm',
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );

            if ($plaintext === false) {
                Log::error('Fallo al desencriptar los datos.');
                return null;
            }

            $data = json_decode($plaintext, true);
            if (!is_array($data)) {
                Log::error('El JSON desencriptado no es válido.');
                return null;
            }

            return $data;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error desencrypting data from Intranet' . $e->getMessage());
            return response()->json(['message' => 'Error al desecriptar los datos desde Intranet.'], 500);
        }
    }




}
