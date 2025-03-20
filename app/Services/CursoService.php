<?php

namespace App\Services;

use App\Models\Curso;
use App\Models\CursoInstancia;
use Illuminate\Database\Eloquent\Collection;
use Exception;
use Log;
use DB;
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
    public function getAll()
    {
        try {
            // Ejecutar el procedimiento almacenado para obtener los cursos
            $cursos = DB::select('CALL GetAllCursosConAreas2()');
            
            // Crear una colección de cursos a partir del resultado del procedimiento almacenado
            $cursosCollection = collect($cursos);

            return $cursosCollection;

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener los cursos: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getById($id)
    {
        try {
           
            return Curso::with('areas')  // Asegúrate de cargar 'anexos' aquí
                ->find($id);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener el curso por Id' . $e->getMessage());
            throw $e;
        }
    }

    public function getAreasByCourseId(int $cursoId)
    {
        try {
            $curso = Curso::with('areas')->find($cursoId);
            if (!$curso) {

                return null;
            }

            return $curso->areas;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener las areas del curso' . $e->getMessage());
            throw $e;
        }

    }

    public function create(array $data)
    {
        try {
            $curso = Curso::create([
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'],
                'obligatorio' => $data['obligatorio'],
                'tipo' => $data['tipo'],
            ]);

            return $curso;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al crear el curso' . $e->getMessage());
            throw $e;
        }

    }

    public function update(Curso $curso, array $data): bool
    {
        try {
            $this->validateData($data);
            return $curso->update($data);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al actualizar el curso' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(Curso $curso): ?bool
    {
        try {
            return $curso->delete();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al eliminar el curso' . $e->getMessage());
            throw $e;
        }

    }


}
