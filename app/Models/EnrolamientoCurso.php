<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrolamientoCurso extends Model
{
    //use HasFactory;

    protected $table = 'enrolamiento_cursos';

    //el campo id de la tabla cursos_instancia lo reconoce Laravel automaticamente como primary key de la tabla
    protected $fillable = [
        'id_persona', 
        'id_instancia', 
        'fecha_enrolamiento', 
        'estado', 
        'evaluacion'
    ];

    //actualiza las columnas created_at updated_at automaticamente
    public $timestamps = true;

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function instanciaCurso()
    {
        return $this->belongsTo(CursoInstancia::class, 'id_instancia');
    }
}
