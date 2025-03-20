<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use App\Services\PersonaService;
use App\Services\CourseInstanceService;
use App\Services\AreaService;
use App\Services\UserService;
use App\Services\AnnexedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\EnrolamientoCursoService;
use App\Models\Area;
use App\Models\Anexo;
use App\Models\Curso;
use Exception;
use DB;
use Barryvdh\Snappy\Facades\SnappyPdf;
use PDF;
use Normalizer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;


class CourseController extends Controller
{
    private CourseService $courseService;
    private CourseInstanceService $courseInstanceService;
    private EnrolamientoCursoService $enrolamientoCursoService;
    private AreaService $areaService;
    private PersonaService $personaService;
    private UserService $userService;

    private AnnexedService $annexedService;

    public function __construct(CourseService $courseService, CourseInstanceService $courseInstanceService, EnrolamientoCursoService $enrolamientoCursoService, AreaService $areaService, PersonaService $personaService, UserService $userService, AnnexedService $annexedService)
    {
        $this->courseService = $courseService;
        $this->courseInstanceService = $courseInstanceService;
        $this->enrolamientoCursoService = $enrolamientoCursoService;
        $this->areaService = $areaService;
        $this->personaService = $personaService;
        $this->userService = $userService;
        $this->annexedService = $annexedService;
    }

    public function listAll(Request $request)
    {
        try {
            $courseName = $request->input('courseName', '');
            $areaId = $request->input('area_id', null);
            $userDni = auth()->user()->dni;
            $personDni = $this->personaService->getByDni($userDni);



            if (auth()->user()->hasRole(['administrador', 'Gestor-cursos'])) {
                $coursesData = $this->courseService->getAll();
                $enroll_data=null;
                
            } else {
                $coursesData = $this->enrolamientoCursoService->getCoursesByUserDni($personDni->dni);
                $enroll_data = $this->enrolamientoCursoService->getAllCoursesInstancesById($personDni->id_p);
                
              
            }

            //filtros
            if ($courseName) {
                $coursesData = $coursesData->filter(function ($course) use ($courseName) {
                    $courseTitleWithoutAccents = $this->removeAccents($course['titulo']);
                    $courseNameWithoutAccents  = $this->removeAccents($courseName);

                    return str_contains(strtolower($courseTitleWithoutAccents ), strtolower($courseNameWithoutAccents));
                });
            }
            $areas = $this->areaService->getAll();
            if ($areaId && $areaId !== 'all') {
                $coursesData = $coursesData->filter(function ($course) use ($areaId) {
                    // Convertir la cadena de áreas en un arreglo de áreas
                    $areas = explode(',', $course['areas']);
                    
                    // Verificar si el areaId está en el arreglo de áreas
                    return in_array($areaId, $areas);
                });
            }

            if ($areaId && $areaId !== 'all') {
                $coursesData = $coursesData->filter(function ($course) use ($areaId) {
                    // Si el campo areas es un string, haz la comparación aquí
                    return strpos($course['areas'], $areaId) !== false; // Verifica si el areaId está presente en el string
                });
            }
            
            $areas = $this->areaService->getAll();
            $totalAreas = $areas->count();
            

            // Paginar los resultados
            $perPage = 10; // Número de cursos por página
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $coursesPaginated = new LengthAwarePaginator(
                $coursesData->forPage($currentPage, $perPage),
                $coursesData->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
           
       
            return view('cursos.index', compact('coursesPaginated',   'areas', 'areaId','courseName', 'personDni', 'enroll_data'));

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error displaying courses: ' . $e->getMessage());

            return redirect()->back();
        }
    }


    function removeAccents($string)
    {
        // Normaliza la cadena y elimina los acentos
        $string = Normalizer::normalize($string, Normalizer::FORM_D);
        // Elimina los caracteres no ASCII (acentos, diacríticos, etc.)
        $string = preg_replace('/\pM/u', '', $string);
        return $string;
    }


    public function show(int $id)
    {
        try {
            $course = $this->courseService->getById($id);
            if (!$course) {
                throw new Exception('El curso no fue encontrado.');
            }
            return view('cursos.show', compact('course'));
        } catch (Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            Log::error('Error in class: ' . get_class($this) . ' . Controller error while displaying course: ' . $e->getMessage());
            return redirect()->back();
        }
    }


    public function create()
    {
        try {
            $areas = $this->areaService->getAll();  // Recupera todas las áreas
            $annex = Anexo::select('formulario_id')->distinct()->get();

            return view('cursos.create', compact('areas', 'annex'));
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' . Controller error opening cursos.create view: ' . $e->getMessage());
            return redirect()->back();
        }

    }

    public function store(Request $request)
    {
        try {


            $validatedData = $request->validate([
                'titulo' => 'required|string|max:253',
                'descripcion' => 'nullable|string|max:253',
                'obligatorio' => 'required|boolean',
                'tipo' => 'required|string',
                'area' => 'required|array|min:1',
            ]);

            // Si no se ha seleccionado ninguna área, mostrar un error
            if (empty($validatedData['area'])) {
                return redirect()->back()->withErrors('Debe seleccionar al menos un área.');
            }


            $course = $this->courseService->create($validatedData);
            $course->areas()->attach($validatedData['area']);


            return redirect()->route('cursos.index')->with('success', 'Capacitación creada exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()->back()->withErrors($e->validator->errors());
        } catch (Exception $e) {

            return redirect()->back()->withErrors('There was a problem creating the course: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        try {
            $course = $this->courseService->getById($id);
            $areas = $this->areaService->getAll();


            if (!$course) {
                throw new Exception('El curso no fue encontrado.');
            }
            return view('cursos.edit', compact('course', 'areas'));
        } catch (Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            Log::error('Error in class: ' . get_class($this) . ' . Controller error when opening cursos.edit view: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, int $id)
    {
        try {

            $course = $this->courseService->getById($id);

            if (!$course) {
                return redirect()->route('cursos.index')->withErrors('El curso no fue encontrado.');
            }

            $validatedData = $request->validate([
                'titulo' => 'required|string|max:100',
                'descripcion' => 'nullable|string|max:65530',
                'obligatorio' => 'required|boolean',
                'tipo' => 'required|string',
                'area' => 'nullable|array',
            ]);


            $course->update($validatedData);

            if (!empty($validatedData['area'])) {
                $course->areas()->sync($validatedData['area']);
            } else {

                $course->areas()->detach();
            }


            return redirect()->route('cursos.index')->with('success', 'Capacitación actualizada exitosamente.');

        } catch (Exception $e) {
            session()->flash('error', 'Error al actualizar el curso: ' . $e->getMessage());
            Log::error('Error en la clase: ' . get_class($this) . '. Error updating the course: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al actualizar el curso.');
        }
    }

    public function destroy(int $id)
    {
        try {
            $course = $this->courseService->getById($id);
            if (!$course) {
                throw new Exception('El curso no fue encontrado.');
            }


            $instances = $this->courseInstanceService->getInstancesByCourse($id);


            foreach ($instances as $instance) {
                $this->enrolamientoCursoService->deleteByInstanceId($course->id, $instance->id);
                $this->courseInstanceService->delete($instance, $course->id);
            }

            $this->annexedService->deleteAnnexCourseInstanceByCourseId($course->id);
            $this->courseService->delete($course);

            return redirect()->route('cursos.index')->with('success', 'El curso y sus instancias fueron eliminados exitosamente.');
        } catch (Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            session()->flash('error', 'Error al eliminar el curso: ' . $e->getMessage());
            Log::error('Error in class: ' . get_class($this) . ' . Error deleting course: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al eliminar el curso.');
        }
    }


    public function getRegistered(int $courseId)
    {
        try {
            $registered = $this->enrolamientoCursoService->getPersonsByCourseId($courseId);
            $course = $this->courseService->getById($courseId);
            return view('courses.registeredptos', compact(' $registered', 'course'));
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting course registrations: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener los incriptos del curso.');
        }

    }


    public function showCourse($courseId)
    {
        $course = $this->courseService->getById($courseId);
        $areas = $this->courseService->getAreasByCourseId(($courseId));
        return view('courses.showCourse', compact('course', 'areas'));
    }



    public function getCourses(int $userId)
    {
        $coursesWithDetails = $this->enrolamientoCursoService->getCursos($userId);
        $person = $this->personaService->getById($userId);
       
        return view('empleado.cursos', compact('coursesWithDetails', 'person'));
    }

    public function getCoursesByDni(int $dni)
    {
        $person = $this->personaService->getByDni($dni);
        $coursesWithDetails = $this->enrolamientoCursoService->getCursosByDni($dni);

        return view('empleado.cursos', compact('coursesWithDetails', 'person'));
    }

}



