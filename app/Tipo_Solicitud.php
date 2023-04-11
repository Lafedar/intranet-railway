<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tipo_Solicitud extends Model
{
    public $table = "tipo_solicitudes";
    public $timestamps = false;
    public function scopeIndex ($query)
    {
        return $query->select('tipo_solicitudes.id as id', 'tipo_solicitudes.nombre as nombre');
    }

}

