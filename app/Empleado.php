<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table='personas';
    protected $primaryKey = 'id_p';



    public function scopeRelacion($query){
        return $query->leftjoin('area', 'area.id_a', 'personas.area')
        ->orderBy('apellido', 'ASC');
    }

      public function scopeBusca_personas($query, $persona){
        return $query ->join('jefe_area','personas.area','jefe_area.area')
            ->where('personas.activo',1)
            ->where('jefe_area.jefe',$persona)
            ->where('personas.id_p','!=',$persona)
            ->select('personas.id_p','personas.nombre_p','personas.apellido')
            ->orderBy('apellido','asc');  
    }

    public function scopeRango ($query){
        return $query -> where('personas.rango','!=',1);    
    }
    
}
    