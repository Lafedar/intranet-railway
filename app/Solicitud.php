<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    public $table = "solicitudes";
    public $timestamps = false;
    public function scopeID($query, $id_solicitud)
    {
        if($id_solicitud)
        {
            return $query -> where('id_solicitud','LIKE',"%$id_solicitud%");
        }
    }
    public function scopeTitulo($query, $titulo)
    {
        if($titulo)
        {
            return $query -> where('titulo','LIKE',"%$titulo%");
        }
    }
    public  function scopeEquipo ($query, $id_equipo)
    {
    	if($id_equipo)
        {
    	    return $query -> where('id_equipo','LIKE', "%$id_equipo%");
    	}
    }
    public  function scopeFalla ($query, $id_falla)
    {
    	if($id_falla)
        {
    	    return $query -> where('id_falla','LIKE', "%$id_falla%");
    	}
    }   
    public function scopeRelaciones_index($query, $id_tipo_solicitud, $id_estado, $id_encargado, $id_solicitante)
    {
    $query->leftjoin('historico_solicitudes', 'historico_solicitudes.id_solicitud', 'solicitudes.id')
          ->leftjoin('estados', 'historico_solicitudes.id_estado', 'estados.id')
          ->leftjoin('fallas', 'fallas.id', 'solicitudes.id_falla')
          ->leftjoin('users as usuario_encargado', 'usuario_encargado.id', 'solicitudes.id_encargado')
          ->leftjoin('users as usuario_solicitante', 'usuario_solicitante.id', 'solicitudes.id_solicitante')
          ->leftjoin('tipo_solicitudes', 'tipo_solicitudes.id', 'solicitudes.id_tipo_solicitud')
          ->where('historico_solicitudes.actual', '=', 1)
          ->select('solicitudes.id as id', 'solicitudes.titulo as titulo', 'tipo_solicitudes.nombre as tipo_solicitud', 'fallas.nombre as falla', 
                   'usuario_encargado.name as nombre_encargado', 'usuario_solicitante.name as nombre_solicitante', 'solicitudes.id_equipo as id_equipo', 
                   'estados.nombre as estado', 'historico_solicitudes.descripcion as descripcion');
    if ($id_tipo_solicitud != 0) {
        $query->where('id_tipo_solicitud', $id_tipo_solicitud);
    }
    if ($id_estado != 0) {
        $query->where('id_estado', $id_estado);
    }
    if ($id_encargado != 0) {
        $query->where('id_encargado', $id_encargado);
    }
    if ($id_solicitante != 0) {
        $query->where('id_solicitante', $id_solicitante);
    }

    return $query;
}
}

?>
