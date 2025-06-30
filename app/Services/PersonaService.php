<?php

namespace App\Services;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Exception;
use DB;

class PersonaService
{
    private function validateData(array $data): void
    {
        // Agregar la validación requerida para los datos de la persona
        if (empty($data['name'])) {
            Log::error('El nombre de la persona es obligatorio.');
            throw new \InvalidArgumentException('El nombre es obligatorio.');
        }
    }

    public function getById(int $id): ?Persona
    {
        try {
            return Persona::on('mysql_read')->find($id);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener la persona por Id ' . $e->getMessage());
            throw $e;
        }

    }
    public function getByIdWrite(int $id): ?Persona
    {
        try {
            return Persona::on('mysql_write')->find($id);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener la persona por Id ' . $e->getMessage());
            throw $e;
        }

    }
    public function getByDni(int $dni)
    {
        try {
            return Persona::on('mysql_read')->where('dni', $dni)->first();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener la persona por Dni ' . $e->getMessage());
            throw $e;
        }

    }

    public function getByDniWrite($dni)
    {
        try {
            return Persona::on('mysql_write')->where('dni', $dni)->first();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener la persona por Dni en escritura ' . $e->getMessage());
            throw $e;
        }
    }


    public function checkIfMailExists($mail)
    {
        try {
            return DB::connection('mysql_read')->table('personas')->where('correo', $mail)->exists();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al verificar si el correo existe' . $e->getMessage());
            return false;
        }

    }


}
