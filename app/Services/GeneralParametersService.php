<?php

namespace App\Services;



use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class GeneralParametersService
{

    public function update($id, $descripcion, $valor)
    {
        try {
            DB::table('parametros_mant')->where('id_param', $id)->update([
                'descripcion_param' => $descripcion,
                'valor_param' => $valor,
            ]);

            return true;
        } catch (Exception $e) {
            
            Log::error('Error in class: ' . get_class($this) . ' .Error updating parameter' . $e->getMessage());
            return false;
            
        }

    }
    public function delete($id){
        try{
            DB::table('parametros_mant')->where('id_param', $id)->delete();
            return true;
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error deleting parameter' . $e->getMessage());
            return false;
        }
        
    }

    public function getMegabytesMax(){
        try{
            return DB::table('parametros_mant')
            ->where('id_param', 'PMB')
            ->value('valor_param')
            ->get();
            
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error getting max megabytes' . $e->getMessage());
            return false;
        }
        
    }

    public function store($id, $descripcion, $valor, $origen){
        try{
            DB::table('parametros_mant')->insert([
                'id_param' => $id,
                'descripcion_param' => $descripcion,
                'valor_param' => $valor,
                'origen' => $origen,
            ]);
            return true;
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error storing parameter' . $e->getMessage());
            return false;
        }
       

    }
    public function listAll(){
        try{
            return  DB::table('parametros_mant')->get();
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error listing all parameters' . $e->getMessage());
            return false;
        }
        
    }

    public function getMailsToMedicationRequests(){
        try{
            return DB::table('parametros_mant')->where('id_param', 'PMAILSMED')->value('valor_param');
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error getting mails to medication requests' . $e->getMessage());
            return false;
        }
        
    }

    public function checkIfExists($id){

        try{
            return DB::table('parametros_mant')->where('id_param', $id)->exists();
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error getting parameter by id' . $e->getMessage());
            return false;
        }
    }
}
