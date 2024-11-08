<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Models\Persona;

class Area extends Model
{
    public $table = "area";
    public $timestamps = false;
    
    protected $primaryKey = 'id_a';  
    public $incrementing = false;    
    protected $keyType = 'string';  
    public function cursos()
{
    return $this->belongsToMany(Curso::class, 'relacion_curso_area', 'id_area', 'id_curso');
}
    
public function scopeIndex($query)
{
    return $query->select('area.id_a as id_a', 'area.nombre_a as nombre_a');
}
    public function personas()
{
    return $this->hasMany(Persona::class, 'area', 'id_a');  
}
    

}

