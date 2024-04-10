<?php

namespace App\Models;  //agrego \Models

use Illuminate\Database\Eloquent\Model;
use DB;

class Persona extends Model
{
    protected $table='mi_agenda';

public function scopeEmpresa($query, $empresa){
    if($empresa){
    return $query -> where('mi_agenda.empresa','LIKE',"%$empresa%");
    }
}

public function scopeNombre($query, $nombre){
    if($nombre){
        return $query -> where(DB::raw("CONCAT(nombre,' ',apellido)"), 'LIKE',"%$nombre%");
    }
}

public function traerPersonas() {
    return DB::table('personas')->orderBy('personas.nombre_p', 'asc')->get();
}



}
