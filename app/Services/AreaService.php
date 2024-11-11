<?php

namespace App\Services;

use App\Area;

use Illuminate\Database\Eloquent\Collection; 

class AreaService
{
   
    public function getAll(): Collection
    {
        return Area::where('permite_personal', 1)->get();
    }
    public function getAreaById(string $areaId)
    {
        return Area::find($areaId);
    }
    
}
