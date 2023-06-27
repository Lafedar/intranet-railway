<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Equipo_mant extends Model
{
    public $table = "equipos_mant";
    public $timestamps = false;
    public function scopeID($query, $id_e){
        if($id_e){
            return $query -> where('equipos_mant.id','LIKE',"%$id_e%");
        }
    }

    public function scopeRelaciones_index ($query, $tipo, $id_area, $id_localizacion){
        $query->leftjoin('localizaciones', 'localizaciones.id', 'equipos_mant.id_localizacion')
            ->leftjoin('area', 'area.id_a', 'equipos_mant.id_area')
            ->leftjoin('tipos_equipos', 'tipos_equipos.id', 'equipos_mant.id_tipo')
            //no colocar id solo porque convierte valores no numericos en 0
            ->select(DB::raw("equipos_mant.id as id_e"), 
            'equipos_mant.marca as marca', 'equipos_mant.modelo as modelo', 'equipos_mant.descripcion as descripcion', 
            'equipos_mant.uso as uso', 'localizaciones.nombre as localizacion', 'area.nombre_a as area', 
            'equipos_mant.uso as uso', 'tipos_equipos.id as id_tipo', 'localizaciones.id as id_localizacion', 
            'area.id_a as id_area', 'tipos_equipos.nombre as nombre_tipo', 'equipos_mant.num_serie as num_serie');

        if($tipo != 0){
            $query->where('id_tipo', $tipo);
        }
        if($id_area != ""){
            $query->where('equipos_mant.id_area', $id_area);
        }
        if($id_localizacion != 0){
            $query->where('id_localizacion', $id_localizacion);
        }

        return $query;
    }
    public function getEquipoMantShowUpdate($id){
        $equipo_mant = DB::table('equipos_mant')
            ->leftjoin('localizaciones', 'localizaciones.id', 'equipos_mant.id_localizacion')
            ->leftjoin('area', 'area.id_a', 'localizaciones.id_area')
            ->leftjoin('tipos_equipos', 'tipos_equipos.id', 'equipos_mant.id_tipo')
            ->select('equipos_mant.id as id', 'equipos_mant.marca as marca', 'equipos_mant.modelo as modelo', 'equipos_mant.descripcion as descripcion', 
            'equipos_mant.uso as uso', 'localizaciones.nombre as localizacion', 'area.nombre_a as area', 'equipos_mant.uso as uso', 
            'tipos_equipos.nombre as nombre_tipo', 'equipos_mant.num_serie as num_serie')
            ->where('equipos_mant.id', $id)
            ->first();

        return $equipo_mant;
    }
    public static function getEquipmentToUpdate($id){
        return self::where('id', $id)->first();
    }
}

