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
            return DB::table('solicitudes_medicamentos')->
                orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting all medications requests ' . $e->getMessage());
            return null;
        }
    }

    public function getRequestsByDni($dni)
    {
        try {
            return DB::table('solicitudes_medicamentos')->where('dni_persona', $dni)
                ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting medications requests by dni ' . $e->getMessage());
            return null;
        }
    }

    public function getRequestById($id)
    {
        try {
            return DB::table('solicitudes_medicamentos')->where('id', $id)->first();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting medication request by id ' . $e->getMessage());
            return null;
        }
    }

    public function deleteRequestById($id)
    {
        try {
            return DB::table('solicitudes_medicamentos')->where('id', $id)->update(['estado' => 'Aprobación Pendiente']);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error deletting medications requests by id ' . $e->getMessage());
            return null;
        }
    }

    public function approveRequestById($id, $approved1, $approved2, $approved3)
    {
        try {

            DB::table('solicitudes_medicamentos')
                ->where('id', $id)
                ->update([
                    'estado' => 'Aprobada',
                    'aprobado1' => $approved1,
                    'aprobado2' => $approved2,
                    'aprobado3' => $approved3,
                ]);

            return true;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error approving medications requests by id ' . $e->getMessage());
            return false;
        }
    }


    public function updateMedicationRequestById($id, $validateData)
    {
        try {

            return DB::table('solicitudes_medicamentos')
                ->where('id', $id)
                ->update([
                    'medicamento1' => $validateData['medication1'],
                    'cantidad1' => $validateData['amount1'],
                    'aprobado1' => $validateData['approved1'],
                    'medicamento2' => $validateData['medication2'],
                    'cantidad2' => $validateData['amount2'],
                    'aprobado2' => $validateData['approved2'],
                    'medicamento3' => $validateData['medication3'],
                    'cantidad3' => $validateData['amount3'],
                    'aprobado3' => $validateData['approved3'],
                ]);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating medications requests by id ' . $e->getMessage());
            return null;
        }
    }

    public function create($data)
    {
        try {
            $idSolicitud = DB::table('solicitudes_medicamentos')->insertGetId([
                'dni_persona' => $data['dni'],
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
                            'cantidad' => (int) $data[$amountKey],
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
                DB::table('items_medicamentos')->insert($items);
            }

            return true;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error creating medication request ' . $e->getMessage());
            return false;
        }
    }
    public function getItemsForMultipleRequests($ids)
    {
        if (empty($ids)) {
            return collect();
        }

        return DB::table('items_medicamentos')
            ->whereIn('id_solicitud', $ids)
            ->get();
    }

    public function getAllItemsByMedicationRequestId($id)
    {

        return DB::table('items_medicamentos')
            ->where('id_solicitud', $id)
            ->get();
    }

    public function getItemByMedicationRequestId($id)
    {
        try {
            return DB::table('items_medicamentos')->where('id', $id)->first();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting item by id ' . $e->getMessage());
            return null;
        }
    }


    public function approveItem($id)
    {
        try {
            DB::table('items_medicamentos')->where('id', $id)->update(['aprobado' => 1]);
            return true;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error approving item by id ' . $e->getMessage());
            return false;
        }
    }
    public function desapproveItem($id)
    {
        try {
            DB::table('items_medicamentos')->where('id', $id)->update(['aprobado' => 0]);
            return true;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error desapproving item by id ' . $e->getMessage());
            return false;
        }
    }
    public function updateItem($id, $data)
    {
        try {
            DB::table('items_medicamentos')
                ->where('id', $id)
                ->update([
                    'medicamento' => $data['medicamento'],
                    'cantidad' => $data['cantidad'],
                    'lote_med' => $data['lote_med'],
                    'vencimiento_med' => $data['vencimiento_med'],
                ]);
            return true;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating item by id ' . $e->getMessage());
            return false;
        }
    }






}
