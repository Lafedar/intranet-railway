<?php

namespace App;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Permiso extends Model
{
    protected $table='permisos';
    public function scopeRelaciones($query, $jefe, $motivo) {
        $jefeAreasTurnos = DB::table('jefe_area')->where('jefe', $jefe)->get();
        $permisos = collect();
        
        foreach ($jefeAreasTurnos as $jefeAreaTurno) {
            $permisosQuery = DB::table('permisos')
                ->select('permisos.id as id',
                    'pAutorizado.nombre_p as nombre_autorizado', 
                    'pAutorizado.apellido as apellido_autorizado',
                    'permisos.created_at as fecha_permiso',
                    'permisos.fecha_desde as fecha_desde',
                    'permisos.fecha_hasta as fecha_hasta',
                    'permisos.id as id',
                    'permisos.hora_desde as hora_desde',
                    'permisos.hora_hasta as hora_hasta',
                    'permisos.descripcion as descripcion',
                    'pAutorizante.nombre_p as nombre_autorizante',
                    'pAutorizante.apellido as apellido_autorizante',
                    'tipo_permiso.desc as motivo',
                    'area.nombre_a as area')
                ->leftJoin('tipo_permiso', 'tipo_permiso.id_tip', 'permisos.motivo')
                ->leftJoin('personas AS pAutorizado', 'pAutorizado.id_p', '=', 'permisos.autorizado')
                ->leftJoin('personas AS pAutorizante', 'pAutorizante.id_p', '=', 'permisos.autorizante')
                ->leftJoin('jefe_area', function ($join) use ($jefeAreaTurno) {
                    $join->on('jefe_area.jefe', '=', 'pAutorizante.id_p')
                        ->on('jefe_area.area', '=', 'pAutorizado.area')
                        ->on('jefe_area.turno', '=', 'pAutorizado.turno');
                })
                ->leftJoin('area', 'area.id_a', 'jefe_area.area')
                ->where('jefe_area.area', '=', $jefeAreaTurno->area)
                ->where('jefe_area.turno', '=', $jefeAreaTurno->turno);
        
            if ($motivo) {
                $permisosQuery->where('permisos.motivo', '=', $motivo);
            }
        
            $permisos = $permisos->merge($permisosQuery->get());
        }
        $permisos = $permisos->unique('id');
        $permisos = $permisos->sortByDesc('fecha_permiso');
        return $permisos;
    }
    public  function scopeEmpleado ($query, $empleado){
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