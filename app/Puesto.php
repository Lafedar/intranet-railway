<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Puesto extends Model{
   protected $table = 'puestos';
   protected $primaryKey = 'id_puesto';

   public function scopeRelaciones($query){
    	return  $query ->leftjoin('personas','puestos.persona','personas.id_p')
       ->leftjoin('localizaciones', 'localizaciones.id', 'puestos.id_localizacion')
       ->leftjoin('area','area.id_a', 'localizaciones.id_area')
         ->select('puestos.id_puesto as id_puesto','puestos.desc_puesto as desc_puesto','personas.nombre_p as nombre',
         'personas.apellido as apellido','area.nombre_a as area','puestos.obs as obs', 'localizaciones.nombre as localizacion');
   }
   public  function scopePuesto ($query, $puesto){
    	if($puesto){
    	   return $query -> where('desc_puesto','LIKE', "%$puesto%");
    	}
    }

   public  function scopeUsuario ($query, $usuario){
    	if($usuario){
    	   return $query -> where(DB::raw("CONCAT(nombre_p,' ',apellido)"), 'LIKE',"%$usuario%");
    	}
   }
   public  function scopeArea ($query, $area){
    	if($area){
    	   return $query ->where('nombre_a', 'LIKE', "%$area%");
    	}
   }
   public  function scopeLocalizacion ($query, $localizacion){
    	if($localizacion){
    	   return $query ->where('nombre', 'LIKE', "%$localizacion%");
    	}
   }
   public static function showPuestoUpdate($id) {
      $puesto = Puesto::find($id);
      return $puesto;
   }
}

