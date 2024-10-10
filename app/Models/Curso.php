<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
   //use HasFactory;
   protected $table = 'cursos';
   protected $fillable = ['codigo','titulo', 'descripcion', 'creado', 'obligatorio','tipo'];

   //controla si Laravel gestiona automáticamente los campos created_at y updated_at de la tabla Curso
   public $timestamps = true;

   public function instancias()
   {
       return $this->hasMany(CursoInstancia::class, 'id_curso');
   }
}
