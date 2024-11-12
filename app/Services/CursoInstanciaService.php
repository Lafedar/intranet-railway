<?php

namespace App\Services;

use App\Models\CursoInstancia;
use Exception;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Log;
use DB;

class CursoInstanciaService
{
    /**
     * Get all instances of a specific course by the course ID.
     *
     * @param int $cursoId
     * @return \Illuminate\Database\Eloquent\Collection|CursoInstancia[]
     */
    public function getInstancesByCourse(int $cursoId): EloquentCollection
    {
        try {
            // Fetch all instances related to the course ID
            return CursoInstancia::where('id_curso', $cursoId)->get();
        } catch (\Throwable $e) {
            Log::error('Error obteniendo instancias del curso: ' . $e->getMessage());
            throw new Exception('Error al buscar las instancias.');
        }
    }

    public function checkAvailability(EloquentCollection $cursoInstancias): EloquentCollection
    {
        $availability = $cursoInstancias->map(function ($instancia) {
            return [
                'idInstance' => $instancia->id,
                'enabled' => $instancia->estado === 'Activo'
            ];
        });
        return new EloquentCollection($availability);
    }

    public function checkInstanceQuota($courseId, $instanceId): int
    {
        try {
            
            $quota = CursoInstancia::where('id_instancia', $instanceId)
                ->where('id_curso', $courseId)
                ->value('cupo');
               
            // Si $quota es null, se asigna 0, de lo contrario, se devuelve el valor de $quota
            return $quota ?? 0; 
           
        } catch (Exception $e) {
            
            Log::error('Error en checkInstanceQuota: ' . $e->getMessage());
            
            return 0; 
        }
        
    }

    public function decrementQuota($courseId, $instanceId): int
    {
        $instance = CursoInstancia::where('id', $instanceId)
            ->where('id_curso', $courseId)
            ->first();
            
        if ($instance->cupo > 0) {
            $instance->cupo = $instance->cupo - 1;
            $instance->save();
        }  
        return $instance->cupo;
    }

    public function create(array $data): CursoInstancia
    {
        try {
            return CursoInstancia::create($data);
        } catch (\Exception $e) {
            \Log::error('Error al crear la instancia: ' . $e->getMessage());
            throw $e; 
        }
    }

    public function getInstanceById(int $id_instancia, int $cursoId): ?CursoInstancia
    {
        try {
            return CursoInstancia::where('id_instancia', $id_instancia)
                                 ->where('id_curso', $cursoId)
                                 ->first(); 
        } catch (\Exception $e) {
            // Manejo de errores
            \Log::error('Error al obtener la instancia: ' . $e->getMessage());
            throw $e; 
        }
    }
    

    public function delete(CursoInstancia $instancia, int $cursoId) :?bool 
    {
        try {
            $instanciaToDelete = CursoInstancia::where('id_instancia', $instancia->id_instancia)
                                               ->where('id_curso', $cursoId)
                                               ->first();
    
            if ($instanciaToDelete) {
                return $instanciaToDelete->delete();
            }
            return false;
    
        } catch (\Exception $e) {
            \Log::error('Error al eliminar la instancia: ' . $e->getMessage());
            throw $e;
        }
        
    }
    public function getCountInstances(int $cursoId){
           
        return CursoInstancia::where('id_curso', $cursoId)
        ->distinct('id_instancia') 
        ->count('id_instancia'); 
    }

    public function getIdCourseByInstanceId(int $instanceId){
        return CursoInstancia::where('id', $instanceId)
        ->value('id_curso');
        
    }
    
  
    
    

}
