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
    

    // Si formulario_id no es autoincremental
    public $incrementing = false;
    
    // Indica el tipo de la clave primaria, si es necesario (en caso de que sea string, por ejemplo)
    protected $keyType = 'string';


    public function instancias()
    {
        return $this->belongsToMany(CursoInstancia::class, 'relacion_curso_instancia_anexo', 'id_curso', 'id_instancia')
                    ->withPivot('formulario_id');
    }

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'relacion_curso_instancia_anexo', 'id_instancia', 'id_curso')
                    ->withPivot('formulario_id');
    }
}
