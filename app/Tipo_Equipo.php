<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tipo_Equipo extends Model
{
    public $table = "tipos_equipos";
    public $timestamps = false;
    public function scopeIndex ($query)
    {
        return $query->select('tipos_equipos.id as id', 'tipos_equipos.nombre as nombre');
    }

}

