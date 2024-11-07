<?php

namespace App\Models;
use App\Area;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    //use HasFactory;

    protected $table = 'personas';
    protected $primaryKey = 'id_p';

    protected $fillable = [
        'id_p',
        'nombre_p',
        'apellido',
        'dni',
        'interno',
        'correo',
        'fe_nac',
        'fe_ing',
        'area',
        'turno',
        'jefe',
        'usuario',
        'activo',
        'legajo'
    ];

    public $timestamps = true;

    public function enrolamientos()
    {
        return $this->hasMany(EnrolamientoCurso::class, 'id_persona');
    }
    public function area()
    {
        
        return $this->belongsTo(Area::class, 'area', 'id_a');
    }
}
