<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Equipo_mant extends Model
{
    public $table = "equipos_mant_temp";
    public $timestamps = false;
    public function scopeID($query, $id_e){
        if($id_e){
            return $query -> where('equipos_mant_temp.id','LIKE',"%$id_e%");
        }
    }

    public function scopeRelaciones_index ($query, $tipo, $id_area, $id_localizacion){
        $query->leftjoin('localizaciones', 'localizaciones.id', 'equipos_mant_temp.id_localizacion')
            ->leftjoin('area', 'area.id_a', 'equipos_mant_temp.id_area')
            ->leftjoin('tipos_equipos', 'tipos_equipos.id', 'equipos_mant_temp.id_tipo')
            //no colocar id solo porque convierte valores no numericos en 0
            ->select(DB::raw("equipos_mant_temp.id as id_e"), 
            'equipos_mant_temp.marca as marca', 'equipos_mant_temp.modelo as modelo', 'equipos_mant_temp.descripcion as descripcion', 
            'equipos_mant_temp.uso as uso', 'localizaciones.nombre as localizacion', 'area.nombre_a as area', 
            'equipos_mant_temp.uso as uso', 'tipos_equipos.id as id_tipo', 'localizaciones.id as id_localizacion', 
            'area.id_a as id_area', 'tipos_equipos.nombre as nombre_tipo', 'equipos_mant_temp.num_serie as num_serie');

        if($tipo != 0){
            $query->where('id_tipo', $tipo);
        }
        if($id_area != ""){
            $query->where('equipos_mant_temp.id_area', $id_area);
        }
        if($id_localizacion != 0){
            $query->where('id_localizacion', $id_localizacion);
        }

        return $query;
    }
    public function getEquipoMantShowUpdate($id){
        $equipo_mant = DB::table('equipos_mant_temp')
            ->leftjoin('localizaciones', 'localizaciones.id', 'equipos_mant_temp.id_localizacion')
            ->leftjoin('area', 'area.id_a', 'localizaciones.id_area')
            ->leftjoin('tipos_equipos', 'tipos_equipos.id', 'equipos_mant_temp.id_tipo')
            ->select('equipos_mant_temp.id as id', 'equipos_mant_temp.marca as marca', 'equipos_mant_temp.modelo as modelo', 'equipos_mant_temp.descripcion as descripcion', 
            'equipos_mant_temp.uso as uso', 'localizaciones.nombre as localizacion', 'area.nombre_a as area', 'equipos_mant_temp.uso as uso', 
            'tipos_equipos.nombre as nombre_tipo', 'equipos_mant_temp.num_serie as num_serie')
            ->where('equipos_mant_temp.id', $id)
            ->first();

        return $equipo_mant;
    }
    public static function getEquipmentToUpdate($id){
        return self::where('id', $id)->first();
    }
}

