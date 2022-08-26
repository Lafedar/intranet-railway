<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Frecuencia extends Model
{
    public function scopeID($query, $id)
    {
        if($id){
        return $query -> where('id','LIKE',"%$id%");
        }
    }
    public function scopeFrecuencia ($query, $frecuencia)
    {
    	if($frecuencia){
    	return $query -> where('frecuencia','LIKE', "%$frecuencia%");
    	}
    }
}