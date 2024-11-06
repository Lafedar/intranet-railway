<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Area extends Model
{
    public $table = "area";
    public $timestamps = false;
    
    protected $primaryKey = 'id_a';  // Clave primaria como string
    public $incrementing = false;    // Especifica que no es un campo auto incremental
    protected $keyType = 'string';   // Asegura que Laravel trate 'id_a' como string
    
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'relacion_curso_area', 'id_area', 'id_curso');
    }
    
    public function scopeIndex($query)
    {
        return $query->select('area.id_a as id_a', 'area.nombre_a as nombre_a');
    }
    

}

