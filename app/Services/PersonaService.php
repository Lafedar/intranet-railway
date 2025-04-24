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

    /**
     * Obtener todas las personas.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): Collection
    {
        try {
            return Persona::with('area')
                ->where('activo', 1)
                ->where('nombre_p', '<>', 'Administrador')
                ->orderBy('apellido', 'asc')
                ->get();


        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener todas las personas' . $e->getMessage());
            throw $e;
        }

    }
    public function getPersonsByArea(array $areas)
    {
        try {

            if (in_array('tod', $areas)) {
                return Persona::where('activo', 1)
                    ->where('nombre_p', '<>', 'Administrador')
                    ->orderBy('apellido')
                    ->with('area')
                    ->get();
            }


            return Persona::whereIn('area', $areas)
                ->where('activo', 1)
                ->where('nombre_p', '<>', 'Administrador')
                ->orderBy('apellido')
                ->with('area')
                ->get();

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener todas las personas de las areas' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener una persona por su ID.
     *
     * @param int $id
     * @return Persona|null
     */
    public function getById(int $id): ?Persona
    {
        try {
            return Persona::find($id);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener la persona por Id ' . $e->getMessage());
            throw $e;
        }

    }
    public function getByDni(int $dni)
    {
        try {
            return Persona::where('dni', $dni)->first();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener la persona por Dni ' . $e->getMessage());
            throw $e;
        }

    }
    /**
     * Crear una nueva persona.
     *
     * @param array $data
     * @return Persona
     */
    public function create(array $data): Persona
    {

        $this->validateData($data);
        return Persona::create($data);
    }

    /**
     * Actualizar una persona existente.
     *
     * @param Persona $persona
     * @param array $data
     * @return bool
     */
    public function update(Persona $persona, array $data): bool
    {
        $this->validateData($data);
        return $persona->update($data);
    }

    /**
     * Eliminar una persona por su ID.
     *
     * @param Persona $persona
     * @return bool|null
     */
    public function delete(Persona $persona): ?bool
    {
        try {
            return $persona->delete();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al eliminar la persona' . $e->getMessage());
            throw $e;
        }

    }

    public function getAreaByDni(int $dni)
    {
        $persona = Persona::where('dni', $dni)->first();

        // Verificar si se encontró la persona
        if ($persona) {
            // Retornar el valor de la columna 'area'
            return $persona->area;
        } else {
            // Si no se encontró la persona, retornar null o manejarlo según necesites
            return null;
        }
    }



    public function updateUserByDni(int $dni, int $id_user)
    {
        try {
            // Buscar la persona por DNI
            $persona = Persona::where('dni', $dni)->first();

            if (!$persona) {
                return false;
            }

            $persona->usuario = $id_user;
            $persona->save();

            return true;

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al actualizar el usuario por Dni' . $e->getMessage());
            return false;
        }
    }
    // En PersonaRepository.php
    public function getByDnis(array $dnis)
    {
        return Persona::whereIn('dni', $dnis)->get();
    }



}
