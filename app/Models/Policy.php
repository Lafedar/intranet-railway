<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Policy extends Model
{
    public $table = "politica";
    protected $fillable = ['titulo','fecha','pdf'];

    public function scopeID($query, $id)
    {
        if($id)
        {
            return $query -> where('id','LIKE',"%$id%");
        }
    }
    public function scopeTitulo ($query, $titulo)
    {
    	if($titulo)
        {
    	    return $query -> where('titulo','LIKE', "%$titulo%");
    	}
    }
    public function scopeFecha($query, $fecha)
    {
        if($fecha != null)
        {
            return $query -> where('fecha',$fecha);
        }
    }
}
