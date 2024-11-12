<?php

namespace App\Models;
use App\Area;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'cursos';
    protected $fillable = ['codigo', 'titulo', 'descripcion', 'creado', 'obligatorio', 'tipo'];
    public $timestamps = true;

    public function instancias()
    {
        return $this->hasMany(CursoInstancia::class, 'id_curso');
    }

    public function areas()
{
    return $this->belongsToMany(Area::class, 'relacion_curso_area', 'id_curso', 'id_area');
}
public function enrolamientos()
    {
        return $this->hasMany(EnrolamientoCurso::class, 'id_curso');
    }
    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'enrolamiento_cursos', 'id_curso', 'id_persona')
        ->withPivot('id_instancia', 'evaluacion');
    }
    
}
