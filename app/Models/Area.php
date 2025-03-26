<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use app\Models\Persona;
use app\Models\Course;
use app\Models\Location;

class Area extends Model
{
    protected $table = "area";
    public $timestamps = false;

    protected $primaryKey = 'id_a';
    public $incrementing = false;
    protected $keyType = 'string';
    public function cursos()
    {
        return $this->belongsToMany(Course::class, 'relacion_curso_area', 'id_area', 'id_curso');
    }

    public function scopeIndex($query)
    {
        return $query->select('area.id_a as id_a', 'area.nombre_a as nombre_a');
    }
    public function personas()
    {
        return $this->hasMany(Persona::class, 'area', 'id_a');
    }
    public function locations()
    {
        return $this->hasMany(Location::class, 'id_area');
    }


}

