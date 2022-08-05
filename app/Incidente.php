<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incidente extends Model
{
        protected $table='incidentes';
        protected $primaryKey = 'id_i';


        public function scopeBusca($query, $resuelto){
        	
        	if($resuelto==null or $resuelto==2){

        		return $query ->select('incidentes.equipamiento as equipamiento','incidentes.descripcion as descripcion','incidentes.resuelto as resuelto','incidentes.solucion as solucion', 'incidentes.id_i as id_i', 'incidentes.created_at as creado')
    			->orderBy('incidentes.created_at','desc');

        	}
        	if ($resuelto == 0 or $resuelto == 1){
        		return $query 
	      ->select('incidentes.equipamiento as equipamiento','incidentes.descripcion as descripcion','incidentes.resuelto as resuelto','incidentes.solucion as solucion', 'incidentes.id_i as id_i', 'incidentes.created_at as creado')
      ->where('incidentes.resuelto','=',$resuelto)
      ->orderBy('incidentes.created_at','desc');
        	}
  
        }

        public function scopeEquipamiento($query,$equipamiento){

        	if($equipamiento){
        	return $query ->where('equipamiento','LIKE',"%$equipamiento%");
        	}
        }
}
