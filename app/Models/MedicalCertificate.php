<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalCertificate extends Model
{
    protected $table = 'certificados_medicos';  
    protected $fillable = ['user_id', 'titulo', 'descripcion', 'archivo'];
}
