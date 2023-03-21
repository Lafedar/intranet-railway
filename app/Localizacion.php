<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Localizacion extends Model
{
    public $table = "localizaciones";
    public $timestamps = false;
    public function scopeIndex ($query)
    {
        return $query->leftjoin('area', 'area.id_a', 'localizaciones.id_area')
        ->select('localizaciones.id as id', 'localizaciones.nombre as nombre', 'area.nombre_a as nombre_a');
    }

}

