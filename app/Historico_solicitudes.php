<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historico_solicitudes extends Model
{
    public $table = "historico_solicitudes";
    public $timestamps = false;
    /*public function scopeID_solicitud($query, $id_solitud)
    {
        if($id_solitud)
        {
            return $query -> where('id_solitud','LIKE',"%$id_solitud%");
        }
    }
    public function scopeID_estado($query, $id_estado)
    {
        if($id_estado)
        {
            return $query -> where('id_estado','LIKE',"%$id_estado%");
        }
    }
    public function scopeFecha($query, $fecha)
    {
        if($fecha)
        {
            return $query -> where('fecha',$fecha);
        }
    }
    public function scopeID_usuario($query, $id_usuario)
    {
        if($id_usuario)
        {
            return $query -> where('id_usuario','LIKE',"%$id_usuario%");
        }
    }
    public function scopeDescripcion ($query, $descripcion)
    {
    	if($descripcion)
        {
    	    return $query -> where('descripcion','LIKE', "%$descripcion%");
    	}
    }*/
    /*public function scopeDetalle ($query)
    {

        SELECT historico_solicitudes.fecha ,historico_solicitudes.descripcion, estados.nombre FROM `historico_solicitudes` LEFT JOIN estados 
        ON estados.id = historico_solicitudes.id_estado; 

        return $query->select('historico_solicitudes.fecha as fecha', 'historico_solicitudes.descripcion as descripcion', 
        'historico_solicitudes.id_estado as estado');

        return $query ->leftjoin('estados', 'estados.id', 'historico_solicitudes.id_estado')
        ->select('historico_solicitudes.fecha as fecha', 'historico_solicitudes.descripcion as descripcion', 'estados.nombre as estado');

    }*/
}