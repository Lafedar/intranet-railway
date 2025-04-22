<?php

namespace App\Services;

use App\Models\Location;
use Exception;
use Log;
use Illuminate\Database\Eloquent\Collection;
use DB;

class MedicationsRequestService
{

    public function getAll(){
        try{
            return DB::table('solicitudes_medicamentos')->get();
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error getting all medications requests ' . $e->getMessage());
            return null;
        }
    }

    public function getRequestsByDni($dni){
        try{
            return DB::table('solicitudes_medicamentos')->where('dni_persona', $dni)->get();
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error getting medications requests by dni ' . $e->getMessage());
            return null;
        }
    }

    public function deleteRequestById($id){
        try{
            return DB::table('solicitudes_medicamentos')->where('id', $id)->delete();
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error deletting medications requests by id ' . $e->getMessage());
            return null;
        }
    }



}
