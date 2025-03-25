<?php

namespace App\Services;

use App\Models\CourseInstance;
use App\Models\CursoInstanciaAnexo;
use Exception;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Log;
use App\Models\Anexo;
use Illuminate\Support\Facades\DB;
use Throwable;

class CourseInstanceService
{
 
    public function getInstancesByCourse(int $cursoId): EloquentCollection
    {
        try {
            // Fetch all instances related to the course ID
            return CourseInstance::where('id_curso', $cursoId)->get();
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

            $quota = CourseInstance::where('id_instancia', $instanceId)
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
        $instance = CourseInstance::where('id', $instanceId)
            ->where('id_curso', $courseId)
            ->first();

        if ($instance->cupo > 0) {
            $instance->cupo = $instance->cupo - 1;
            $instance->save();
        }
        return $instance->cupo;
    }

    public function create(array $data): CourseInstance
    {
        try {
            return CourseInstance::create($data);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al crear la instancia' . $e->getMessage());
            throw $e;
        }
    }


    public function getInstanceById(int $id_instancia, int $cursoId): ?CourseInstance
    {
        try {

            $instancia = CourseInstance::with('anexos')
                ->where('id_instancia', $id_instancia)
                ->where('id_curso', $cursoId)
                ->first();

            return $instancia;
        } catch (Exception $e) {
            Log::error('Error en la clase: ' . get_class($this) . ' .Error al obtener la instancia por id: ' . $e->getMessage());
            throw $e;
        }
    }



    public function delete(CourseInstance $instancia, int $cursoId): ?bool
    {
        try {
            $instanciaToDelete = CourseInstance::where('id_instancia', $instancia->id_instancia)
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
            return CourseInstance::where('id_curso', $cursoId)
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
            $maxIdInstancia = CourseInstance::where('id_curso', $cursoId)
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
            return CourseInstance::where('id', $instanceId)
                ->value('id_curso');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener el curso mediante el Id de instancia' . $e->getMessage());
            throw $e;
        }

    }

    public function getDocumentation(int $instanciaId, int $cursoId)
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

    public function getDocumentationById(string $formulario_id, int $cursoId, int $instanciaId)
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


    public function getAnnexes()
    {
        return Anexo::all();
    }


    public function getannexByType(int $cursoId, int $instanciaId, string $tipo)
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
        $anexo = DB::table('anexos')
            ->where('formulario_id', $formularioId)
            ->first();

        return $anexo;
    }

    public function changeInstanceStatus(int $instanciaId, int $cursoId, string $bandera)
    {
        $instancia = CourseInstance::where('id_instancia', $instanciaId)
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


    public function getStartDate(int $cursoId, int $instanciaId)
    {
        return CourseInstance::where('id_curso', $cursoId)
            ->where('id_instancia', $instanciaId)
            ->value('fecha_inicio');
    }

    public function getCountAnnexesInstance(int $cursoId, int $instanciaId)
    {
        return CursoInstanciaAnexo::where('id_curso', $cursoId)
            ->where('id_instancia', $instanciaId)
            ->count() ?? 0;
    }

    public function get_room($cursoId, $instanciaId)
    {
        $instance = CourseInstance::where('id_curso', $cursoId)
            ->where('id_instancia', $instanciaId)
            ->first();

        if ($instance) {

            return $instance->lugar;
        }

        return null;
    }

    public function get_hour($cursoId, $instanciaId)
    {
        $instance = CourseInstance::where('id_curso', $cursoId)
            ->where('id_instancia', $instanciaId)
            ->first();

        if ($instance) {

            return $instance->hora;
        }

        return null;
    }


    public function getAllInstances(){
        return CourseInstance::all();
    }

    



}
