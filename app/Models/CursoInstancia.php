<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoInstancia extends Model
{
    //use HasFactory;

    protected $table = 'cursos_instancia';

    //el campo id de la tabla cursos_instancia lo reconoce Laravel automaticamente como primary key de la tabla
    protected $fillable = [
        'id_curso', 
        'fecha_inicio', 
        'fecha_fin', 
        'cupo', 
        'modalidad', 
        'capacitador', 
        'lugar', 
        'estado',
        'version'
    ];

    // Indicar a Laravel que los campos son de tipo fecha
    protected $dates = [
        'fecha_inicio',
        'fecha_fin',
    ];
   
    public $timestamps = true;

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }

    public function enrolamientos()
    {
        return $this->hasMany(EnrolamientoCurso::class, 'id_instancia');
    }
}
