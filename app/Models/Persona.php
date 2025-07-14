<?php

namespace App\Models;
use App\Models\Area;
;
use Empresa;
use Illuminate\Support\Facades\DB;
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

    public function traerPersonas()
    {
        return DB::table('personas')->orderBy('personas.nombre_p', 'asc')->get();
    }
  


}
