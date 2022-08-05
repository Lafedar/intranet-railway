<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $table='menuses';
    protected $primaryKey = 'id';

    public function scopeID($query, $id){
    	if($id)
    		return $query->where('id','LIKE',"%$id%");
    	


    }

    

}