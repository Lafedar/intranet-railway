<?php

namespace App\Services;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado;

class PersonaService
{
    private function validateData(array $data): void
    {
        try {
            if (empty($data['name'])) {
                Log::error('El nombre de la persona es obligatorio.');
                throw new \InvalidArgumentException('El nombre es obligatorio.');
            }
        }catch(Exception $e){
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error validating person data: ' . $e->getMessage());
        }

    }

    public function getById(int $id): ?Persona
    {
        try {
            return Persona::on('mysql_read')->find($id);
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error getting person by id: ' . $e->getMessage());
            throw $e;
        }

    }
    public function getByIdWrite(int $id): ?Persona
    {
        try {
            return Persona::on('mysql_write')->find($id);
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error getting person by id (write): ' . $e->getMessage());
            throw $e;
        }

    }
    public function getByDni(int $dni)
    {
        try {
            return Persona::on('mysql_read')->where('dni', $dni)->first();
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error getting person by dni: ' . $e->getMessage());
            throw $e;
        }

    }

    public function getByDniWrite($dni)
    {
        try {
            return Persona::on('mysql_write')->where('dni', $dni)->first();
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error getting person by dni (write): ' . $e->getMessage());
            throw $e;
        }
    }


    public function checkIfMailExists($mail)
    {
        try {
            return DB::connection('mysql_read')->table('personas')->where('correo', $mail)->exists();
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error checking if the email exists: ' . $e->getMessage());
            return false;
        }

    }

    public function createFromIntranetData(array $data)
    {
        try {
            $employee = new Empleado();
            $employee->id_p = $data['id'];
            $employee->nombre_p = $data['nombre_p'];
            $employee->apellido = $data['apellido'];
            $employee->dni = $data['dni'];
            $employee->interno = $data['interno'];
            $employee->correo = $data['correo'];
            $employee->fe_nac = $data['fe_nac'];
            $employee->fe_ing = $data['fe_ing'];
            $employee->area = $data['area'];
            $employee->turno = $data['turno'];
            $employee->activo = $data['activo'];
            $employee->jefe = $data['jefe'];
            $employee->usuario = null;
            $employee->legajo = $data['legajo'];
            $employee->save();

            return $employee;
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error synchronizing new person from Intranet: ' . $e->getMessage());
            return null;
        }
    }

    public function updateFromIntranetData($employee, array $data)
    {
        try {
            $employee->dni = $data['dni'];
            $employee->nombre_p = $data['nombre_p'];
            $employee->apellido = $data['apellido'];
            $employee->interno = $data['interno'];
            $employee->correo = $data['correo'];
            $employee->fe_nac = $data['fe_nac'];
            $employee->fe_ing = $data['fe_ing'];
            $employee->area = $data['area'];
            $employee->turno = $data['turno'];
            $employee->activo = $data['activo'];
            $employee->jefe = $data['jefe'];
            $employee->legajo = $data['legajo'];
            $employee->save();

            return true;
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error updating person data from Intranet: ' . $e->getMessage());
            return false;
        }
    }


}
