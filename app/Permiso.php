<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Permiso extends Model
{
        protected $table='permisos';

	public function scopeRelaciones($query, $jefe,$motivo){
    		
    		if($motivo == 0){
    			return $query->join('personas as p1','permisos.autorizado','p1.id_p')
                ->join('personas as p2','permisos.autorizante','p2.id_p')
                ->join('tipo_permiso','permisos.motivo','tipo_permiso.id_tip')
                ->join('area','p1.area','area.id_a')
                ->join('jefe_area','p1.area','jefe_area.area')
                ->where('jefe_area.jefe',$jefe)
                ->select('p1.nombre_p as nombre_autorizado','p1.apellido as apellido_autorizado' , 'permisos.created_at as fecha_permiso','permisos.fecha_desde as fecha_desde','permisos.fecha_hasta as fecha_hasta','tipo_permiso.desc as motivo', 'permisos.id as id','permisos.hora_desde as hora_desde','permisos.hora_hasta as hora_hasta', 'permisos.descripcion as descripcion','area.nombre_a as area', 'p2.nombre_p as nombre_autorizante', 'p2.apellido as apellido_autorizante')
                ->orderBy('fecha_permiso','DESC');
            }
            else{
            	return $query->join('personas as p1','permisos.autorizado','p1.id_p')
                ->join('personas as p2','permisos.autorizante','p2.id_p')
                ->join('tipo_permiso','permisos.motivo','tipo_permiso.id_tip')
                ->join('area','p1.area','area.id_a')
                ->join('jefe_area','p1.area','jefe_area.area')
                ->where('jefe_area.jefe',$jefe)
                ->where('permisos.motivo',$motivo)
                ->select('p1.nombre_p as nombre_autorizado','p1.apellido as apellido_autorizado' , 'permisos.created_at as fecha_permiso','permisos.fecha_desde as fecha_desde','permisos.fecha_hasta as fecha_hasta','tipo_permiso.desc as motivo', 'permisos.id as id','permisos.hora_desde as hora_desde','permisos.hora_hasta as hora_hasta', 'permisos.descripcion as descripcion','area.nombre_a as area', 'p2.nombre_p as nombre_autorizante', 'p2.apellido as apellido_autorizante')
                ->orderBy('fecha_permiso','DESC');
            }
    }
    public  function scopeEmpleado ($query, $empleado)
    {
    	if($empleado){
    	return $query -> where(DB::raw("CONCAT(p1.nombre_p,' ',p1.apellido)"), 'LIKE',"%$empleado%");
    	
    	}
    }

    public function scopeJefe ($query){

    	return $query -> where('p2.rango','!=',1);
    }

    public function scopeBuscaPermiso($query, $id){

    	return $query ->join('personas','permisos.autorizado','personas.id_p')
        ->join('area','personas.area','area.id_a')
        ->join('tipo_permiso','permisos.motivo','tipo_permiso.id_tip')
        ->join('jefe_area','personas.area','jefe_area.area')
        ->where('permisos.id',$id)
        ->select('personas.nombre_p as nombre_autorizado','personas.apellido as apellido_autorizado' , 'permisos.created_at as fecha_permiso','permisos.fecha_desde as fecha_desde', 'permisos.fecha_hasta as fecha_hasta','tipo_permiso.desc as motivo', 'permisos.id as id','permisos.hora_desde as hora_desde','permisos.hora_hasta as hora_hasta','permisos.descripcion as descripcion','area.nombre_a as area');
    }

}