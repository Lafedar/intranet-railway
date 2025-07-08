<?php

namespace App\Services;

use App\Models\Location;
use Exception;
use Log;
use Illuminate\Database\Eloquent\Collection;
use DB;
use App\Services\SynchronizationService;

class MedicationsRequestService
{
    protected $synchronizationService;

    public function __construct(SynchronizationService $synchronizationService)
    {
        $this->synchronizationService = $synchronizationService;
    }

    public function getAll()
    {
        try {
            return DB::connection('mysql_read')->table('solicitudes_medicamentos')
                ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting all medications requests ' . $e->getMessage());
            return null;
        }
    }

    public function getRequestsByDni($dni)
    {
        try {
            return DB::connection('mysql_read')->table('solicitudes_medicamentos')->where('dni_persona', $dni)
                ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting medications requests by dni ' . $e->getMessage());
            return null;
        }
    }

    public function getRequestById($id)
    {
        try {
            return DB::connection('mysql_read')->table('solicitudes_medicamentos')->where('id', $id)->first();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting medication request by id ' . $e->getMessage());
            return null;
        }
    }

    public function getRequestByIdWrite($id)
    {
        try {
            return DB::connection('mysql_write')->table('solicitudes_medicamentos')->where('id', $id)->first();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting medication request by id (write)' . $e->getMessage());
            return null;
        }
    }

    public function validateApprovedItems($id)
    {
        try {
            $exists = DB::connection('mysql_read')->table('items_medicamentos')
                ->where('id_solicitud', $id)
                ->where('aprobado', 1)
                ->exists();

            return $exists;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error validating approved items ' . $e->getMessage());
            return false;
        }

    }

    public function create($data)
    {
        try {
            // Crear solicitud
            $idSolicitud = DB::connection('mysql_write')->table('solicitudes_medicamentos')->insertGetId([
                'dni_persona' => $data['dni_user'],
                'estado' => 'Aprobación Pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $items = [];

            // Crear ítems individualmente
            foreach ($data as $key => $value) {
                if (str_starts_with($key, 'medication')) {
                    $index = str_replace('medication', '', $key);
                    $amountKey = 'amount' . $index;

                    if (!empty($value) && isset($data[$amountKey]) && $data[$amountKey] !== '') {
                        $item = [
                            'id_solicitud' => $idSolicitud,
                            'medicamento' => $value,
                            'cantidad_solicitada' => (int) $data[$amountKey],
                            'cantidad_aprobada' => (int) $data[$amountKey],
                            'aprobado' => 0,
                            'lote_med' => null,
                            'vencimiento_med' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        // Insertar y obtener el ID real
                        $idItem = DB::connection('mysql_write')->table('items_medicamentos')->insertGetId($item);

                        // Agregar a lista con el ID incluido
                        $items[] = array_merge(['id' => $idItem], $item);
                    }
                }
            }

            // Datos de la solicitud para sincronizar
            $solicitudData = [
                'id' => $idSolicitud,
                'dni_persona' => $data['dni_user'],
                'estado' => 'Aprobación Pendiente',
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ];


            return [
                'id_solicitud' => $idSolicitud,
                'solicitud' => $solicitudData,
                'items' => $items,
            ];


        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' - Error creating medication request: ' . $e->getMessage());
            return false;
        }
    }



    public function getItemsForMultipleRequests($ids)
    {
        try {
            if (empty($ids)) {
                return collect();
            }

            return DB::connection('mysql_read')->table('items_medicamentos')
                ->whereIn('id_solicitud', $ids)
                ->get();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . 'Error getting items of multiple requests ids ' . $e->getMessage());
            return null;
        }

    }

    public function getAllItemsByMedicationRequestId($id)
    {
        try {
            return DB::connection('mysql_read')->table('items_medicamentos')
                ->where('id_solicitud', $id)
                ->get();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . 'Error getting all items by your request id ' . $e->getMessage());
            return null;
        }
    }

    public function getItemByMedicationRequestId($id)
    {
        try {
            return DB::connection('mysql_read')->table('items_medicamentos')->where('id', $id)->first();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting item by id ' . $e->getMessage());
            return null;
        }
    }

    public function getAllMedicationRequestAndItemsByUserDni($dni)
    {
        try {
            $requests = DB::connection('mysql_read')->table('solicitudes_medicamentos')
                ->where('dni_persona', $dni)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($requests->isEmpty()) {
                return null;
            }

            $result = [];

            foreach ($requests as $request) {
                $items = $this->getAllItemsByMedicationRequestId($request->id);

                $result[] = [
                    'request' => json_decode(json_encode($request), true),
                    'items' => $items
                ];
            }

            return $result;
        } catch (Exception $e) {
            Log::error('Error in ' . get_class($this) . 'Error getting medications requests and their items' . $e->getMessage());
            return null;
        }
    }
    public function getItemById($id)
    {
        try {
            return DB::connection('mysql_read')->table('items_medicamentos')->where('id', $id)->get();
        } catch (Exception $e) {
            Log::error('Error in ' . get_class($this) . 'Error getting the item by id: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteLastRecord($id_request)
    {
        try {
            if ($id_request != null) {
                DB::connection('mysql_write')
                    ->table('items_medicamentos')
                    ->where('id_solicitud', $id_request)
                    ->delete();



                DB::connection('mysql_write')
                    ->table('solicitudes_medicamentos')
                    ->where('id', $id_request)
                    ->delete();

                return true;
            } else {
                return false;
            }



        } catch (Exception $e) {
            Log::error('Error in ' . get_class($this) . 'Error deleting the last medication request: ' . $e->getMessage());
            return false;
        }
    }

    public function update($medicationRequest, $data)
    {
        try {
            if ($data != null) {
                DB::connection('mysql_write')->table('solicitudes_medicamentos')->where('id', $medicationRequest->id)->update([
                    'dni_persona' => $data['dni_persona'],
                    'estado' => $data['estado'],
                ]);
                return true;
            }
            return false;

        } catch (Exception $e) {
            Log::error('Error in ' . get_class($this) . 'Error updating a medication request: ' . $e->getMessage());
            return false;
        }
    }
    public function updateItemsMedicamentos(array $items, int $idSolicitud)
    {
        foreach ($items as $itemData) {
            if (isset($itemData['id'])) {
                try {
                    DB::connection('mysql_write')->table('items_medicamentos')
                        ->where('id', $itemData['id'])
                        ->where('id_solicitud', $idSolicitud)
                        ->update([
                            'aprobado' => $itemData['aprobado'],
                            'medicamento' => $itemData['medicamento'],
                            'cantidad_solicitada' => $itemData['cantidad_solicitada'],
                            'cantidad_aprobada' => $itemData['cantidad_aprobada'],
                            'lote_med' => $itemData['lote_med'],
                            'vencimiento_med' => $itemData['vencimiento_med'],
                        ]);

                    return true;
                } catch (Exception $e) {
                    Log::error('Error in ' . get_class($this) . 'Error updating a medication request items: ' . $e->getMessage());
                    return false;
                }
            }
        }
    }


}
