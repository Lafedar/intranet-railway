<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoInstanciaAnexo extends Model
{
    use HasFactory;
    protected $table = 'relacion_curso_instancia_anexo'; 
    protected $fillable = ['id_curso', 'id_instancia', 'formulario_id'];
    public $timestamps = false;

    // Relación con CursosInstancia
    public function cursoInstancia()
    {
        return $this->belongsTo(CursoInstancia::class, 'id_curso');
    }

    // Relación con Anexos
    public function anexo()
    {
        return $this->belongsTo(Anexo::class, 'formulario_id');
    }
}
