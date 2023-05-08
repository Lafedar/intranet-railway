<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tipo_Solicitud extends Model{
    public $table = "tipo_solicitudes";
    public $timestamps = false;
    public function scopeIndex ($query){
        return $query->select('tipo_solicitudes.id as id', 'tipo_solicitudes.nombre as nombre');
    }
    public static function showTipoSolicitud($id){
        return DB::table('tipo_solicitudes')
        ->select('tipo_solicitudes.id as id', 'tipo_solicitudes.nombre as nombre')
        ->where('tipo_solicitudes.id', $id)
        ->first();
    }
    public static function updateTipoSolicitud($id, $nombre){
        DB::table('tipo_solicitudes')
        ->where('tipo_solicitudes.id',$id)
        ->update([
            'nombre' => $nombre
        ]);    
    }
}

