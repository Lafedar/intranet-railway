<?php

namespace App\Services;

use App\Models\CursoInstancia;
use Exception;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Log;
use DB;
use App\Models\Anexo;

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
        } catch (Throwable $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error obteniendo las instancias del curso' . $e->getMessage());
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
               
            return $quota ?? 0; 
           
        } catch (Exception $e) {
            
            Log::error('Error in class: ' . get_class($this) . ' .Error en checkInstanceQuota' . $e->getMessage());
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
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al crear la instancia' . $e->getMessage());
            throw $e; 
        }
    }

    /*public function getInstanceById(int $id_instancia, int $cursoId): ?CursoInstancia
    {
        try {
            return CursoInstancia::with('anexos')
                                ->where('id_instancia', $id_instancia)
                                 ->where('id_curso', $cursoId)
                                 ->first(); 
        } catch (\Exception $e) {
            
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener la instancia' . $e->getMessage());
            throw $e; 
        }
    }*/
    public function getInstanceById(int $id_instancia, int $cursoId): ?CursoInstancia
{
    try {
        
        
        $instancia = CursoInstancia::with('anexos')
            ->where('id_instancia', $id_instancia)
            ->where('id_curso', $cursoId)
            ->first();

        
        /*if ($instancia) {
            foreach ($instancia->anexos as $anexo) {
                Log::info("Anexo: {$anexo->nombre}, Tipo: {$anexo->pivot->tipo}");
            }
        } else {
            Log::info("No se encontró la instancia con id_instancia: $id_instancia, id_curso: $cursoId");
        }*/

        return $instancia;
    } catch (\Exception $e) {
        Log::error('Error en la clase: ' . get_class($this) . ' .Error al obtener la instancia: ' . $e->getMessage());
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
    
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al eliminar la instancia' . $e->getMessage());
            throw $e;
        }
        
    }
    public function getCountInstances(int $cursoId){
           try{
                return CursoInstancia::where('id_curso', $cursoId)
                ->distinct('id_instancia') 
                ->count('id_instancia'); 
           }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al contar las instancias del curso' . $e->getMessage());
            throw $e;
           }
        
    }

    public function getMaxInstanceId(int $cursoId)
    {
        try {
            $maxIdInstancia = CursoInstancia::where('id_curso', $cursoId)
                                            ->max('id_instancia');
            
        
                                            if (!empty($maxIdInstancia)) {
                                                return $maxIdInstancia;
                                            } else {
                                                return 0;
                                            }
                                            

            
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener el id_instancia más grande del curso' . $e->getMessage());
            throw $e;
        }
    }

    public function getIdCourseByInstanceId(int $instanceId){
        try{
            return CursoInstancia::where('id', $instanceId)
        ->value('id_curso');
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener el curso mediante el Id de instancia' . $e->getMessage());
            throw $e;
        }
        
    }
    public function getModalidad(int $instanciaId, int $cursoId){
        return CursoInstancia::where('id_curso', $cursoId)
        ->where('id_instancia', $instanciaId)
        ->value('modalidad');
    }


    public function getDocumentacion(int $instanciaId, int $cursoId)
{
    try {
        
        // Verificar si hay anexos asociados a la instancia
        if ($instancia->anexos->isEmpty()) {
            return response()->json(['message' => 'No hay anexos relacionados con este curso.'], 404);
        }

        // Obtener los anexos
        $formularios = $instancia->anexos;

        // Retornar los anexos encontrados
        return response()->json(['formularios' => $formularios], 200);
        
    } catch (\Exception $e) {
        Log::error('Error en la clase: ' . get_class($this) . ' .Error al obtener la documentación: ' . $e->getMessage());
        return response()->json(['error' => 'Hubo un error al obtener los anexos.'], 500);
    }
}


    public function getDocumentacionById(string $formulario_id, int $cursoId, int $instanciaId) {
        $instancia = $this->getInstanceById($instanciaId, $cursoId);
   dd($instancia);
        $anexo = $instancia->anexos()->where('anexos.formulario_id', $formulario_id)->first();
    
        return $anexo;
    }
    

    public function getAnexos(){
        return Anexo::all();
    }
    

}
