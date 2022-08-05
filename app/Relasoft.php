<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relasoft extends Model
{
    protected $table='relasoft';
    protected $primaryKey = 'id_rs';



public function scopeRelacione($query)
    {
       return  $query ->join('softinst','relasoft.softw','softinst.id_s')
                      ->join('equipamientos','relasoft.equipo','equipamientos.id_e')
                       
                       ->select('equipamientos.id_e as equipo','softinst.Software as software','softinst.Version as version','softinst.Obs as obs', 'relasoft.licen as licen','relasoft.id_rs as id', 'relasoft.estado as estado');
          }             
public function scopeSoftware($query, $software){
    if($software){
    return $query -> where('software','LIKE',"%$software%");
    }
}

public function scopeVersion($query, $version){
    if($version){
        return $query -> where('version','LIKE',"%$version%");
    }
}

public  function scopeEquipo ($query, $equipo)
    {
        if($equipo){
        return $query -> where('equipo','LIKE', "%$equipo%");
        }
    }

public  function scopeLicen ($query, $licen)
    {
        if($licen){
        return $query -> where('licen','LIKE', "%$licen%");
        }
    }
public  function scopeid ($query, $id)
    {
        if($id){
        return $query -> where('id_rs','LIKE', "%$id%");
        }
    }

    public  function scopeEstado ($query, $estado)
    {
        if($estado){
        return $query -> where('estado','LIKE', "%$estado%");
        }
    }

}