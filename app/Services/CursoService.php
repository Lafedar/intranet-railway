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
        try{
            return Curso::with('areas')
            ->orderBy('created_at','desc')
                ->get();
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener los cursos' . $e->getMessage());
            throw $e;
        }
        
    }

    public function getById($id): ?Curso
{
    try {
        return Curso::with('areas', 'anexos')  // Asegúrate de cargar 'anexos' aquí
            ->find($id);
    } catch (Exception $e) {
        Log::error('Error in class: ' . get_class($this) . ' .Error al obtener el curso por Id' . $e->getMessage());
        throw $e;
    }
}

    public function getAreasByCourseId(int $cursoId)
    {
        try{
            $curso = Curso::with('areas')->find($cursoId);
            if (!$curso) {
                
                return null;  
            }
    
            return $curso->areas;
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener las areas del curso' . $e->getMessage());
            throw $e;
        }
        
    }

    public function create(array $data)
    {
        try{
            $curso = Curso::create([
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'],
                'obligatorio' => $data['obligatorio'],
                'codigo' => $data['codigo'],
                'tipo' => $data['tipo'],
            ]);
        
            return $curso;
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al crear el curso' . $e->getMessage());
            throw $e;
        }
        
    }

    public function update(Curso $curso, array $data): bool
    {
        try{
            $this->validateData($data);
            return $curso->update($data);
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al actualizar el curso' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(Curso $curso) :?bool 
    {
        try{
            return $curso->delete();
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al eliminar el curso' . $e->getMessage());
            throw $e;
        }
        
    }
    
    public function getDocumentacion(int $cursoId)
    {
        $curso = $this->getById($cursoId);
    
        if ($curso->anexos->isEmpty()) {
            dd('No hay anexos relacionados con este curso.');
        }
    
        $formularios = $curso->anexos;
    
        return $formularios;
    }

    public function getDocumentacionById(string $formulario_id, int $cursoId) {
        $curso = $this->getById($cursoId);
    
        $anexo = $curso->anexos()->where('anexos.formulario_id', $formulario_id)->first();
    
        return $anexo;
    }
}
