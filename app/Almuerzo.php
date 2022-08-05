<?php

namespace App;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almuerzo extends Model
{
	
   
    protected $table ='almuerzos';
    protected $primaryKey='id';
    protected $fillable = ['id_e','lunes','martes','miercoles','jueves','viernes', 'fecha','activo','id_sem'];

   public function scopeNombre($query, $nombre)
   {	
   		if($nombre)
   			return $query->where('id_e','LIKE',"%$nombre%");

   }

   public function scopeIdsem($query, $idsem)
   {	
   		if($idsem)
   			return $query->where('id_sem','LIKE',"%$idsem%");

   }	

}
