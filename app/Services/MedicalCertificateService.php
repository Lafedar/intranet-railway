<?php

namespace App\Services;



use Illuminate\Database\Eloquent\Collection;
use DB;
use App\Models\Empleado;
use App\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Models\Persona;
use log;
use App\Models\MedicalCertificate;


class MedicalCertificateService
{

    public function create($id, $titulo, $descripcion, $archivo)
    {
        try {
            if (!empty($id) && !empty($titulo) && !empty($archivo)) {
                return MedicalCertificate::on('mysql_write')->create([
                    'user_id' => $id,
                    'titulo' => $titulo,
                    'descripcion' => $descripcion,
                    'archivo' => $archivo,
                ]);
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al crear el certificado medico: ' . $e->getMessage());
            return false;
        }

    }
}
