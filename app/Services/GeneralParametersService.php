<?php

namespace App\Services;



use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class GeneralParametersService
{
    public function getMailsToMedicationRequests(){
        try{
            return DB::connection('mysql_read')->table('parametros_mant')->where('id_param', 'PMAILSMED')->value('valor_param');
        }catch(Exception $e){
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error getting mails to medication requests: ' . $e->getMessage());
            return false;
        }
        
    }

  
}
