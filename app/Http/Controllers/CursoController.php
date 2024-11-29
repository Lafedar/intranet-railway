<?php

namespace App\Http\Controllers;

use App\Services\CursoService;
use App\Services\PersonaService;
use App\Services\CursoInstanciaService;
use App\Services\AreaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\EnrolamientoCursoService;
use App\Area;
use App\Models\Anexo;
use App\Models\Curso;
use Exception;
use DB;
use Barryvdh\Snappy\Facades\SnappyPdf;
use PDF;

class CursoController extends Controller
{
    private CursoService $cursoService;
    private CursoInstanciaService $cursoInstanciaService;
    private EnrolamientoCursoService $enrolamientoCursoService;
    private AreaService $areaService;
    private PersonaService $personaService;

    public function __construct(CursoService $cursoService, CursoInstanciaService $cursoInstanciaService, EnrolamientoCursoService $enrolamientoCursoService, AreaService $areaService, PersonaService $personaService)
    {
        $this->cursoService = $cursoService;
        $this->cursoInstanciaService = $cursoInstanciaService;
        $this->enrolamientoCursoService = $enrolamientoCursoService;
        $this->areaService = $areaService;
        $this->personaService = $personaService;
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
                $cursosData = $this->cursoService->getAll()->load('areas');
            } else {
                $cursosData = $this->enrolamientoCursoService->getCursosByUserId($personaDni->id_p);

            }

            //filtros
            if ($nombreCurso) {
                $cursosData = $cursosData->filter(function ($curso) use ($nombreCurso) {
                    return str_contains(strtolower($curso->titulo), strtolower($nombreCurso));
                });
            }

            if ($areaId && $areaId !== 'all') {

                $cursosData = $cursosData->filter(function ($curso) use ($areaId) {
                    return $curso->areas->contains('id_a', $areaId);
                });
            }

            if ($areaId === 'all') {

                $cursosData = $cursosData->filter(function ($curso) {
                    return $curso->areas->count() === $this->areaService->getAll()->count();
                });
            }

            if ($areaId && $areaId !== 'all') {
                $cursosData = $cursosData->filter(function ($curso) {

                    return $curso->areas->count() !== $this->areaService->getAll()->count();
                });
            }
            $cursosData = $cursosData->map(function ($curso) {
                $curso->cantInscriptos = $this->enrolamientoCursoService->getCountPersonas($curso->id);
                return $curso;
            });


            $cursosData = $cursosData->map(function ($curso) use ($personaDni) {
                $curso->cantInscriptos = $this->enrolamientoCursoService->getCountPersonas($curso->id);
                $curso->evaluacion = $this->enrolamientoCursoService->getEvaluacion($curso->id, $personaDni->id_p);
                $curso->porcentajeAprobados = $this->enrolamientoCursoService->getPorcentajeAprobacion($curso->id);

                return $curso;
            });

            $cursosData = $cursosData->sortByDesc('curso.created_at');
            $areas = $this->areaService->getAll();
            $totalAreas = $areas->count();


            return view('cursos.index', compact('cursosData', 'areas', 'nombreCurso', 'areaId', 'totalAreas', 'personaDni'));

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al mostrar los cursos: ' . $e->getMessage());

            return redirect()->back();
        }
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

    /*public function store(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'titulo' => 'required|string|max:253',
                'descripcion' => 'nullable|string|max:253',
                'obligatorio' => 'required|boolean',
                'codigo' => 'nullable|string',
                'tipo' => 'required|string',
                'area' => 'required|array|min:1',
            ]);


            if (empty($validatedData['area'])) {
                return redirect()->back()->withErrors('Debe seleccionar al menos un área.');
            }

            $curso = $this->cursoService->create($validatedData);

            $curso->areas()->attach($validatedData['area']);

            return redirect()->route('cursos.index')->with('success', 'Curso creado exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors()); //errores en el validateData
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Hubo un problema al crear el curso: ' . $e->getMessage());
        }
    }*/
    public function store(Request $request)
    {
        try {

            // Validar los datos del formulario
            $validatedData = $request->validate([
                'titulo' => 'required|string|max:253',
                'descripcion' => 'nullable|string|max:253',
                'obligatorio' => 'required|boolean',
                'codigo' => 'nullable|string',
                'tipo' => 'required|string',
                'area' => 'required|array|min:1',
            ]);

            // Si no se ha seleccionado ninguna área, mostrar un error
            if (empty($validatedData['area'])) {
                return redirect()->back()->withErrors('Debe seleccionar al menos un área.');
            }

            // Crear el curso usando el servicio
            $curso = $this->cursoService->create($validatedData);

            // Verificar si se seleccionó "Todas las Áreas" (es decir, el valor 'tod')
            if (in_array('tod', $validatedData['area'])) {
                // Si 'tod' está presente, asociamos solo el área 'tod'
                $curso->areas()->attach(['tod']); // Solo asignamos 'tod'
            } else {
                // Si no se seleccionó 'tod', asociamos las áreas seleccionadas
                $curso->areas()->attach($validatedData['area']);
            }

            // Redirigir con éxito
            return redirect()->route('cursos.index')->with('success', 'Curso creado exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay errores de validación, retornamos con los errores
            return redirect()->back()->withErrors($e->validator->errors());
        } catch (Exception $e) {
            // Capturar cualquier otro error y redirigir con un mensaje de error
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


    /*public function update(Request $request, int $id)
    {
        try {
            // Obtener el curso a editar
            $curso = $this->cursoService->getById($id);

            if (!$curso) {
                return redirect()->route('cursos.index')->withErrors('El curso no fue encontrado.');
            }

            $validatedData = $request->validate([
                'titulo' => 'required|string|max:100',
                'descripcion' => 'nullable|string|max:65530',
                'obligatorio' => 'required|boolean',
                'codigo' => 'nullable|string',
                'tipo' => 'required|string',
                'area' => 'nullable|array',

            ]);

            // Actualizar el curso con los datos validados
            $curso->update($validatedData);

            // Actualizar las áreas (sincroniza las áreas seleccionadas)
            if (isset($validatedData['area'])) {
                $curso->areas()->sync($validatedData['area']);
            }



            return redirect()->route('cursos.index')->with('success', 'Curso actualizado exitosamente.');

        } catch (Exception $e) {
            session()->flash('error', 'Error al actualizar el curso: ' . $e->getMessage());
            Log::error('Error in class: ' . get_class($this) . ' .Error al actualizar el curso: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al actualizar el curso.');
        }
    }*/
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
                'codigo' => 'nullable|string',
                'tipo' => 'required|string',
                'area' => 'nullable|array',
            ]);


            $curso->update($validatedData);


            if (isset($validatedData['area']) && in_array('tod', $validatedData['area'])) {

                $curso->areas()->sync(['tod']);
            } elseif (isset($validatedData['area'])) {

                $curso->areas()->sync($validatedData['area']);
            } else {

                $curso->areas()->detach();
            }

            return redirect()->route('cursos.index')->with('success', 'Curso actualizado exitosamente.');

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


            $instancias = $this->cursoInstanciaService->getInstancesByCourse($id);


            foreach ($instancias as $instancia) {
                $this->enrolamientoCursoService->deleteByInstanceId($curso->id, $instancia->id);
                $this->cursoInstanciaService->delete($instancia, $curso->id);
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
    public function generarCertificado(int $cursoId, int $id_persona)
    {

        $curso = $this->cursoService->getById($cursoId);

        $persona = $this->personaService->getById($id_persona);
        $fecha = now()->format('d/m/Y');  // Fecha en formato DD/MM/YYYY
        $imagePath = storage_path('app/public/Imagenes-principal-nueva/LOGO-LAFEDAR.png');

        if (file_exists($imagePath)) {

            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath); // Obtener el tipo MIME de la imagen (ej. image/png)

            // Crear la cadena de imagen Base64
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
        } else {

            $imageBase64 = null;
        }



        return view('cursos.certificado', compact('curso', 'persona', 'imageBase64', 'fecha'));
    }



}



