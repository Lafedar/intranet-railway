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
        if (empty($data['tipo'])) {
            throw new \InvalidArgumentException('El tipo del curso es obligatorio.');
        }
       
    }
    
    public function getAll(): Collection
    {
        return Curso::with('areas')
        ->orderBy('created_at','desc')
            ->get();
    }

    public function getById($id): ?Curso
    {
        return Curso::with('areas') 
                ->find($id);  
    }

    public function getAreasByCourseId(int $cursoId)
    {
        $curso = Curso::with('areas')->find($cursoId);
        if (!$curso) {
            
            return null;  
        }

        return $curso->areas;
    }

    public function create(array $data)
    {
        
        $curso = Curso::create([
            'titulo' => $data['titulo'],
            'descripcion' => $data['descripcion'],
            'obligatorio' => $data['obligatorio'],
            'codigo' => $data['codigo'],
            'tipo' => $data['tipo'],
        ]);
    
        return $curso;
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
