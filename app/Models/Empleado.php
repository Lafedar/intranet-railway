<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    //use HasFactory;

    protected $table = 'personas';

    //el campo id de la tabla cursos_instancia lo reconoce Laravel automaticamente como primary key de la tabla
    protected $fillable = [
        'legajo',
        'id_persona',
        'interno',
        'fecha_ingreso',
        'fecha_egreso',
        'id_area',
        'id_turno',
        'jefe',
        'id_usuario',
        'activo'
    ];

    // Indicar a Laravel que los campos son de tipo fecha
    protected $dates = [
        'fecha_ingreso',
        'fecha_egreso',
    ];

    public $timestamps = true;

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }


}
