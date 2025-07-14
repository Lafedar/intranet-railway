<?php

namespace App\Services;



use Illuminate\Database\Eloquent\Collection;
use App\Models\Empleado;
use App\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Models\Persona;
use Illuminate\Support\Facades\Log;
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
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error creating a medical certificate: ' . $e->getMessage());
            return false;
        }

    }

    public function delete($id)
    {
        try {
            if ($id != null) {
                MedicalCertificate::on('mysql_write')->where('id', $id)->delete();
                return true;
            }
            return false;

        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error deleting a medical certificate: ' . $e->getMessage());
            return false;
        }

    }
}
