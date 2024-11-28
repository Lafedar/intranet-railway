<?php

namespace App\Http\Controllers;

use App\Services\CursoInstanciaService;
use App\Services\CursoService;
use App\Services\AreaService;
use App\Services\EnrolamientoCursoService;
use App\Services\PersonaService;
use Auth;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Curso;
use DB;
use App\Area;
use Carbon\Carbon;
use Exception;
use App\Models\Anexo;
use Barryvdh\Snappy\Facades\SnappyPdf;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Models\CursoInstanciaAnexo;
use Illuminate\Database\Eloquent\Collection;
use App\Mail\InscripcionCursoMail;
use Illuminate\Support\Facades\Mail;









class CursoInstanciaController extends Controller
{
    private $cursoInstanciaService;
    private $cursoService;
    private $enrolamientoCursoService;
    private $personaService;
    private $areaService;

    public function __construct(CursoInstanciaService $cursoInstanciaService, CursoService $cursoService, EnrolamientoCursoService $enrolamientoCursoService, PersonaService $personaService, AreaService $areaService)
    {
        $this->cursoInstanciaService = $cursoInstanciaService;
        $this->cursoService = $cursoService;
        $this->enrolamientoCursoService = $enrolamientoCursoService;
        $this->personaService = $personaService;
        $this->areaService = $areaService;
    }

    /**
     * Display a listing of instances for a specific course.
     *
     * @param int $cursoId
     * @return \Illuminate\View\View
     */

    public function index($cursoId)
    {
        try {


            $curso = $this->cursoService->getById($cursoId);
            $personaDni = Auth::user()->dni;
            $persona = $this->personaService->getByDni($personaDni);
            $evaluacion = $this->enrolamientoCursoService->getEvaluacion($cursoId, $persona->id_p);

            if (!$curso) {
                throw new Exception('Curso no encontrado.');
            }

            $userDni = auth()->user()->dni;
            $personaDni = $this->personaService->getByDni($userDni);
            $persona = $this->personaService->getById($personaDni->id_p);
            $instanciasIds = $this->enrolamientoCursoService->getInstancesByPersonId($cursoId, $persona->id_p);
            $instancias = new Collection();
            if (auth()->user()->hasRole(['administrador', 'Gestor-cursos'])) {
                $instancias = $this->cursoInstanciaService->getInstancesByCourse($cursoId)->sortByDesc('created_at');
            } else {
                foreach ($instanciasIds as $idInstancia) {
                    // Llamas al método para obtener la instancia completa
                    $instancia = $this->cursoInstanciaService->getInstanceById($idInstancia, $cursoId);

                    // Verificas si la instancia existe y la agregas a la colección
                    if ($instancia) {
                        $instancias->push($instancia);  // Usar 'push' para agregar a la colección
                    }
                }

            }
            $availability = $this->cursoInstanciaService->checkAvailability($instancias);

            $userDni = Auth::user()->dni;


            $instancesEnrollment = $instancias->map(function ($instancia) use ($userDni) {

                $isEnrolled = $this->enrolamientoCursoService->isEnrolled($userDni, $instancia->id_instancia);
                $instancia->isEnrolled = $isEnrolled;
                return $instancia;

            });


            $instanciasConRestantes = $instancias->map(function ($instancia) use ($curso) {

                $cupo = $this->cursoInstanciaService->checkInstanceQuota($curso->id, $instancia->id_instancia);
                $cantInscriptos = $this->enrolamientoCursoService->getCountPersonsByInstanceId($instancia->id_instancia, $curso->id);

                //valido si el cupo es null para los cursos viejos
                /*if ($cupo == 0 || $cupo == null) {
                    $restantes = 0;
                    $cupo = $cantInscriptos;
                    $instancia->cupo = $cupo;
                } else {
                    $restantes = $cupo - $cantInscriptos;
                }*/

                $restantes = $cupo - $cantInscriptos;
                $instancia->restantes = $restantes;

                return $instancia;
            });

            $cantInstancias = $this->cursoInstanciaService->getMaxInstanceId($cursoId) + 1;


            return view('cursos.instancias.index', compact('instancesEnrollment', 'curso', 'availability', 'cantInstancias', 'persona', 'evaluacion'));

        } catch (Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            Log::error('Error en la clase: ' . get_class($this) . ' .Error al obtener las instancias del curso: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener las instancias del curso.');
        }
    }




    public function inscription($courseId, $instanceId)
    {
        try {

            $userDni = Auth::user()->dni;
            $enroll = $this->enrolamientoCursoService->enroll($userDni, $instanceId);
            return redirect()->route('cursos.instancias.index', $courseId);

        } catch (Exception $e) {

            Log::error('Error in class: ' . get_class($this) . ' .Error al incribir al usuario ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al intentar inscribir al usuario.');

        }
    }

    public function create($instanciaId, $cursoId)
    {
        try {
            $curso = Curso::findOrFail($cursoId);
            $personas = $this->personaService->getAll();
            $anexos = $this->cursoInstanciaService->getAnexos();

            return view('cursos.instancias.create', compact('curso', 'personas', 'anexos'));
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al retornar el curso a cursos.instancias.create' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al retornar el curso a cursos.instancias.create.');
        }

    }

    public function store(Request $request, $cursoId)
    {
        try {
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'nullable|date',
                'cupo' => 'required|integer',
                'modalidad' => 'nullable|string|max:255',
                'capacitador' => 'nullable|string|max:255',
                'otro_capacitador' => 'nullable|string|max:255',
                'lugar' => 'nullable|string|max:255',
                'estado' => 'required|string|in:Activo,No Activo',
                'version' => 'nullable|string|max:255',
                'anexos' => 'nullable|array',

            ]);
            $capacitador = $request->input('capacitador');


            if ($request->input('otro_capacitador')) {
                $capacitador = $request->input('otro_capacitador');

            }

            if ($request->input('fecha_inicio') !== null) {
                $fechaInicio = Carbon::parse($request->input('fecha_inicio'));
                $fechaActual = Carbon::now();


                if ($fechaInicio < $fechaActual->startOfDay()) {
                    return redirect()->back()->withInput()->withErrors(['fecha_inicio' => 'La fecha de inicio no puede ser menor que la fecha actual.']);
                }
            }


            if ($request->input('fecha_fin') !== null && $request->input('fecha_fin') < $request->input('fecha_inicio')) {
                return redirect()->back()->withInput()->withErrors(['fecha_fin' => 'La fecha de fin debe ser mayor o igual que la fecha de inicio.']);
            }

            $data = $request->all();
            $data['id_curso'] = $cursoId;
            $data['capacitador'] = $capacitador;


            $nextInstanciaId = $this->cursoInstanciaService->getMaxInstanceId($cursoId) + 1;
            $data['id_instancia'] = $nextInstanciaId;

            $this->cursoInstanciaService->create($data);


            if ($request->has('anexos') && is_array($request->input('anexos'))) {
                foreach ($request->input('anexos') as $anexoId) {


                    $tipoAnexo = DB::table('anexos')
                        ->where('formulario_id', $anexoId)
                        ->value('tipo');

                    // Verificamos si el tipo de anexo existe
                    if ($tipoAnexo) {
                        DB::table('relacion_curso_instancia_anexo')->insert([
                            'id_curso' => $cursoId,
                            'id_instancia' => $nextInstanciaId,
                            'formulario_id' => $anexoId,
                            'tipo' => $tipoAnexo, // Asocia el tipo de anexo
                        ]);
                    } else {

                        Log::warning("Tipo de anexo no encontrado para formulario_id: $anexoId");
                    }
                }
            }


            return redirect()->route('cursos.instancias.index', $cursoId)
                ->with('success', 'Instancia creada exitosamente.');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al crear la instancia del curso: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al crear la instancia del curso.');
        }
    }


    public function destroy(int $cursoId, int $instanciaId)
    {
        try {

            $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId);

            if (!$instancia) {
                return redirect()->route('cursos.instancias.index', ['cursoId' => $cursoId])
                    ->withErrors('La instancia no fue encontrada.');
            }

            DB::table('relacion_curso_instancia_anexo')
                ->where('id_instancia', $instanciaId)
                ->where('id_curso', $cursoId)
                ->delete();

            $this->cursoInstanciaService->delete($instancia, $cursoId);

            return redirect()->route('cursos.instancias.index', ['cursoId' => $cursoId])
                ->with('success', 'Instancia eliminada exitosamente.');
        } catch (Exception $e) {

            Log::error('Error en clase: ' . get_class($this) . ' .Error al eliminar la instancia del curso: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al eliminar la instancia del curso.');
        }
    }




    public function edit($instanciaId, $cursoId)
    {
        try {
            $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId);
            $curso = $this->cursoService->getById($cursoId);
            $capacitador = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId)->capacitador;
            $personas = $this->personaService->getAll();
            $modalidad = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId)->modalidad;
            $anexos = $this->cursoInstanciaService->getAnexos();
            $selectedAnexos = $this->cursoInstanciaService->getDocumentacion($instanciaId, $cursoId);


            return view('cursos.instancias.edit', compact('instancia', 'curso', 'capacitador', 'personas', 'modalidad', 'anexos', 'selectedAnexos'));
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al retornar la instancia a cursos.instancias.edit' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al retornar la instancia a cursos.instancias.edit.');
        }


    }

    public function update(Request $request, $instanciaId, $cursoId)
    {
        try {
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'nullable|date',
                'cupo' => 'required|integer',
                'modalidad' => 'nullable|string|max:255',
                'capacitador' => 'nullable|string|max:255',
                'otro_capacitador' => 'nullable|string|max:255',
                'lugar' => 'nullable|string|max:255',
                'estado' => 'required|string|in:Activo,No Activo',
                'version' => 'nullable|string|max:255',
                'anexos' => 'nullable|array',

            ]);
            $capacitador = $request->input('capacitador');

            if ($request->input('otro_capacitador')) {
                $capacitador = $request->input('otro_capacitador');


            }

            $inscriptosCount = $this->enrolamientoCursoService->getCountPersonsByInstanceId($instanciaId, $cursoId);
            $cupo = $request->input('cupo');
            if ($cupo < $inscriptosCount) {
                return redirect()->back()->withInput()->withErrors(['cupo' => 'El cupo no puede ser menor que la cantidad de personas ya inscriptas.']);
            }

            $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId);

            $data = $request->all();
            $data['capacitador'] = $capacitador;
            $instancia->update($data);

            if ($request->has('anexos') && is_array($request->input('anexos'))) {
                // Eliminar las relaciones actuales con los anexos para esta instancia y curso
                DB::table('relacion_curso_instancia_anexo')
                    ->where('id_instancia', $instanciaId)
                    ->where('id_curso', $cursoId)
                    ->delete();

                // Obtener los anexos seleccionados desde el formulario
                $anexos = $request->input('anexos');

                foreach ($anexos as $formulario_id) {

                    $tipoAnexo = DB::table('anexos')
                        ->where('formulario_id', $formulario_id)
                        ->value('tipo');


                    if ($tipoAnexo) {
                        // Insertar la nueva relación en la tabla 'relacion_curso_instancia_anexo' con el tipo
                        DB::table('relacion_curso_instancia_anexo')->insert([
                            'id_instancia' => $instanciaId,
                            'id_curso' => $cursoId,
                            'formulario_id' => $formulario_id,
                            'tipo' => $tipoAnexo,  // Asociamos el tipo de anexo
                        ]);
                    } else {

                        Log::warning("Tipo de anexo no encontrado para formulario_id: $formulario_id");
                    }
                }
            }

            return redirect()->route('cursos.instancias.index', $instancia->id_curso)
                ->with('success', 'Instancia actualizada exitosamente.');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al actualizar la instancia' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al actualizar la instancia.');
        }

    }


    public function getAsistentesInstancia(int $instanciaId, int $cursoId, string $tipo)
    {
        try {

            $inscriptos = $this->enrolamientoCursoService->getPersonsByInstanceId($instanciaId, $cursoId);

            $inscriptosCount = $inscriptos->count();

            $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId);
            $curso = $this->cursoService->getById($cursoId);

            $anexos = $this->cursoInstanciaService->getAnexoByTipo($cursoId, $instanciaId, $tipo);

            $inscriptos->each(function ($inscripto) use ($instanciaId, $cursoId) {
                $inscripto->fecha_enrolamiento = $this->enrolamientoCursoService->getFechaCreacion($instanciaId, $cursoId, $inscripto->id_persona);
            });
            return view('cursos.instancias.inscriptos', compact('curso', 'inscriptos', 'inscriptosCount', 'instancia', 'anexos'));

        } catch (Exception $e) {
            Log::error('Error en la clase: ' . get_class($this) . ' .Error al obtener los asistentes de la instancia: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener los asistentes de la instancia.');
        }
    }


    public function getCountAsistentes(int $instanciaId, int $cursoId)
    {
        try {
            $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId);
            $inscriptos = $this->enrolamientoCursoService->getPersonsByInstanceId($instanciaId, $cursoId);
            $countInscriptos = $inscriptos->count();
            $instancia->cantInscriptos = $countInscriptos;


            return view('cursos.instancias.index', compact('countInscriptos'));
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener los asistentes de la instancia' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener los asistentes de la instancia.');
        }

    }


    public function getPersonas(int $cursoId, int $instanciaId)
    {
        try {

            $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId);
            $curso = $this->cursoService->getById($cursoId);

            $areasCurso = $this->cursoService->getAreasByCourseId($cursoId);
            $areaIds = $areasCurso->pluck('id_a')->toArray();

            $personas = $this->personaService->getPersonsByArea($areaIds);

            $personas->each(function ($persona) {
                $persona->area = $this->areaService->getAreaById($persona->area);
            });

            $personasEnroladas = $this->enrolamientoCursoService->getPersonsByInstanceId($instancia->id_instancia, $curso->id);
            $enroladasIds = $personasEnroladas->pluck('id_persona')->toArray();

            // Asignar el estado de inscripción a las personas
            $personasConEstado = $personas->map(function ($persona) use ($enroladasIds) {
                $persona->estadoEnrolado = in_array($persona->id_p, $enroladasIds);
                return $persona;
            });

            $personasConEstado = $personasConEstado->sortByDesc('estadoEnrolado');


            $cupo = $this->cursoInstanciaService->checkInstanceQuota($curso->id, $instancia->id_instancia);

            $cantInscriptos = $this->enrolamientoCursoService->getCountPersonsByInstanceId($instancia->id_instancia, $curso->id);
            $restantes = $cupo - $cantInscriptos;

            if ($filtro = request('filtro')) {
                $personasConEstado = $personasConEstado->filter(function ($persona) use ($filtro) {
                    return stripos($persona->nombre_p, $filtro) !== false || stripos($persona->apellido, $filtro) !== false || stripos($persona->legajo, $filtro) !== false;
                });
            }

            return view('cursos.instancias.personas', compact('personasConEstado', 'curso', 'instancia', 'restantes'));

        } catch (Exception $e) {
            Log::error('Error al obtener las personas para inscribir: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener las personas para inscribir.');
        }
    }




    public function inscribirVariasPersonas(Request $request, int $instancia_id, int $cursoId)
    {
        try {
            $personasSeleccionadas = $request->input('personas', []);

            if (empty($personasSeleccionadas)) {
                return redirect()->back()->with('error', 'No se seleccionaron personas para inscribir.');
            }
            $imagePath2 = storage_path('app/public/cursos/firma.jpg');

            if (file_exists($imagePath2)) {
                $imageData = base64_encode(file_get_contents($imagePath2));
                $mimeType = mime_content_type($imagePath2); // Obtener el tipo MIME de la imagen (ej. image/png)
                $imageBase64Firma = 'data:' . $mimeType . ';base64,' . $imageData;
            } else {
                $imageBase64Firma = null;
            }

            foreach ($personasSeleccionadas as $id_persona => $inscribir) {
                $user = $this->personaService->getById($id_persona);

                // Inscribir a la persona
                $this->enrolamientoCursoService->enroll($user->dni, $instancia_id, $cursoId);

                // Enviar el correo de inscripción
                $curso = $this->cursoService->getById($cursoId)->titulo; // Aquí deberías obtener el nombre real del curso
                $fechaInicio = $this->cursoInstanciaService->getFechaInicio($cursoId, $instancia_id);

                // Enviar el correo
                Mail::to($user->correo)->send(new InscripcionCursoMail($user, $curso, $fechaInicio, $imageBase64Firma));
            }

            return redirect()->back()->with('success', 'Las personas seleccionadas han sido inscriptas exitosamente y se les ha enviado un correo.');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al inscribir la/s personas' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al inscribir la/s personas.');
        }
    }


    public function desinscribirPersona(int $userId, int $instanciaId, int $cursoId)
    {
        try {
            $this->enrolamientoCursoService->unEnroll($userId, $instanciaId, $cursoId);
            return redirect()->back()->with('success', 'La persona ha sido desenrolada correctamente.');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al desinscribir la persona' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al desinscribir la persona.');
        }

    }




    public function evaluarInstancia($userId, $instanciaId, $cursoId, $bandera)
    {
        try {

            $resultado = $this->enrolamientoCursoService->evaluarInstancia($userId, $instanciaId, $cursoId, $bandera);
            return redirect()->back()
                ->with('success', 'La persona fue evaluada correctamente.');

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al evaluar la persona' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al evaluar la persona.');

            return redirect()->back()
                ->withErrors('Ocurrió un error al aprobar la instancia: ' . $e->getMessage());
        }
    }

    public function evaluarInstanciaTodos(Request $request, $cursoId, $instanciaId, $bandera)
    {
        try {

            // Obtener todas las personas inscritas en esta instancia
            $inscriptos = $this->enrolamientoCursoService->getPersonsByInstanceId($instanciaId, $cursoId);

            // Aprobar a todas las personas
            foreach ($inscriptos as $enrolamiento) {
                $this->enrolamientoCursoService->evaluarInstancia($enrolamiento->id_persona, $instanciaId, $cursoId, $bandera);
            }
            if ($bandera == 0) {
                return redirect()->back()->with('success', 'Todas las personas fueron aprobadas correctamente.');
            } else {
                return redirect()->back()->with('success', 'Todas las personas fueron desaprobadas correctamente.');
            }

        } catch (Exception $e) {
            Log::error('Error en la clase: ' . get_class($this) . ' .Error al aprobar a todas las personas: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al aprobar a todas las personas.');
        }
    }


    public function verPlanilla(int $instanciaId, int $cursoId, string $tipo)
    {
        $inscriptos = $this->enrolamientoCursoService->getPersonsByInstanceId($instanciaId, $cursoId);
        $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId);
        $curso = $this->cursoService->getById($cursoId);
        $anexo = $this->cursoInstanciaService->getAnexoByTipo($cursoId, $instanciaId, $tipo);


        $inscriptos = $inscriptos->where('evaluacion', 'Aprobado');

        $inscriptosChunks = array_chunk($inscriptos->toArray(), 17);

        $inscriptos->each(function ($inscripto) use ($instanciaId, $cursoId) {
            $inscripto->fecha_enrolamiento = $this->enrolamientoCursoService->getFechaCreacion($instanciaId, $cursoId, $inscripto->id_persona);
        });

        $imagePath = storage_path('app/public/cursos/logo-lafedar.png');
        if (file_exists($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath);
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
        } else {
            $imageBase64 = null;
        }

        return view('cursos.planillaCursos', compact('inscriptos', 'anexo', 'instancia', 'curso', 'imageBase64', 'inscriptosChunks'));
    }




    public function generarPDF(string $formulario_id, int $cursoId, int $instanciaId)
    {
        $is_pdf = true;
        $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId);
        $curso = $this->cursoService->getById($cursoId);
        $inscriptos = $this->enrolamientoCursoService->getPersonsByInstanceId($instanciaId, $cursoId);
        $inscriptos = $inscriptos->where('evaluacion', 'Aprobado');

        $inscriptosArray = $inscriptos->toArray();

        // Dividir la lista de inscriptos en páginas (cada página tendrá 17 inscriptos)
        $inscriptosChunks = array_chunk($inscriptosArray, 17);

        foreach ($inscriptosChunks as &$pagina) {
            while (count($pagina) < 17) {
                $pagina[] = [
                    'fecha_enrolamiento' => null,
                    'persona' => [
                        'nombre_p' => null,
                        'apellido' => null
                    ]
                ];
            }
        }


        $anexo = $this->cursoInstanciaService->getDocumentacionById($formulario_id, $cursoId, $instanciaId);
        if (!$anexo) {
            return redirect()->back()->withErrors('La instancia no tiene un anexo relacionado.');
        }


        $imagePath = storage_path('app/public/cursos/logo-lafedar.png');
        if (file_exists($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath);
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
        } else {
            $imageBase64 = null;
        }


        $html = view('cursos.planillaCursos', compact('inscriptos', 'instancia', 'curso', 'anexo', 'imageBase64', 'inscriptosChunks', 'is_pdf'))->render();


        $pdf = SnappyPdf::loadHTML($html)
            ->setOption('enable-local-file-access', true)
            ->setOption('enable-javascript', true)
            ->setOption('javascript-delay', 200)
            ->setOption('margin-top', 10)
            ->setOption('margin-right', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10);

        return $pdf->download('planilla.pdf');
    }





    public function verPlanillaPrevia(string $formulario_id, int $cursoId, int $instanciaId)
    {
        $curso = $this->cursoService->getById($cursoId);
        $anexo = $this->cursoInstanciaService->getDocumentacionById($formulario_id, $cursoId, $instanciaId);
        $imagePath = storage_path('app/public/cursos/logo-lafedar.png');

        if (file_exists($imagePath)) {

            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath); // Obtener el tipo MIME de la imagen (ej. image/png)

            // Crear la cadena de imagen Base64
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
        } else {

            $imageBase64 = null;
        }


        return view('cursos.planillaPrevia', compact('anexo', 'imageBase64'));
    }

    public function getDocumentacion(int $instanciaId, int $cursoId)
    {

        $documentos = $this->cursoInstanciaService->getDocumentacion($instanciaId, $cursoId);
        $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId);
        $curso = $this->cursoService->getById($cursoId);

        return view('cursos.documentacion', compact('documentos', 'instancia', 'curso'));
    }

    //DEJO ESTO POR SI EN ALGUN MOMENTO SE LLEGA A USAR - GENERA CERTIFICADO PARA CADA INSTANCIA
/*public function generarCertificado(int $instanciaId, int $cursoId, int $id_persona){
    $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId);
    $curso = $this->cursoService->getById($cursoId);
    $persona = $this->personaService->getById($id_persona);
    $fecha = now()->format('d/m/Y');  // Fecha en formato DD/MM/YYYY
    $imagePath = storage_path('app/public/Imagenes principal-nueva/LOGO-LAFEDAR.png'); 
    
    if (file_exists($imagePath)) {
       
        $imageData = base64_encode(file_get_contents($imagePath));
        $mimeType = mime_content_type($imagePath); // Obtener el tipo MIME de la imagen (ej. image/png)

        // Crear la cadena de imagen Base64
        $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
    } else {
       
        $imageBase64 = null;
    }

   

    return view('cursos.certificado', compact('instancia', 'curso', 'persona', 'imageBase64','fecha'));
}

public function generarPDFcertificado(int $instanciaId, int $cursoId, int $id_persona) {
    $is_pdf = true;
    $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId);
    $curso = $this->cursoService->getById($cursoId);
    $persona = $this->personaService->getById($id_persona);
    $fecha = now()->format('d/m/Y');  

    
    $imagePath = storage_path('app/public/Imagenes principal-nueva/LOGO-LAFEDAR.png'); 
    
    if (file_exists($imagePath)) {
       
        $imageData = base64_encode(file_get_contents($imagePath));
        $mimeType = mime_content_type($imagePath); // Obtener el tipo MIME de la imagen (ej. image/png)

        
        $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
    } else {
       
        $imageBase64 = null;
    }

    
    $html = view('cursos.certificado', compact('instancia', 'curso', 'persona', 'imageBase64', 'fecha', 'is_pdf'))->render();

    
    $pdf = SnappyPdf::loadHTML($html)
                ->setOption('orientation', 'landscape') // Establece la orientación a apaisado
                ->setOption('enable-local-file-access', true)
                ->setOption('enable-javascript', true)
                ->setOption('javascript-delay', 200)
                ->setOption('margin-top', 10)
                ->setOption('margin-right', 10)
                ->setOption('margin-bottom', 5)
                ->setOption('margin-left', 10);

    
    return $pdf->download('certificado.pdf');
}*/



}
