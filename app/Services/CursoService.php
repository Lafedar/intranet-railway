<?php

namespace App\Services;

use App\Models\Curso;
use App\Models\CursoInstancia;
use Illuminate\Database\Eloquent\Collection; 

class CursoService
{
   
    private function validateData(array $data): void
    {
        if (empty($data['titulo'])) {
            throw new \InvalidArgumentException('El nombre del curso es obligatorio.');
        }
        if (empty($data['obligatorio'])) {
            throw new \InvalidArgumentException('La obligatoriedad del curso es necesaria.');
        }
        if (empty($data['tipo'])) {
            throw new \InvalidArgumentException('El tipo del curso es obligatorio.');
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
