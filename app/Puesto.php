<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Puesto extends Model
{
   protected $table = 'puestos';
   protected $primaryKey = 'id_puesto';

   public function scopeRelaciones($query)
   {
    	return  $query ->leftjoin('personas','puestos.persona','personas.id_p')
      ->leftjoin('area','puestos.area','area.id_a')
      ->select('puestos.id_puesto as id_puesto','puestos.desc_puesto as desc_puesto','personas.nombre_p as nombre',
      'personas.apellido as apellido','area.nombre_a as area','puestos.obs as obs');
   }
   public  function scopePuesto ($query, $puesto)
   {
    	if($puesto)
      {
    	   return $query -> where('desc_puesto','LIKE', "%$puesto%");
    	}
    }

   public  function scopeUsuario ($query, $usuario)
   {
    	if($usuario)
      {
    	   return $query -> where(DB::raw("CONCAT(nombre_p,' ',apellido)"), 'LIKE',"%$usuario%");
    	}
   }
   public  function scopeArea ($query, $area)
   {
    	if($area)
      {
    	   return $query ->where('nombre_a', 'LIKE', "%$area%");
    	}
   }






}

