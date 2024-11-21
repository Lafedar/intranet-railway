<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CursoInstancia;

class Anexo extends Model
{
    use HasFactory;
    protected $table = 'anexos';
    protected $primaryKey = 'formulario_id';
    

    // Si formulario_id no es autoincremental, asegúrate de que esta opción esté desactivada
    public $incrementing = false;
    
    // Indica el tipo de la clave primaria, si es necesario (en caso de que sea string, por ejemplo)
    protected $keyType = 'string';
    public function cursosInstancias()
    {
        return $this->belongsToMany(CursoInstancia::class, 'relacion_curso_instancia_anexo', 'formulario_id', 'id_curso')
                    ->withPivot('id_instancia', 'tipo');
    }
}
