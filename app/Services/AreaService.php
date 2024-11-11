<?php

namespace App\Services;

use App\Area;

use Illuminate\Database\Eloquent\Collection; 

class AreaService
{
   
    public function getAll(): Collection
    {
        return Area::all();
           
    }
    public function getAreaById(string $areaId)
    {
        return Area::find($areaId);
    }
    
}
