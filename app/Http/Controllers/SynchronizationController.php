<?php

namespace App\Http\Controllers;
use App\Models\RegistroUser;
use App\User;
use App\Services\UserService;
use App\Services\EncryptService;
use Carbon\Carbon;
use App\Services\PersonaService;
use App\Services\SynchronizationService;
use DB;
use Exception;
use Log;
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

    public function __construct(UserService $userService, EncryptService $encryptService, PersonaService $personService, SynchronizationService $synchronizationService)
    {

        $this->userService = $userService;
        $this->encryptService = $encryptService;
        $this->personService = $personService;
        $this->synchronizationService = $synchronizationService;
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

            $empleado = new Empleado();
            $empleado->nombre_p = $data['nombre_p'];
            $empleado->apellido = $data['apellido'];
            $empleado->dni = $data['dni'];
            $empleado->interno = $data['interno'];
            $empleado->correo = $data['correo'];
            $empleado->fe_nac = $data['fe_nac'];
            $empleado->fe_ing = $data['fe_ing'];
            $empleado->area = $data['area'];
            $empleado->turno = $data['turno'];
            $empleado->activo = $data['activo'];
            $empleado->jefe = $data['jefe'];
            $empleado->usuario = null;
            $empleado->legajo = $data['legajo'];
            $empleado->save();

            return response()->json(['message' => 'Empleado sincronizado con éxito'], 201);
        } catch (Exception $e) {
            Log::error('Error al guardar persona sincronizada: ' . $e->getMessage());
            return response()->json(['error' => 'Error al sincronizar'], 500);
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

            // Actualizar los campos
            $employee->dni = $data['dni'];
            $employee->nombre_p = $data['nombre_p'];
            $employee->apellido = $data['apellido'];
            $employee->interno = $data['interno'];
            $employee->correo = $data['correo'];
            $employee->fe_nac = $data['fe_nac'];
            $employee->fe_ing = $data['fe_ing'];
            $employee->area = $data['area'];
            $employee->turno = $data['turno'];
            $employee->activo = $data['activo'];
            $employee->jefe = $data['jefe'];
            $employee->legajo = $data['legajo'];
            $employee->save();

            return response()->json(['message' => 'Empleado actualizado con éxito'], 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar persona sincronizada: ' . $e->getMessage());
            return response()->json(['error' => 'Error al sincronizar'], 500);
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

            $requestModel = MedicationRequest::on('mysql_write')->find($data['id']);
            if (!$requestModel) {
                return response()->json(['error' => 'Solicitud no encontrada'], 404);
            }

            $requestModel->update([
                'dni_persona' => $data['dni_persona'],
                'estado' => $data['estado'],
            ]);

            foreach ($items as $itemData) {
                if (isset($itemData['id'])) {
                    DB::connection('mysql_write')->table('items_medicamentos')
                        ->where('id', $itemData['id'])
                        ->where('id_solicitud', $requestModel->id)
                        ->update([
                            'aprobado' => $itemData['aprobado'],
                            'medicamento' => $itemData['medicamento'],
                            'cantidad_solicitada' => $itemData['cantidad_solicitada'],
                            'cantidad_aprobada' => $itemData['cantidad_aprobada'],
                            'lote_med' => $itemData['lote_med'],
                            'vencimiento_med' => $itemData['vencimiento_med'],
                        ]);
                }
            }

            return response()->json(['message' => 'Solicitud actualizada con éxito'], 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar solicitud de medicación: ' . $e->getMessage());
            return response()->json(['error' => 'Error al sincronizar'], 500);
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
            Log::error('Error al desactivar a la persona: ' . $e->getMessage());
            return response()->json(['error' => 'Error al sincronizar'], 500);
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
            Log::error('Error al sincronizar la contraseña: ' . $e->getMessage());
            return response()->json(['error' => 'Error al sincronizar'], 500);
        }
    }

    /*----------------------------------------------------------------------------------------------*/


    public function getKey(Request $request)
    {
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
            Log::error('Excepción al desencriptar datos: ' . $e->getMessage());
            return null;
        }
    }




}
