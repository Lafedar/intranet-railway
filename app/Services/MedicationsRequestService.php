<?php

namespace App\Services;

use App\Models\Location;
use Exception;
use Log;
use Illuminate\Database\Eloquent\Collection;
use DB;

class MedicationsRequestService
{

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

    public function validateApprovedItems($id)
    {
        try {
            $exists = DB::connection('mysql_read')->table('items_medicamentos')
                ->where('id_solicitud', $id)
                ->where('aprobado', 1)
                ->exists();

            return $exists;
        }catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error validating approved items ' . $e->getMessage());
            return false;
        }

    }

    public function create($data)
    {
        try {
            $idSolicitud = DB::connection('mysql_write')->table('solicitudes_medicamentos')->insertGetId([
                'dni_persona' => $data['dni_user'],
                'estado' => 'Aprobación Pendiente',
            ]);

            $items = [];

            foreach ($data as $key => $value) {
                if (str_starts_with($key, 'medication')) {
                    $index = str_replace('medication', '', $key);
                    $amountKey = 'amount' . $index;

                    if (!empty($value) && isset($data[$amountKey]) && $data[$amountKey] !== '') {
                        $items[] = [
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
                    }
                }
            }

            if (!empty($items)) {
                DB::connection('mysql_write')->table('items_medicamentos')->insert($items);
            }

            return true;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error creating medication request ' . $e->getMessage());
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


}
