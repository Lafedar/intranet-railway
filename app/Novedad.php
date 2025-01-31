<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Like;

class Novedad extends Model
{
        use HasFactory;

        protected $table = 'novedades'; // Nombre de la tabla
        protected $fillable = [
                'titulo',
                'descripcion',
                'portada',
                'imagenes_sec',

        ];
        public function likes()
        {
                return $this->hasMany(Like::class); 
        }
}
