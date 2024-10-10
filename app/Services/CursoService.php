<?php

namespace App\Services;

use App\Models\Curso;
use Illuminate\Database\Eloquent\Collection; 

class CursoService
{
    private function validateData(array $data): void
    {
        if (empty($data['nombre'])) {
            throw new \InvalidArgumentException('El nombre del curso es obligatorio.');
        }
    }
    
    public function getAll(): Collection
    {
        return Curso::all();
    }

    public function getById($id): ?Curso
    {
        return Curso::find($id);
    }

    public function create(array $data): Curso
    {
        $this->validateData($data);
        return Curso::create($data);
    }

    public function update(Curso $curso, array $data): bool
    {
        $this->validateData($data);
        return $curso->update($data);
    }

    public function delete(Curso $curso) :?bool 
    {
        return $curso->delete();
    }

    
}
