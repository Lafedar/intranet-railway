<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MedicationRequest extends Model
{
    use HasFactory;
    protected $table = 'solicitudes_medicamentos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'dni_persona',
        'estado',
        
    ];
}
