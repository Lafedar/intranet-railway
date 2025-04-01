<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use App\Services\PersonaService;
use App\Services\CourseInstanceService;
use App\Services\AreaService;
use App\Services\UserService;
use App\Services\AnnexService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\EnrolamientoCursoService;
use App\Models\Anexo;
use Exception;
use Normalizer;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseController extends Controller
{
    private CourseService $courseService;
    private CourseInstanceService $courseInstanceService;
    private EnrolamientoCursoService $enrolamientoCursoService;
    private AreaService $areaService;
    private PersonaService $personaService;
    private UserService $userService;

    private AnnexService $annexService;

    public function __construct(CourseService $courseService, CourseInstanceService $courseInstanceService, EnrolamientoCursoService $enrolamientoCursoService, AreaService $areaService, PersonaService $personaService, UserService $userService, AnnexService $annexService)
    {
        $this->courseService = $courseService;
        $this->courseInstanceService = $courseInstanceService;
        $this->enrolamientoCursoService = $enrolamientoCursoService;
        $this->areaService = $areaService;
        $this->personaService = $personaService;
        $this->userService = $userService;
        $this->annexService = $annexService;
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
                $enroll_data = null;

            } else {
                $coursesData = $this->enrolamientoCursoService->getCoursesByUserDni($personDni->dni);
                $enroll_data = $this->enrolamientoCursoService->getAllCoursesInstancesById($personDni->id_p);


            }

            //filtros
            if ($courseName) {
                $coursesData = $coursesData->filter(function ($course) use ($courseName) {
                    $courseTitleWithoutAccents = $this->removeAccents($course['titulo']);
                    $courseNameWithoutAccents = $this->removeAccents($courseName);

                    return str_contains(strtolower($courseTitleWithoutAccents), strtolower($courseNameWithoutAccents));
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


            return view('cursos.index', compact('coursesPaginated', 'areas', 'areaId', 'courseName', 'personDni', 'enroll_data'));

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

    public function showCreateCourseForm()
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

    public function saveNewCourse(Request $request)
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

    public function showEditCourseForm(int $id)
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

    public function updateDetails(Request $request, int $id)
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

    public function deleteCourseWithAssociations(int $id)
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

            $this->annexService->deleteAnnexCourseInstanceByCourseId($course->id);
            $this->courseService->delete($course);

            return redirect()->route('cursos.index')->with('success', 'El curso y sus instancias fueron eliminados exitosamente.');
        } catch (Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            session()->flash('error', 'Error al eliminar el curso: ' . $e->getMessage());
            Log::error('Error in class: ' . get_class($this) . ' . Error deleting course: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al eliminar el curso.');
        }
    }

    public function displayCourseDetails($courseId)
    {
        $course = $this->courseService->getById($courseId);
        $areas = $this->courseService->getAreasByCourseId(($courseId));
        return view('cursos.verCurso', compact('course', 'areas'));
    }
    public function displayCourseDetailsJson($courseId)
    {
        $course = $this->courseService->getById($courseId);
        $areas = $this->courseService->getAreasByCourseId($courseId);

        // Devolver los datos como JSON para la petición AJAX
        return response()->json([
            'course' => $course,
            'areas' => $areas
        ]);
    }


    public function listUserCourses(int $userId)
    {
        $coursesWithDetails = $this->enrolamientoCursoService->getCursos($userId);
        $person = $this->personaService->getById($userId);

        return view('empleado.cursos', compact('coursesWithDetails', 'person'));
    }

    public function listUserCoursesByDni(int $dni)
    {
        $person = $this->personaService->getByDni($dni);
        $coursesWithDetails = $this->enrolamientoCursoService->getCursosByDni($dni);

        return view('empleado.cursos', compact('coursesWithDetails', 'person'));
    }
    public function showCreateNewOptimizedCourse()
    {
        $areas = $this->areaService->getAll();
        $persons = $this->personaService->getAll();
        $persons = $this->personaService->getAll();

        $persons->each(function ($person) {
            $person->area = $this->areaService->getAreaById($person->area);
        });
        $courses = $this->courseService->getAll();

        return view('cursos.createOptimized', compact('areas', 'persons', 'courses'));
    }


    public function saveNewOptimizedCourse(Request $request)
    {
        try {

            $flag = $request->input('flag2');
            $courseId = $request->input('course');

            if($flag == 0){
                $validatedData = $request->validate([
                    'titulo' => 'required|string|max:253',
                    'area' => 'required|array|min:1',
                ]);
    
                $validatedData['obligatorio'] = 1;
                $validatedData['tipo'] = 'Interna';
                $validatedData['descripcion'] = null;
                // Si no se ha seleccionado ninguna área, mostrar un error
                if (empty($validatedData['area'])) {
                    return redirect()->back()->withErrors('Debe seleccionar al menos un área.');
                }
    
    
                $course = $this->courseService->create($validatedData);
                $course->areas()->attach($validatedData['area']);
    
    
                return redirect()->route('cursos.createOptimized')->with('success', 'Capacitación creada exitosamente.');
            }elseif($flag == 1){
                $validatedData = $request->validate([
                    'titulo' => 'required|string|max:253',
                    'area' => 'required|array|min:1',
                ]);
                //dd($validatedData);
                // Si no se ha seleccionado ninguna área, mostrar un error
                if (empty($validatedData['area'])) {
                    return redirect()->back()->withErrors('Debe seleccionar al menos un área.');
                }
    
                $course=$this->courseService->getById($courseId);
               
                $course->areas()->attach($validatedData['area']);
    
    
                return redirect()->route('cursos.createOptimized')->with('success', 'Capacitación actualizada exitosamente.');
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()->back()->withErrors($e->validator->errors());
        } catch (Exception $e) {

            return redirect()->back()->withErrors('There was a problem creating the course: ' . $e->getMessage());
        }
    }

    


   
}


