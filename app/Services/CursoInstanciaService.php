<?php

namespace App\Services;

use App\Models\CursoInstancia;
use Exception;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Log;

use App\Models\Anexo;
use Illuminate\Support\Facades\DB;

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


    public function getInstanceById(int $id_instancia, int $cursoId): ?CursoInstancia
    {
        try {

            $instancia = CursoInstancia::with('anexos')
                ->where('id_instancia', $id_instancia)
                ->where('id_curso', $cursoId)
                ->first();

            return $instancia;
        } catch (Exception $e) {
            Log::error('Error en la clase: ' . get_class($this) . ' .Error al obtener la instancia por id: ' . $e->getMessage());
            throw $e;
        }
    }



    public function delete(CursoInstancia $instancia, int $cursoId): ?bool
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
    public function getCountInstances(int $cursoId)
    {
        try {
            return CursoInstancia::where('id_curso', $cursoId)
                ->distinct('id_instancia')
                ->count('id_instancia');
        } catch (Exception $e) {
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

    public function getIdCourseByInstanceId(int $instanceId)
    {
        try {
            return CursoInstancia::where('id', $instanceId)
                ->value('id_curso');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener el curso mediante el Id de instancia' . $e->getMessage());
            throw $e;
        }

    }
    public function getModalidad(int $instanciaId, int $cursoId)
    {
        return CursoInstancia::where('id_curso', $cursoId)
            ->where('id_instancia', $instanciaId)
            ->value('modalidad');
    }



    public function getDocumentacion(int $instanciaId, int $cursoId)
    {
        try {
            $anexos = DB::table('relacion_curso_instancia_anexo')
                ->join('anexos', 'relacion_curso_instancia_anexo.formulario_id', '=', 'anexos.formulario_id')
                ->where('relacion_curso_instancia_anexo.id_instancia', $instanciaId)
                ->where('relacion_curso_instancia_anexo.id_curso', $cursoId)
                ->select('anexos.*')
                ->get();



            return $anexos;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener la documentacion de la instancia' . $e->getMessage());
            throw $e;
        }

    }

    public function getDocumentacionById(string $formulario_id, int $cursoId, int $instanciaId)
    {

        $formulario = DB::table('relacion_curso_instancia_anexo')
            ->where('id_instancia', $instanciaId)
            ->where('id_curso', $cursoId)
            ->where('formulario_id', $formulario_id)
            ->value('formulario_id');


        if (!$formulario) {
            return null;
        }


        $anexo = DB::table('anexos')
            ->where('formulario_id', $formulario)
            ->first();

        return $anexo;
    }


    public function getAnexos()
    {
        return Anexo::all();
    }


    public function getAnexoByTipo(int $cursoId, int $instanciaId, string $tipo)
    {
        $formulario = DB::table('relacion_curso_instancia_anexo')
            ->where('id_instancia', $instanciaId)
            ->where('id_curso', $cursoId)
            ->where('tipo', $tipo)
            ->orderBy('id', 'desc')
            ->first();

        if ($formulario) {
            $formularioId = $formulario->formulario_id;
        } else {
            $formularioId = null;
        }

public function cambiarEstadoInstancia(int $instanciaId, int $cursoId, string $bandera){
    $instancia = CursoInstancia::where('id_instancia', $instanciaId)
                                ->where('id_curso', $cursoId)
                                ->first();

    if ($instancia && $bandera == "No") {
        $instancia->estado = 'No Activo';
        $instancia->save();     
        session()->flash('success', 'La instancia ha sido cerrada correctamente.');
    } elseif ($instancia && $bandera == "Si") {
        $instancia->estado = 'Activo';
        $instancia->save(); 
        session()->flash('success', 'La instancia ha sido abierta correctamente.');
    } else {
        session()->flash('error', 'No se encontró la instancia o el estado no es válido.');
    }
                            
    return redirect()->back();
}

        $anexo = DB::table('anexos')
            ->where('formulario_id', $formularioId)
            ->first();

        return $anexo;
    }



    public function validarAnexo(string $formulario_id, int $cursoId, int $instanciaId)
    {
        $formulario = DB::table('relacion_curso_instancia_anexo')
            ->where('id_instancia', $instanciaId)
            ->where('id_curso', $cursoId)
            ->where('formulario_id', $formulario_id)
            ->value('formulario_id');

    }

    public function getFechaInicio(int $cursoId, int $instanciaId)
    {
        return CursoInstancia::where('id_curso', $cursoId)
            ->where('id_instancia', $instanciaId)
            ->value('fecha_inicio');
    }



}
