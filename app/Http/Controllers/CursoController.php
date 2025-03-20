<?php

namespace App\Http\Controllers;

use App\Services\CursoService;
use App\Services\PersonaService;
use App\Services\CourseInstanceService;
use App\Services\AreaService;
use App\Services\UserService;
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


class CursoController extends Controller
{
    private CursoService $cursoService;
    private CourseInstanceService $courseInstanceService;
    private EnrolamientoCursoService $enrolamientoCursoService;
    private AreaService $areaService;
    private PersonaService $personaService;
    private UserService $userService;

    public function __construct(CursoService $cursoService, CourseInstanceService $courseInstanceService, EnrolamientoCursoService $enrolamientoCursoService, AreaService $areaService, PersonaService $personaService, UserService $userService)
    {
        $this->cursoService = $cursoService;
        $this->courseInstanceService = $courseInstanceService;
        $this->enrolamientoCursoService = $enrolamientoCursoService;
        $this->areaService = $areaService;
        $this->personaService = $personaService;
        $this->userService = $userService;
    }

    /**
     * Mostrar una lista de todos los cursos.
     *
     * @return \Illuminate\View\View
     */


    public function listAll(Request $request)
    {
        try {
            $nombreCurso = $request->input('nombre_curso', '');
            $areaId = $request->input('area_id', null);
            $userDni = auth()->user()->dni;
            $personaDni = $this->personaService->getByDni($userDni);



            if (auth()->user()->hasRole(['administrador', 'Gestor-cursos'])) {
                $cursosData = $this->cursoService->getAll();
                $enroll_data=null;
                
            } else {
                $cursosData = $this->enrolamientoCursoService->getCoursesByUserDni($personaDni->dni);
                $enroll_data = $this->enrolamientoCursoService->getAllCoursesInstancesById($personaDni->id_p);
                
              
            }

            //filtros
            if ($nombreCurso) {
                $cursosData = $cursosData->filter(function ($curso) use ($nombreCurso) {
                    $tituloCursoSinTildes = $this->removeAccents($curso['titulo']);
                    $nombreCursoSinTildes = $this->removeAccents($nombreCurso);

                    return str_contains(strtolower($tituloCursoSinTildes), strtolower($nombreCursoSinTildes));
                });
            }
            $areas = $this->areaService->getAll();
            if ($areaId && $areaId !== 'all') {
                $cursosData = $cursosData->filter(function ($curso) use ($areaId) {
                    // Convertir la cadena de áreas en un arreglo de áreas
                    $areas = explode(',', $curso['areas']);
                    
                    // Verificar si el areaId está en el arreglo de áreas
                    return in_array($areaId, $areas);
                });
            }

            if ($areaId && $areaId !== 'all') {
                $cursosData = $cursosData->filter(function ($curso) use ($areaId) {
                    // Si el campo areas es un string, haz la comparación aquí
                    return strpos($curso['areas'], $areaId) !== false; // Verifica si el areaId está presente en el string
                });
            }

    
            //$cursosData = $cursosData->sortByDesc('curso.created_at');
            $areas = $this->areaService->getAll();
            $totalAreas = $areas->count();
            

            // Paginar los resultados
            $perPage = 10; // Número de cursos por página
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $cursosPaginated = new LengthAwarePaginator(
                $cursosData->forPage($currentPage, $perPage),
                $cursosData->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
           
           //$cursosPaginated = $cursosData;
       
            return view('cursos.index', compact('cursosPaginated',   'areas', 'areaId','nombreCurso', 'personaDni', 'enroll_data'));

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al mostrar los cursos: ' . $e->getMessage());

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


    /**
     * Mostrar los detalles de un curso específico.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(int $id)
    {
        try {
            $curso = $this->cursoService->getById($id);
            if (!$curso) {
                throw new Exception('El curso no fue encontrado.');
            }
            return view('cursos.show', compact('curso'));
        } catch (Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al mostrar el curso: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Mostrar el formulario para crear un nuevo curso.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $areas = $this->areaService->getAll();  // Recupera todas las áreas
            $anexos = Anexo::select('formulario_id')->distinct()->get();

            return view('cursos.create', compact('areas', 'anexos'));
        } catch (Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al abrir la vista cursos.create: ' . $e->getMessage());
            return redirect()->back();
        }

    }

    /**
     * Almacenar un nuevo curso en la base de datos.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */


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


            $curso = $this->cursoService->create($validatedData);
            $curso->areas()->attach($validatedData['area']);


            return redirect()->route('cursos.index')->with('success', 'Capacitación creada exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()->back()->withErrors($e->validator->errors());
        } catch (Exception $e) {

            return redirect()->back()->withErrors('Hubo un problema al crear el curso: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar el formulario para editar un curso existente.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        try {
            $curso = $this->cursoService->getById($id);
            $areas = $this->areaService->getAll();


            if (!$curso) {
                throw new Exception('El curso no fue encontrado.');
            }
            return view('cursos.edit', compact('curso', 'areas'));
        } catch (Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al abrir la vista cursos.edit: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Actualizar un curso en la base de datos.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */


    public function update(Request $request, int $id)
    {
        try {

            $curso = $this->cursoService->getById($id);

            if (!$curso) {
                return redirect()->route('cursos.index')->withErrors('El curso no fue encontrado.');
            }

            $validatedData = $request->validate([
                'titulo' => 'required|string|max:100',
                'descripcion' => 'nullable|string|max:65530',
                'obligatorio' => 'required|boolean',
                'tipo' => 'required|string',
                'area' => 'nullable|array',
            ]);


            $curso->update($validatedData);

            if (!empty($validatedData['area'])) {
                $curso->areas()->sync($validatedData['area']);
            } else {

                $curso->areas()->detach();
            }


            return redirect()->route('cursos.index')->with('success', 'Capacitación actualizada exitosamente.');

        } catch (Exception $e) {
            session()->flash('error', 'Error al actualizar el curso: ' . $e->getMessage());
            Log::error('Error en la clase: ' . get_class($this) . '. Error al actualizar el curso: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al actualizar el curso.');
        }
    }










    /**
     * Eliminar un curso de la base de datos.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */



    public function destroy(int $id)
    {
        try {
            $curso = $this->cursoService->getById($id);
            if (!$curso) {
                throw new Exception('El curso no fue encontrado.');
            }


            $instancias = $this->courseInstanceService->getInstancesByCourse($id);


            foreach ($instancias as $instancia) {
                $this->enrolamientoCursoService->deleteByInstanceId($curso->id, $instancia->id);
                $this->courseInstanceService->delete($instancia, $curso->id);
            }
            DB::table('relacion_curso_instancia_anexo')
                ->where('id_curso', $id)
                ->delete();


            $this->cursoService->delete($curso);
            return redirect()->route('cursos.index')->with('success', 'El curso y sus instancias fueron eliminados exitosamente.');
        } catch (Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            session()->flash('error', 'Error al eliminar el curso: ' . $e->getMessage());
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al eliminar el curso: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al eliminar el curso.');
        }
    }



    public function getInscriptos(int $cursoId)
    {
        try {
            $inscritos = $this->enrolamientoCursoService->getPersonsByCourseId($cursoId);
            $curso = $this->cursoService->getById($cursoId);
            return view('cursos.inscriptos', compact('inscritos', 'curso'));
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al obtener los incriptos del curso: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener los incriptos del curso.');
        }

    }


    public function verCurso($cursoId)
    {
        $curso = $this->cursoService->getById($cursoId);
        $areas = $this->cursoService->getAreasByCourseId(($cursoId));
        return view('cursos.verCurso', compact('curso', 'areas'));
    }



    public function getCursos(int $userId)
    {
        $cursosConDetalles = $this->enrolamientoCursoService->getCursos($userId);
        $persona = $this->personaService->getById($userId);
       
        return view('empleado.cursos', compact('cursosConDetalles', 'persona'));
    }

    public function getCursosByDni(int $dni)
    {
        $persona = $this->personaService->getByDni($dni);
        $cursosConDetalles = $this->enrolamientoCursoService->getCursosByDni($dni);

        return view('empleado.cursos', compact('cursosConDetalles', 'persona'));
    }

}



