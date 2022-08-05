<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    public  function scopeID ($query, $id)
    {
    	if($id){
    	return $query -> where('id','LIKE', "%$id%");
    	}
    }
    public  function scopeTitulo ($query, $titulo)
    {
    	if($titulo){
    	return $query -> where('titulo','LIKE', "%$titulo%");
    	}
    }

    public  function scopeObs ($query, $obs)
    {
    	if($obs){
    	return $query -> where('obs','LIKE', "%$obs%");
    	}
    }
    public function scopeFecha($query, $fecha){
    if($fecha != null){
    return $query -> where('fecha',$fecha);
    }
}
}
