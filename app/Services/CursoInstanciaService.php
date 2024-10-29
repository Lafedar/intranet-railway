<?php

namespace App\Services;

use App\Models\CursoInstancia;
use Exception;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Log;

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

        $quota = CursoInstancia::where('id', $instanceId)
            ->where('id_curso', $courseId)
            ->value('cupo');
        return $quota;
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
            throw $e; // Lanza de nuevo la excepción para manejarla en el controlador
        }
    }

    public function getInstanceById(int $id): ?CursoInstancia
    {
        return CursoInstancia::find($id); 
    }

    public function delete(CursoInstancia $instancia) :?bool 
    {
        return $instancia->delete();
    }

    

}
