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


    public function createPerson(Request $request)
    {
        try {
            $data = $request->input('person');

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


    public function updatePerson(Request $request)
    {
        try {
            $data = $request->input('person');

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

    public function updateMedicationRequest(Request $request)
    {
        try {
            $data = $request->input('request');
            $items = $request->input('items', []);

            if (!isset($data['id'])) {
                return response()->json(['error' => 'Falta el ID de la solicitud'], 400);
            }

            $requestModel = MedicationRequest::find($data['id']);
            if (!$requestModel) {
                return response()->json(['error' => 'Solicitud no encontrada'], 404);
            }

            $requestModel->update([
                'dni_persona' => $data['dni_persona'] ?? $requestModel->dni_persona,
                'estado' => $data['estado'] ?? $requestModel->estado,
                'updated_at' => now(),
            ]);
            
            foreach ($items as $itemData) {
                if (isset($itemData['id'])) {
                    DB::table('items_medicamentos')
                        ->where('id', $itemData['id'])
                        ->where('id_solicitud', $requestModel->id)
                        ->update([
                            'medicamento' => $itemData['medicamento'],
                            'cantidad_solicitada' => $itemData['cantidad_solicitada'] ?? null,
                            'cantidad_aprobada' => $itemData['cantidad_aprobada'] ?? null,
                            'aprobado' => $itemData['aprobado'] ?? null,
                            'lote_med' => $itemData['lote_med'] ?? null,
                            'vencimiento_med' => $itemData['vencimiento_med'] ?? null,
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
            $personPayload = $request->input('person');
            
            $person = $this->personService->getByDniWrite($personPayload['dni']);
            
            if (!$person) {
                return response()->json(['error' => 'Empleado no encontrado'], 404);
            }
            $person->activo = 0;
            $person->save();

            $user = $this->userService->getByDniWrite($personPayload['dni']);
            
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




}
