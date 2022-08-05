<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Software extends Model
{   
    protected $table = 'softinst';
    protected $primaryKey ='id_s';




public function scopeSoftware($query, $software){
    if($software){
    return $query -> where('Software','LIKE',"%$software%");
    }
}

public function scopeVersion($query, $version){
    if($version){
        return $query -> where('Version','LIKE',"%$version%");
    }
}

public  function scopeEquipo ($query, $equipo)
    {
        if($equipo){
        return $query -> where('id_e','LIKE', "%$equipo%");
        }
    }


}

