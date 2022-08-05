<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Visita extends Model
{
    protected $table='visitas';
    protected $primaryKey = 'id_vis';



    public function scopeRelaciones($query, $estado)
    {
    	if($estado == 2 || $estado == null){
        return  $query ->leftjoin('personas','visitas.interno','personas.id_p')
                    ->leftjoin('externos','visitas.externo','externos.dni')
                    ->leftjoin('tarjetas','visitas.tarjeta','tarjetas.id_tar')
                    ->leftjoin('empresas','externos.empresa_ext','empresas.id_emp')
                    ->select('visitas.tarjeta as tarjeta','externos.nombre_ext as visitante_nombre' , 'externos.apellido_ext as visitante_apellido','empresas.razon_social as empresa', 'personas.nombre_p as visita_a_nombre', 'personas.apellido as visita_a_apellido', 'visitas.created_at as fecha_inicio', 'visitas.updated_at as fecha_fin', 'visitas.activa as activa','externos.dni as dni_ext')
                    ->orderBy('fecha_inicio','desc');
                }
                else{
                   return  $query ->leftjoin('personas','visitas.interno','personas.id_p')
                    ->leftjoin('externos','visitas.externo','externos.dni')
                    ->leftjoin('tarjetas','visitas.tarjeta','tarjetas.id_tar')
                    ->leftjoin('empresas','externos.empresa_ext','empresas.id_emp')
                    ->where('activa',$estado)
                    ->select('visitas.tarjeta as tarjeta','externos.nombre_ext as visitante_nombre' , 'externos.apellido_ext as visitante_apellido','empresas.razon_social as empresa', 'personas.nombre_p as visita_a_nombre', 'personas.apellido as visita_a_apellido', 'visitas.created_at as fecha_inicio', 'visitas.updated_at as fecha_fin', 'visitas.activa as activa')
                    ->orderBy('fecha_inicio','desc');
                }

    }

    public  function scopeVisita_a ($query, $visita_a)
    {
    	if($visita_a){
    	return $query -> where(DB::raw("CONCAT(nombre_p,' ',apellido)"), 'LIKE',"%$visita_a%");
    	
    	}
    }

    public  function scopeVisitante ($query, $visitante)
    {
    	if($visitante){
    	return $query -> where(DB::raw("CONCAT(nombre_ext,' ',apellido_ext)"), 'LIKE',"%$visitante%");
    	
    	}
    }

    public function scopeTarjeta($query, $tarjeta){
    	if($tarjeta){
    		return $query ->where('visitas.tarjeta','LIKE',"%$tarjeta%");
    	}
    }
    public function scopeFecha($query, $fecha){
    if($fecha != null){
    return $query -> where('visitas.updated_at','LIKE',"%$fecha%");
    }
}
}
