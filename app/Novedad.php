<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Novedad extends Model
{
        use HasFactory;

        protected $table = 'novedades'; // Nombre de la tabla
        protected $fillable = [
                'titulo',
                'descripcion',
                'created_at',
                'imagen',
            ];
}
