<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historico_solicitudes extends Model
{
    public function scopeID_solicitud($query, $id_solitud)
    {
        if($id_solitud){
        return $query -> where('id_solitud','LIKE',"%$id_solitud%");
        }
    }
    public function scopeID_estado($query, $id_estado)
    {
        if($id_estado){
        return $query -> where('id_estado','LIKE',"%$id_estado%");
        }
    }
    public function scopeFecha($query, $fecha)
    {
        if($fecha){
        return $query -> where('fecha',$fecha);
        }
    }
    public function scopeID_usuario($query, $id_usuario)
    {
        if($id_usuario){
        return $query -> where('id_usuario','LIKE',"%$id_usuario%");
        }
    }
    public function scopeDescripcion ($query, $descripcion)
    {
    	if($descripcion){
    	return $query -> where('descripcion','LIKE', "%$descripcion%");
    	}
    }
    public function scopeDetalles ($query)
    {
    	return $query ->leftjoin('estados', 'historico_solicitudes.id_estado', 'estados.id')
        ->select('historico_solicitudes.fecha', 'estado.nombre', 'historico_solicitudes.descripcion')
        ->orderBy('historico_solicitudes.fecha', 'DESC');
    }
}