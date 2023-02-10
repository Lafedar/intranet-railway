<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Falla extends Model
{
    public function scopeID($query, $id)
    {
        if($id){
        return $query -> where('id','LIKE',"%$id%");
        }
    }
    public function scopeNombre ($query, $nombre)
    {
    	if($nombre){
    	return $query -> where('nombre','LIKE', "%$nombre%");
    	}
    }
}