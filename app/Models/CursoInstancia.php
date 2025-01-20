<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Anexo;

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
        'codigo',
        'lugar',
        'estado',
        'version',
        'id_instancia'


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


    /*public function enrolamientos()
{
    return $this->hasMany(EnrolamientoCurso::class, 'id_instancia', 'id_instancia');
}*/
    public function enrolamientos()
    {
        return $this->hasMany(EnrolamientoCurso::class, 'id_instancia', 'id_instancia')
            ->where('id_curso', $this->id_curso);  // Asegúrate de que 'id_curso' esté presente en CursoInstancia
    }

    /*public function anexos()
        {
            return $this->belongsToMany(Anexo::class, 'relacion_curso_instancia_anexo', 'id_curso', 'formulario_id')
                        ->withPivot('id_instancia', 'tipo'); // Incluye columna adicional de la tabla intermedia
        }*/
    public function anexos()
    {
        return $this->belongsToMany(Anexo::class, 'relacion_curso_instancia_anexo', 'id_instancia', 'formulario_id')
            ->withPivot('id_curso'); // Esto asegura que 'id_curso' esté accesible
    }



}
