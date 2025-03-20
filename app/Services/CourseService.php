<?php

namespace App\Services;

use App\Models\Course;

use Illuminate\Database\Eloquent\Collection;
use Exception;
use Log;
use DB;
class CourseService
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
            
            $courses = DB::select('CALL GetAllCursosConAreas2()');
            
            $coursesCollection = collect($courses)->map(function ($curso) {
                return (array) $curso;  // Convierte cada objeto en un array asociativo
            });

            return $coursesCollection;

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting courses: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getById($id)
    {
        try {
           
            return Course::with('areas')  // Asegúrate de cargar 'anexos' aquí
                ->find($id);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting course by ID' . $e->getMessage());
            throw $e;
        }
    }

    public function getAreasByCourseId(int $courseId)
    {
        try {
            $course = Course::with('areas')->find($courseId);
            if (!$course) {

                return null;
            }

            return $course->areas;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting course areas' . $e->getMessage());
            throw $e;
        }

    }

    public function create(array $data)
    {
        try {
            $course = Course::create([
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'],
                'obligatorio' => $data['obligatorio'],
                'tipo' => $data['tipo'],
            ]);

            return $course;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error creating course' . $e->getMessage());
            throw $e;
        }

    }

    public function update(Course $course, array $data): bool
    {
        try {
            $this->validateData($data);
            return  $course->update($data);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating the course' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(Course $course): ?bool
    {
        try {
            return $course->delete();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error deleting course' . $e->getMessage());
            throw $e;
        }

    }


}
