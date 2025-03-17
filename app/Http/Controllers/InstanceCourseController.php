<?php

namespace App\Http\Controllers;

use App\Services\CursoInstanciaService;
use App\Services\CursoService;
use App\Services\AreaService;
use App\Services\EnrolamientoCursoService;
use App\Services\PersonaService;
use App\Services\AnnexedService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Curso;
use DB;
use Carbon\Carbon;
use Exception;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Database\Eloquent\Collection;
use App\Mail\InscripcionCursoMail;
use Illuminate\Support\Facades\Mail;



class InstanceCourseController extends Controller
{
    private $cursoInstanciaService;
    private $cursoService;
    private $enrolamientoCursoService;
    private $personaService;
    private $areaService;
    private $annexedService;


    public function __construct(CursoInstanciaService $cursoInstanciaService, CursoService $cursoService, EnrolamientoCursoService $enrolamientoCursoService, PersonaService $personaService, AreaService $areaService, AnnexedService $annexedService)
    {
        $this->cursoInstanciaService = $cursoInstanciaService;
        $this->cursoService = $cursoService;
        $this->enrolamientoCursoService = $enrolamientoCursoService;
        $this->personaService = $personaService;
        $this->areaService = $areaService;
        $this->annexedService = $annexedService;
    }

    /**
     * Display a listing of instances for a specific course.
     *
     * @param int $courseId
     * @return \Illuminate\View\View
     */

    public function index($courseId)
    {
        try {


            $course = $this->cursoService->getById($courseId);

            $personDni = Auth::user()->dni;
            $person = $this->personaService->getByDni($personDni);
            $assessment = $this->enrolamientoCursoService->getEvaluacion($courseId, $person->id_p);

            if (!$course) {
                throw new Exception('course no encontrado.');
            }

            $userDni = auth()->user()->dni;
            $personDni = $this->personaService->getByDni($userDni);
            $person = $this->personaService->getById($personDni->id_p);
            $instancesIds = $this->enrolamientoCursoService->getInstancesByPersonId($courseId, $person->id_p);
            $instances = new Collection();
            if (auth()->user()->hasRole(['administrador', 'Gestor-courses'])) {
                $instances = $this->cursoInstanciaService->getInstancesByCourse($courseId)->sortByDesc('created_at');
            } else {
                foreach ($instancesIds as $idInstancia) {
                    // Llamas al método para obtener la instancia completa
                    $instance = $this->cursoInstanciaService->getInstanceById($idInstancia, $courseId);

                    // Verificas si la instancia existe y la agregas a la colección
                    if ($instance) {
                        $instances->push($instance);  // Usar 'push' para agregar a la colección
                    }
                }

            }
            $availability = $this->cursoInstanciaService->checkAvailability($instances);

            $userDni = Auth::user()->dni;


            $instancesEnrollment = $instances->map(function ($instance) use ($userDni, $course) {

                $isEnrolled = $this->enrolamientoCursoService->isEnrolled($userDni, $instance->id_instancia, $course->id);
                $instance->isEnrolled = $isEnrolled;
                $instance->amountRegistered = $this->enrolamientoCursoService->getCountPersonsByInstanceId($instance->id_instancia, $course->id);

                $quota = $this->cursoInstanciaService->checkInstanceQuota($course->id, $instance->id_instancia);

                $amountRegistered = $this->enrolamientoCursoService->getCountPersonsByInstanceId($instance->id_instancia, $course->id);
                $percentageAPP = $this->enrolamientoCursoService->getPorcentajeAprobacionInstancia($instance->id_instancia, $course->id);
                $amountAnnexes = $this->cursoInstanciaService->getCountAnexosInstancia($course->id, $instance->id_instancia);

                $remaining = $quota - $amountRegistered;
                $instance->remaining = $remaining;
                $instance->percentageAPP = $percentageAPP;
                $instance->amountAnnexes = $amountAnnexes;
                $instance->quota = $quota;

                $instance->formatted_start_date = Carbon::parse($instance->fecha_inicio)->format('d/m/Y');
                $instance->formatted_end_date = Carbon::parse($instance->fecha_fin)->format('d/m/Y');
                $instance->formatted_hour = $instance->hora ? Carbon::parse($instance->hora)->format('H:i') : 'N/A';

                return $instance;

            });

            $amountInstances = $this->cursoInstanciaService->getMaxInstanceId($courseId) + 1;


            return view('cursos.instancias.index', compact('instancesEnrollment', 'course', 'availability', 'amountInstances', 'person', 'assessment'));

        } catch (Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            Log::error('Error en la clase: ' . get_class($this) . ' .Error getting course instances: ' . $e->getMessage());
            return redirect()->route('home.inicio');

        }
    }


    public function create($instanceId, $courseId)
    {
        try {
            $course = Curso::findOrFail($courseId);
            $persons = $this->personaService->getAll();

            $annexes = $this->annexedService->getAll();

            return view('cursos.instancias.create', compact('course', 'persons', 'annexes'));
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error returning course to courses.instances.create' . $e->getMessage());
            return redirect()->back()->withErrors('There was a problem returning the course to courses.instancias.create.');
        }

    }

    public function store(Request $request, $courseId)
    {
        try {
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'nullable|date',
                'hora' => 'nullable|date_format:H:i',
                'cupo' => 'required|integer',
                'modalidad' => 'nullable|string|max:255',
                'capacitador' => 'nullable|string|max:255',
                'otro_capacitador' => 'nullable|string|max:255',
                'codigo' => 'nullable|string|max:49',
                'lugar' => 'nullable|string|max:255',
                'estado' => 'required|string|in:Activo,No Activo',
                'version' => 'nullable|string|max:255',
                'anexos' => 'nullable|array',
                'certificado' => 'required|max:20',
                'examen' => 'nullable|max:200',


            ]);
            $trainer = $request->input('capacitador');



            if ($request->input('otro_capacitador')) {
                $trainer = $request->input('otro_capacitador');

            }

            if ($request->input('fecha_fin') !== null && $request->input('fecha_fin') < $request->input('fecha_inicio')) {
                return redirect()->back()->withInput()->withErrors(['fecha_fin' => 'La fecha de fin debe ser mayor o igual que la fecha de inicio.']);
            }

            $data = $request->all();
            $data['id_curso'] = $courseId;
            $data['capacitador'] = $trainer;
            $data['codigo'] = $request->input('codigo');
            $data['certificado'] = $request->input('certificado');

            $data['hora'] = $request->input('hora');





            $nextInstanceId = $this->cursoInstanciaService->getMaxInstanceId($courseId) + 1;
            $data['id_instancia'] = $nextInstanceId;

            if ($request->input('certificado') == "Participacion") {
                $data['examen'] = null;
            } else {
                $data['examen'] = $request->input('examen');
            }

            $this->cursoInstanciaService->create($data);




            if ($request->has('anexos') && is_array($request->input('anexos'))) {
                foreach ($request->input('anexos') as $annexedId) {


                    $annexedType = $this->annexedService->getById($annexedId);

                    // Verificamos si el tipo de anexo existe
                    if ($annexedType) {
                        $this->annexedService->insert_annexed_course_instance($courseId, $nextInstanceId, $annexedId, $annexedType);
                    } else {

                        Log::warning("Attachment type not found for formulario_id: $annexedId");
                    }
                }
            }


            return redirect()->route('cursos.instancias.index', $courseId)
                ->with('success', 'Instance created successfully.');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error creating course instance: ' . $e->getMessage());
            return redirect()->back()->withErrors('There was a problem creating the course instance.');
        }
    }


    public function destroy(int $courseId, int $instanceId)
    {
        try {

            $instance = $this->cursoInstanciaService->getInstanceById($instanceId, $courseId);

            if (!$instance) {
                return redirect()->route('curso.instancias.index', ['courseId' => $courseId])
                    ->withErrors('The instance was not found.');
            }

            $this->annexedService->delete_annexed_course_instance($instanceId, $courseId);

            $this->cursoInstanciaService->delete($instance, $courseId);

            return redirect()->route('curso.instancias.index', ['cursoId' => $courseId])
                ->with('success', 'Instance successfully deleted.');
        } catch (Exception $e) {

            Log::error('Error in class: ' . get_class($this) . ' .Error deleting course instance: ' . $e->getMessage());
            return redirect()->back()->withErrors('There was a problem deleting the course instance.');
        }
    }




    public function edit($instanceId, $courseId)
    {
        try {
            $instance = $this->cursoInstanciaService->getInstanceById($instanceId, $courseId);
            $course = $this->cursoService->getById($courseId);
            $trainer = $this->cursoInstanciaService->getInstanceById($instanceId, $courseId)->capacitador;
            $persons = $this->personaService->getAll();
            $modality = $this->cursoInstanciaService->getInstanceById($instanceId, $courseId)->modalidad;
            $annexes = $this->cursoInstanciaService->getAnexos();
            $selectedAnnexes = $this->cursoInstanciaService->getDocumentacion($instanceId, $courseId);


            return view('cursos.instancias.edit', compact('instance', 'course', 'trainer', 'persons', 'modality', 'annexes', 'selectedAnnexes'));
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error returning the instance to courses.instancias.edit' . $e->getMessage());
            return redirect()->back()->withErrors('There was a problem returning the instance to courses.instancias.edit.');
        }


    }

    public function update(Request $request, $instanceId, $courseId)
    {
        try {

            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'nullable|date',
                'hora' => 'nullable|date_format:H:i',
                'cupo' => 'required|integer',
                'modalidad' => 'nullable|string|max:255',
                'capacitador' => 'nullable|string|max:255',
                'otro_capacitador' => 'nullable|string|max:255',
                'codigo' => 'nullable|string|max:49',
                'lugar' => 'nullable|string|max:255',
                'estado' => 'required|string|in:Activo,No Activo',
                'version' => 'nullable|string|max:255',
                'anexos' => 'nullable|array',
                'certificado' => 'required|max:20',
                'examen' => 'nullable|string|max:200',

            ]);
            // Obtener el valor actual de hora de la base de datos (o del modelo)

            $trainer = $request->input('capacitador');
            $hour = $request->input('hora');


            if ($request->input('otro_capacitador')) {
                $trainer = $request->input('otro_capacitador');


            }


            if ($request->input('fecha_fin') !== null && $request->input('fecha_fin') < $request->input('fecha_inicio')) {
                return redirect()->back()->withInput()->withErrors(['fecha_fin' => 'La fecha de fin debe ser mayor o igual que la fecha de inicio.']);
            }
            $amountRegistered = $this->enrolamientoCursoService->getCountPersonsByInstanceId($instanceId, $courseId);
            $quota = $request->input('cupo');
            if ($quota < $amountRegistered) {
                return redirect()->back()->withInput()->withErrors(['cupo' => 'El cupo no puede ser menor que la cantidad de personas ya inscriptas.']);
            }

            $instance = $this->cursoInstanciaService->getInstanceById($instanceId, $courseId);

            $data = $request->all();
            $data['capacitador'] = $trainer;
            $data['certificado'] = $request->input('certificado');
            $data['examen'] = $request->input('examen');

            $data['hora'] = $hour;
            $instance->update($data);

            $registered = $this->enrolamientoCursoService->getPersonsByInstanceId($instance->id_instancia, $courseId);
            if ($instance->certificado == "Participacion") {
                $instance->examen = "";
                $instance->save();
                foreach ($registered as $enrolled) {
                    $enrolled->evaluacion = "Participacion";

                    $enrolled->save();
                }
            } elseif ($instance->certificado == "Aprobacion") {

                foreach ($registered as $enrolled) {
                    $enrolled->evaluacion = "N/A";
                    $enrolled->save();
                }
            }

            if (!$request->has('anexos') || empty($request->input('anexos'))) {
                // Eliminar todas las relaciones con los anexos de esta instancia
                $this->annexedService->delete_annexed_course_instance($instanceId, $courseId);

            } elseif (is_array($request->input('anexos'))) {
                // Si se seleccionaron anexos, primero eliminamos las relaciones actuales
                $this->annexedService->delete_annexed_course_instance($instanceId, $courseId);

                // Insertamos los nuevos anexos seleccionados
                $annexed = $request->input('anexos');
                foreach ($annexed as $form_id) {
                    $annexedType = $this->annexedService->getById($form_id);

                    if ($annexedType) {

                        $this->annexedService->insert_annexed_course_instance($courseId, $instanceId, $form_id, $annexedType);
                    } else {
                        Log::warning("Attachment type not found for formulario_id: $form_id");
                    }
                }
            }

            return redirect()->route('cursos.instancias.index', $instance->id_curso)
                ->with('success', 'Instancia actualizada correctamente.');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating the instance' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al actualizar la instancia.');
        }

    }


    public function getAsistentesInstancia(int $instanceId, int $courseId, string $tipo)
    {
        try {

            $registered = $this->enrolamientoCursoService->getPersonsByInstanceId($instanceId, $courseId);

            $amountRegistered = $registered->count();

            $instance = $this->cursoInstanciaService->getInstanceById($instanceId, $courseId);
            $course = $this->cursoService->getById($courseId);
            $annexed = $this->cursoInstanciaService->getAnexoByTipo($courseId, $instanceId, $tipo);
            $amountApproved = $this->enrolamientoCursoService->getCountAprobadosInstancia($course->id, $instance->id_instancia);
            $registered->each(function ($enrolled) use ($instanceId, $courseId) {
                $enrolled->fecha_enrolamiento = $this->enrolamientoCursoService->getFechaCreacion($instanceId, $courseId, $enrolled->id_persona);
            });
            return view('cursos.instancias.inscriptos', compact('course', 'registered', 'amountRegistered', 'instance', 'annexed', 'amountApproved'));

        } catch (Exception $e) {
            Log::error('Error en la clase: ' . get_class($this) . ' .Error getting the assistants from the instance: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener los asistentes de la instancia.');
        }
    }


    public function getCountAsistentes(int $instanceId, int $courseId)
    {
        try {
            $instance = $this->cursoInstanciaService->getInstanceById($instanceId, $courseId);
            $registered = $this->enrolamientoCursoService->getPersonsByInstanceId($instanceId, $courseId);
            $countRegistered = $registered->count();
            $instance->amountRegistered = $countRegistered;


            return view('cursos.instancias.index', compact('$countRegistered'));
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting the assistants from the instance: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener los asistentes de la instancia.');
        }

    }


    public function getPersonas(int $courseId, int $instanceId)
    {
        try {

            $instance = $this->cursoInstanciaService->getInstanceById($instanceId, $courseId);
            $course = $this->cursoService->getById($courseId);

            $areasCourse = $this->cursoService->getAreasByCourseId($courseId);
            $areaIds = $areasCourse->pluck('id_a')->toArray();

            $persons = $this->personaService->getPersonsByArea($areaIds);

            $persons->each(function ($person) {
                $person->area = $this->areaService->getAreaById($person->area);
            });

            $personsEnroll = $this->enrolamientoCursoService->getPersonsByInstanceId($instance->id_instancia, $course->id);
            $enrollIds = $personsEnroll->pluck('id_persona')->toArray();

            // Asignar el estado de inscripción a las personas
            $personsWithStatus = $persons->map(function ($person) use ($enrollIds) {
                $person->estadoEnrolado = in_array($person->id_p, $enrollIds);
                return $person;
            });


            $personsWithStatus = $personsWithStatus->sortByDesc('estadoEnrolado');


            $quota = $this->cursoInstanciaService->checkInstanceQuota($course->id, $instance->id_instancia);

            $amountRegistered = $this->enrolamientoCursoService->getCountPersonsByInstanceId($instance->id_instancia, $course->id);
            $remaining = $quota - $amountRegistered;

            if ($filtro = request('filtro')) {
                $personsWithStatus = $personsWithStatus->filter(function ($person) use ($filtro) {
                    return stripos($person->nombre_p, $filtro) !== false || stripos($person->apellido, $filtro) !== false || stripos($person->legajo, $filtro) !== false;
                });
            }

            return view('cursos.instancias.personas', compact('personsWithStatus', 'course', 'instance', 'remaining'));

        } catch (Exception $e) {
            Log::error('Error al obtener las personas para inscribir: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener las personas para inscribir.');
        }
    }




    public function inscribirVariasPersonas(Request $request, int $instance_id, int $courseId, $manager_dni)
    {
        try {
            $selectedPersons = $request->input('personas', []);
            $manager = $this->personaService->getByDni($manager_dni);

            if (empty($selectedPersons)) {
                return redirect()->back()->with('error', 'No se seleccionaron personas para inscribir.');
            }

            $imagePath2 = storage_path('app/public/courses/firma.jpg');

            if (file_exists($imagePath2)) {
                $imageData = base64_encode(file_get_contents($imagePath2));
                $mimeType = mime_content_type($imagePath2); // Obtener el tipo MIME de la imagen (ej. image/png)
                $imageBase64Firma = 'data:' . $mimeType . ';base64,' . $imageData;
            } else {
                $imageBase64Firma = null;
            }

            $successfulRegistrations = 0;  // Contador de inscripciones exitosas

            foreach ($selectedPersons as $id_persona => $inscribir) {
                if ($inscribir == 1) {  // Solo inscribir si la persona fue seleccionada
                    $user = $this->personaService->getById($id_persona);
                    $manager = $this->personaService->getByDni($manager->dni);

                    // Inscribir a la persona
                    $this->enrolamientoCursoService->enroll($user->dni, $instance_id, $courseId);

                    // Enviar el correo de inscripción
                    $course = $this->cursoService->getById($courseId);
                    $startDate = $this->cursoInstanciaService->getFechaInicio($courseId, $instance_id);
                    $room = $this->cursoInstanciaService->get_room($courseId, $instance_id);
                    $hour = $this->cursoInstanciaService->get_hour($courseId, $instance_id);

                    if ($request->input('mail') && !empty($user->correo)) {
                        Mail::to($user->correo)->send(new InscripcionCursoMail($user, $course, $startDate, $imageBase64Firma, $manager, $room, $hour));
                    }

                    $successfulRegistrations++;  // Incrementar el contador de inscripciones exitosas
                }
            }

            // Verificar si al menos una inscripción fue exitosa
            if ($successfulRegistrations > 0) {
                return redirect()->back()->with('success', 'Las personas seleccionadas han sido inscriptas exitosamente' . ($request->input('mail') ? ' y se les ha enviado un correo.' : ''));
            } else {
                return redirect()->back()->with('error', 'Hubo un problema al inscribir las personas.');
            }

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error registering the person(s)' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al inscribir la/s personas.');
        }
    }


    public function desinscribirPersona(int $userId, int $instanceId, int $courseId)
    {
        try {
            $this->enrolamientoCursoService->unEnroll($userId, $instanceId, $courseId);
            return redirect()->back()->with('success', 'La persona ha sido desenrolada correctamente.');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error unsubscribing the person' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al desinscribir la persona.');
        }

    }




    public function evaluarInstancia($userId, $instanceId, $courseId, $bandera)
    {
        try {

            $this->enrolamientoCursoService->evaluarInstancia($userId, $instanceId, $courseId, $bandera);
            return redirect()->back()
                ->with('success', 'La persona fue evaluada correctamente.');

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error in evaluating the person' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al evaluar la persona.');


        }
    }

    public function evaluarInstanciaTodos(Request $request, $courseId, $instanceId, $flag)
    {
        try {

            // Obtener todas las personas inscritas en esta instancia
            $registered = $this->enrolamientoCursoService->getPersonsByInstanceId($instanceId, $courseId);

            // Aprobar a todas las personas
            foreach ($registered as $enlistment) {
                $this->enrolamientoCursoService->evaluarInstancia($enlistment->id_persona, $instanceId, $courseId, $flag);
            }
            if ($flag == 0) {
                return redirect()->back()->with('success', 'Todas las personas fueron aprobadas correctamente.');
            } else {
                return redirect()->back()->with('success', 'Todas las personas fueron desaprobadas correctamente.');
            }

        } catch (Exception $e) {
            Log::error('Error en la clase: ' . get_class($this) . ' .Error approving all people: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al aprobar a todas las personas.');
        }
    }


    public function verPlanilla(int $instanceId, int $courseId, string $tipo)
    {
        $registered = $this->enrolamientoCursoService->getPersonsByInstanceId($instanceId, $courseId);
        $instance = $this->cursoInstanciaService->getInstanceById($instanceId, $courseId);
        $course = $this->cursoService->getById($courseId);
        $annexed = $this->cursoInstanciaService->getAnexoByTipo($courseId, $instanceId, $tipo);



        $registered->each(function ($registered) use ($instanceId, $courseId) {
            $registered->fecha_enrolamiento = Carbon::parse($this->enrolamientoCursoService->getFechaCreacion($instanceId, $courseId, $registered->id_persona))
                ->format('d/m/Y');
        });

        $registeredChunks = array_chunk($registered->toArray(), 17);

        $imagePath = storage_path('app/public/courses/logo-lafedar.png');
        if (file_exists($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath);
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
        } else {
            $imageBase64 = null;
        }

        return view('cursos.planillaCursos', compact('registered', 'annexed', 'instance', 'course', 'imageBase64', 'registeredChunks'));
    }


    public function generarPDF(string $formulario_id, int $courseId, int $instanceId, Request $request)
    {
        $is_pdf = true;
        $instance = $this->cursoInstanciaService->getInstanceById($instanceId, $courseId);
        $course = $this->cursoService->getById($courseId);
        $registered = $this->enrolamientoCursoService->getPersonsByInstanceId($instanceId, $courseId);

        $registeredArray = $registered->toArray();

        // Dividir la lista de inscriptos en páginas (cada página tendrá 17 inscriptos)
        $registeredChunks = array_chunk($registeredArray, 17);

        foreach ($registeredChunks as &$page) {
            while (count($page) < 17) {
                $page[] = [
                    'fecha_enrolamiento' => null,
                    'persona' => [
                            'nombre_p' => null,
                            'apellido' => null
                        ]
                ];
            }
        }

        foreach ($page as &$registered) {
            if (!empty($registered['fecha_enrolamiento'])) {

                $registered['fecha_enrolamiento'] = Carbon::parse($registered['fecha_enrolamiento'])->format('d/m/Y');
            }
        }


        $selectedDate = $request->input('fechaSeleccionada', null); // 'null' por defecto si no se pasa


        $annexed = $this->cursoInstanciaService->getDocumentacionById($formulario_id, $courseId, $instanceId);
        if (!$annexed) {
            return redirect()->back()->withErrors('La instancia no tiene un anexo relacionado.');
        }

        $imagePath = storage_path('app/public/courses/logo-lafedar.png');
        if (file_exists($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath);
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
        } else {
            $imageBase64 = null;
        }


        $html = view('cursos.planillaCursos', compact('registered', 'instance', 'course', 'annexed', 'imageBase64', 'registeredChunks', 'is_pdf', 'selectedDate'))->render();


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




    public function verPlanillaPrevia(string $form_id, int $courseId, int $instanceId)
    {
        $course = $this->cursoService->getById($courseId);
        $annexed = $this->cursoInstanciaService->getDocumentacionById($form_id, $courseId, $instanceId);
        $imagePath = storage_path('app/public/courses/logo-lafedar.png');

        if (file_exists($imagePath)) {

            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath); // Obtener el tipo MIME de la imagen (ej. image/png)

            // Crear la cadena de imagen Base64
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
        } else {

            $imageBase64 = null;
        }


        return view('cursos.planillaPrevia', compact('annexed', 'imageBase64'));
    }

    public function getDocumentacion(int $instanceId, int $courseId)
    {

        $documents = $this->cursoInstanciaService->getDocumentacion($instanceId, $courseId);
        $instance = $this->cursoInstanciaService->getInstanceById($instanceId, $courseId);
        $course = $this->cursoService->getById($courseId);

        return view('cursos.documentacion', compact('documents', 'instance', 'course'));
    }


    public function enviarCertificado($courseId, $instanceId)
    {

        $approved = $this->enrolamientoCursoService->getAprobados($courseId, $instanceId);
        $course = $this->cursoService->getById($courseId);
        $instance = $this->cursoInstanciaService->getInstanceById($instanceId, $courseId);
        $registered = $this->enrolamientoCursoService->getPersonsByInstanceId($instanceId, $courseId);

        if ($instance->certificado == "Participacion") {
            $approved = $registered ;
        } elseif ($approved->isEmpty()) {
            return "No hay personas aprobadas para esta instancia.";
        }
        //logo lafedar
        $imagePath = storage_path('app/public/Imagenes-principal-nueva/LOGO-LAFEDAR.png');

        if (file_exists($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath); // Obtener el tipo MIME de la imagen (ej. image/png)
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
        } else {
            $imageBase64 = null;
        }

        $firmaPath = storage_path('app/public/courses/firma_rrhh.png');

        if (file_exists($firmaPath)) {

            $imageData2 = base64_encode(file_get_contents($firmaPath));
            $mimeType2 = mime_content_type($firmaPath); // Obtener el tipo MIME de la imagen (ej. image/png)

            // Crear la cadena de imagen Base64
            $imageBase64_firma = 'data:' . $mimeType2 . ';base64,' . $imageData2;
        } else {

            $imageBase64_firma = null;
        }

        $successCount = 0;
        $errorCount = 0;


        foreach ($approved as $personId) {

            if ($instance->certificado == "Aprobacion") {
                $person = $this->personaService->getById($personId);
            } else if ($instance->certificado == "Participacion") {
                $person = $this->personaService->getById($personId->id_persona);
            }



            if (!$person || empty($person->correo)) {
                continue;
            }


            $data = [
                'nombre' => $person->nombre_p,
                'apellido' => $person->apellido,
                'course' => $course->titulo,
                'capacitador' => $instance->capacitador,
                'fecha' => $instance->fecha_inicio->format('d/m/Y'),
                'imageBase64' => $imageBase64,
                'imageBase64Firma' => $imageBase64_firma,
            ];

            if (!defined('CERTIFICADO_BASE_PATH')) {
                define('CERTIFICADO_BASE_PATH', public_path('storage/certificados/certificado-'));
            }

            $filePath = CERTIFICADO_BASE_PATH . $person->id_p . '.pdf';

            if (file_exists($filePath)) {
                $filePath = CERTIFICADO_BASE_PATH . $person->id_p . '-' . time() . '.pdf';
            }

            // Asegurarse de que la carpeta exista antes de guardar el archivo
            $directory = dirname($filePath);
            if (!file_exists($directory)) {
                mkdir($directory, 0775, true); // Crear la carpeta si no existe
            }

            if ($instance->certificado == "Aprobacion") {
                // Generar el PDF y guardarlo en la ruta
                $pdf = SnappyPdf::loadView('cursos.certificadoMail', $data)
                    ->setPaper('a4')
                    ->setOrientation('landscape');

                $pdf->save($filePath);

            } elseif ($instance->certificado == "Participacion") {
                $pdf = SnappyPdf::loadView('cursos.certificadoMailParticipacion', $data)
                    ->setPaper('a4')
                    ->setOrientation('landscape');

                $pdf->save($filePath);
            }

            if (file_exists($filePath)) {
                // Enviar por correo con el archivo adjunto
                Mail::to($person->correo)
                    ->send(new \App\Mail\CertificadoMail($filePath, $data['nombre'], $data['apellido'], $data['course'], $data['imageBase64Firma']));

                // Eliminar el archivo temporal si no es necesario guardarlo
                unlink($filePath);

                $successCount++;
            } else {

                Log::error("No se pudo guardar el archivo PDF para la persona ID: " . $person->id_p);
                $errorCount++;
            }
        }


        if ($successCount > 0 && $instance->certificado == "Aprobacion") {
            return redirect()->back()->with('success', "Se enviaron correctamente $successCount certificados de aprobación por correo, a las personas aprobadas.");
        } elseif ($successCount > 0 && $instance->certificado == "Participacion") {
            return redirect()->back()->with('success', "Se enviaron correctamente $successCount certificados de participacion por correo.");
        } else {
            return redirect()->back()->with('error', 'Hubo un problema al enviar los certificados.');
        }



    }
    public function generarCertificado(int $courseId, int $id_persona, int $id_instancia)
    {

        $course = $this->cursoService->getById($courseId);

        $person = $this->personaService->getById($id_persona);
        $instance = $this->cursoInstanciaService->getInstanceById($id_instancia, $courseId);
        $enlistment = $this->enrolamientoCursoService->get_enlistment($person->id_p, $course->id, $instance->id_instancia)->first();
        $date = now()->format('d/m/Y');  // Fecha en formato DD/MM/YYYY
        $imagePath = storage_path('app/public/Imagenes-principal-nueva/LOGO-LAFEDAR.png');

        if (file_exists($imagePath)) {

            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath); // Obtener el tipo MIME de la imagen (ej. image/png)

            // Crear la cadena de imagen Base64
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
        } else {

            $imageBase64 = null;
        }



        $firmaPath = storage_path('app/public/courses/firma_rrhh.png');

        if (file_exists($firmaPath)) {

            $imageData2 = base64_encode(file_get_contents($firmaPath));
            $mimeType2 = mime_content_type($firmaPath); // Obtener el tipo MIME de la imagen (ej. image/png)

            // Crear la cadena de imagen Base64
            $imageBase64_firma = 'data:' . $mimeType2 . ';base64,' . $imageData2;
        } else {

            $imageBase64_firma = null;
        }


        if ($enlistment->evaluacion == "Aprobado") {
            return view('cursos.certificado', compact('course', 'person', 'imageBase64', 'date', 'instance', 'imageBase64_firma'));
        } elseif ($enlistment->evaluacion == "Participacion") {
            return view('cursos.certificadoParticipacion', compact('course', 'person', 'imageBase64', 'date', 'instance', 'imageBase64_firma'));
        }
    }

    public function generarPDFcertificado(int $instanceId, int $courseId, int $id_persona)
    {
        $is_pdf = true;
        $course = $this->cursoService->getById($courseId);
        $person = $this->personaService->getById($id_persona);
        $date = now()->format('d/m/Y');
        $instance = $this->cursoInstanciaService->getInstanceById($instanceId, $courseId);


        $imagePath = storage_path('app/public/Imagenes-principal-nueva/LOGO-LAFEDAR.png');

        if (file_exists($imagePath)) {

            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath); // Obtener el tipo MIME de la imagen (ej. image/png)


            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
        } else {

            $imageBase64 = null;
        }


        $firmaPath = storage_path('app/public/courses/firma_rrhh.png');

        if (file_exists($firmaPath)) {

            $imageData2 = base64_encode(file_get_contents($firmaPath));
            $mimeType2 = mime_content_type($firmaPath); // Obtener el tipo MIME de la imagen (ej. image/png)

            // Crear la cadena de imagen Base64
            $imageBase64_firma = 'data:' . $mimeType2 . ';base64,' . $imageData2;
        } else {

            $imageBase64_firma = null;
        }



        if ($instance->certificado == 'Aprobacion') {
            $html = view('cursos.certificado', compact('instance', 'course', 'person', 'imageBase64_firma', 'imageBase64', 'date', 'is_pdf'))->render();
        } else {
            $html = view('cursos.certificadoParticipacion', compact('instance', 'course', 'person', 'imageBase64_firma', 'imageBase64', 'date', 'is_pdf'))->render();
        }

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
    }
    public function cambiarEstadoInstancia(int $instanceId, int $courseId, string $bandera)
    {
        $this->cursoInstanciaService->cambiarEstadoInstancia($instanceId, $courseId, $bandera);
        return redirect()->back();
    }




}
