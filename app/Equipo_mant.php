<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipo_mant extends Model
{
    public $table = "equipos_mant";

    public function scopeRelaciones_index ($query)
    {
    	return $query ->leftjoin('localizaciones', 'localizaciones.id', 'equipos_mant.id_localizacion')
        ->leftjoin('area', 'area.id_a', 'localizaciones.id_area')
        ->leftjoin('tipos_equipos', 'tipos_equipos.id', 'equipos_mant.id_tipo')
        ->select('equipos_mant.id as id', 'equipos_mant.marca as marca', 'equipos_mant.modelo as modelo', 'equipos_mant.descripcion as descripcion', 
        'equipos_mant.uso as uso', 'localizaciones.nombre as localizacion', 'area.nombre_a as area', 'equipos_mant.uso as uso', 
        'tipos_equipos.nombre as nombre_tipo', 'equipos_mant.num_serie as num_serie');
    }

}

