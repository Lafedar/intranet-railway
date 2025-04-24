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
            return DB::table('solicitudes_medicamentos')->get();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting all medications requests ' . $e->getMessage());
            return null;
        }
    }

    public function getRequestsByDni($dni)
    {
        try {
            return DB::table('solicitudes_medicamentos')->where('dni_persona', $dni)->get();
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
            return DB::table('solicitudes_medicamentos')->where('id', $id)->update(['estado' => 'Aprobacion Pendiente']);
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
                    'estado' => 'Completada',
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






}
