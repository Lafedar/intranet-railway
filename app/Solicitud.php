<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    public $table = "solicitudes";
    public $timestamps = false;
    public function scopeID($query, $id)
    {
        if($id)
        {
            return $query -> where('id','LIKE',"%$id%");
        }
    }
    public function scopeTitulo($query, $titulo)
    {
        if($titulo)
        {
            return $query -> where('titulo','LIKE',"%$titulo%");
        }
    }
    public  function scopeEquipo ($query, $equipo)
    {
    	if($equipo)
        {
    	    return $query -> where('equipo','LIKE', "%$equipo%");
    	}
    }
    public  function scopeFalla ($query, $falla)
    {
    	if($falla)
        {
    	    return $query -> where('falla','LIKE', "%$falla%");
    	}
    }
    public function scopeSolicitante($query, $solicitante)
    {
        if($solicitante != null)
        {
            return $query -> where('solicitante', 'LIKE', "%$solicitante%");
        }
    }
    public function scopeEncargado ($query, $encargado)
    {
    	if($encargado)
        {
    	    return $query -> where('encargado','LIKE', "%$encargado%");
    	}
    }
    public function scopeRelaciones_index ($query)
    {
    	return $query ->leftjoin('historico_solicitudes', 'historico_solicitudes.id_solicitud', 'solicitudes.id')
        ->leftjoin('estados', 'historico_solicitudes.id_estado', 'estados.id')
        ->leftjoin('fallas', 'fallas.id', 'solicitudes.id_falla')
        ->leftjoin('users as usuario_encargado', 'usuario_encargado.id', 'solicitudes.id_encargado')
        ->leftjoin('users as usuario_solicitante', 'usuario_solicitante.id', 'solicitudes.id_solicitante')
        ->leftjoin('tipo_solicitudes', 'tipo_solicitudes.id', 'solicitudes.id_tipo_solicitud')
        ->where('historico_solicitudes.actual', '=', 1)
        ->select('solicitudes.id as id', 'solicitudes.titulo as titulo', 'tipo_solicitudes.nombre as tipo_solicitud', 'fallas.nombre as falla', 
        'usuario_encargado.name as nombre_encargado', 'usuario_solicitante.name as nombre_solicitante', 'solicitudes.id_equipo as id_equipo', 
        'estados.nombre as estado');
    }
}

?>
