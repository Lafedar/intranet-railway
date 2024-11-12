<?php

namespace App\Services;

use App\Models\Persona;
use App\Area;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;


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
        return Persona::with('area') 
            ->where('activo', 1) // Carga la relación 'area' de forma eficiente
            ->orderBy('nombre_p')
            ->get();
    }
    public function getPersonsByArea(array $areas)
    {
        return Persona::whereIn('area', $areas) 
            ->where('activo', 1)
            ->with('area')  
            ->get();
    }
    /**
     * Obtener una persona por su ID.
     *
     * @param int $id
     * @return Persona|null
     */
    public function getById(int $id): ?Persona
    {
        return Persona::find($id);
    }
    public function getByDni(int $dni)
{
    return Persona::where('dni', $dni)->first();  // Retorna la primera persona que tenga el dni proporcionado
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
        return $persona->delete();
    }

    


   
}
