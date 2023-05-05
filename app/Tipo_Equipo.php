<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tipo_Equipo extends Model
{
    public $table = "tipos_equipos";
    public $timestamps = false;
    public function scopeIndex ($query){
        return $query->select('tipos_equipos.id as id', 'tipos_equipos.nombre as nombre');
    }
    public static function getFallasXTipoConNombre(){
        return DB::table('fallasxtipo')
        ->leftjoin('fallas', 'fallas.id', 'fallasxtipo.id_falla')
        ->leftjoin('tipo_solicitudes', 'tipo_solicitudes.id', 'fallasxtipo.id_tipo_solicitud')
        ->select('fallasxtipo.id_tipo_equipo as id_tipo_equipo', 'fallas.nombre as nom_falla', 'tipo_solicitudes.nombre as nom_tipo_solicitud')
        ->get();
    }
    public static function tipoEquipoSeleccionado($id){
        return Tipo_Equipo::select('tipos_equipos.id as id', 'tipos_equipos.nombre as nombre')
        ->where('tipos_equipos.id', $id)
        ->first();
    }
    public static function updateTipoEquipo($id, $nombre){
        Tipo_Equipo::where('tipos_equipos.id',$id)
        ->update([
            'nombre' => $nombre
        ]);    
    }
    public static function deleteFallaXTipo($idFalla, $idTipoEquipo){
        DB::table('fallasxtipo')->where('fallasxtipo.id_tipo_equipo', $idTipoEquipo)
        ->where('fallasxtipo.id_falla' ,$idFalla)->delete();
    }
    public static function getFallas(){
        return DB::table('fallas')->get();
    }
    public static function getFallasXTipo(){
        return DB::table('fallasxtipo')->get();
    }
}

