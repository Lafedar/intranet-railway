<?php

namespace App\Services;

use App\Area;

use Illuminate\Database\Eloquent\Collection; 

class AreaService
{
   
    public function getAll(): Collection
    {
        try{
            return Area::where('permite_personal', 1)->get();
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener las areas' . $e->getMessage());
            throw $e;
        }
    }
    public function getAreaById(string $areaId)
    {
        try{
            return Area::find($areaId);
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener el area' . $e->getMessage());
            throw $e;
        }
    }
    
}
