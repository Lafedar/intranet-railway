<?php

namespace App\Http\Controllers;

use App\Services\CourseInstanceService;
use App\Services\CourseService;
use App\Services\AreaService;
use App\Services\EnrolamientoCursoService;
use App\Services\PersonaService;
use App\Services\AnnexService;
use App\Models\Course;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use Carbon\Carbon;
use Exception;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Database\Eloquent\Collection;
use App\Mail\InscripcionCursoMail;
use Illuminate\Support\Facades\Mail;



class CourseInstanceController extends Controller
{
    private $courseInstanceService;
    private $courseService;
    private $enrolamientoCursoService;
    private $personaService;
    private $areaService;
    private $annexService;


    public function __construct(CourseInstanceService $courseInstanceService, CourseService $courseService, EnrolamientoCursoService $enrolamientoCursoService, PersonaService $personaService, AreaService $areaService, AnnexService $annexService)
    {
        $this->courseInstanceService = $courseInstanceService;
        $this->courseService = $courseService;
        $this->enrolamientoCursoService = $enrolamientoCursoService;
        $this->personaService = $personaService;
        $this->areaService = $areaService;
        $this->annexService = $annexService;
    }

    public function listAll($courseId)
    {
        try {


            $course = $this->courseService->getById($courseId);

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
            if (auth()->user()->hasRole(['administrador', 'Gestor-cursos'])) {
                $instances = $this->courseInstanceService->getInstancesByCourse($courseId)->sortByDesc('created_at');
            } else {
                foreach ($instancesIds as $idInstancia) {
                    // Llamas al método para obtener la instancia completa
                    $instance = $this->courseInstanceService->getInstanceById($idInstancia, $courseId);

                    // Verificas si la instancia existe y la agregas a la colección
                    if ($instance) {
                        $instances->push($instance);  // Usar 'push' para agregar a la colección
                    }
                }

            }
            $availability = $this->courseInstanceService->checkAvailability($instances);

            $userDni = Auth::user()->dni;


            $instancesEnrollment = $instances->map(function ($instance) use ($userDni, $course) {

                $isEnrolled = $this->enrolamientoCursoService->isEnrolled($userDni, $instance->id_instancia, $course->id);
                $instance->isEnrolled = $isEnrolled;
                $instance->amountRegistered = $this->enrolamientoCursoService->getCountPersonsByInstanceId($instance->id_instancia, $course->id);

                $quota = $this->courseInstanceService->checkInstanceQuota($course->id, $instance->id_instancia);

                $amountRegistered = $this->enrolamientoCursoService->getCountPersonsByInstanceId($instance->id_instancia, $course->id);
                $percentageAPP = $this->enrolamientoCursoService->getPorcentajeAprobacionInstancia($instance->id_instancia, $course->id);
                $amountAnnexes = $this->courseInstanceService->getCountAnnexesInstance($course->id, $instance->id_instancia);

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

            $amountInstances = $this->courseInstanceService->getMaxInstanceId($courseId) + 1;


            return view('cursos.instancias.index', compact('instancesEnrollment', 'course', 'availability', 'amountInstances', 'person', 'assessment'));

        } catch (Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            Log::error('Error en la clase: ' . get_class($this) . ' .Error getting course instances: ' . $e->getMessage());
            return redirect()->route('home.inicio');

        }
    }


    public function showCreateCourseInstanceForm($instanceId, $courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $persons = $this->personaService->getAll();

            $annexes = $this->annexService->getAll();

            return view('cursos.instancias.create', compact('course', 'persons', 'annexes'));
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error returning course to courses.instances.create' . $e->getMessage());
            return redirect()->back()->withErrors('There was a problem returning the course to courses.instancias.create.');
        }

    }

    public function saveNewCourseInstance(Request $request, $courseId)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'nullable|date',
                'hour' => 'nullable|date_format:H:i',
                'quota' => 'required|integer',
                'modality' => 'nullable|string|max:255',
                'trainer' => 'nullable|string|max:255',
                'another_trainer' => 'nullable|string|max:255',
                'code' => 'nullable|string|max:49',
                'place' => 'nullable|string|max:255',
                'status' => 'required|string|in:Activo,No Activo',
                'version' => 'nullable|string|max:255',
                'annexes' => 'nullable|array',
                'certificate' => 'required|max:20',
                'exam' => 'nullable|max:200',


            ]);
            $trainer = $request->input('trainer');



            if ($request->input('another_trainer')) {
                $trainer = $request->input('another_trainer');

            }

            if ($request->input('end_date') !== null && $request->input('end_date') < $request->input('start_date')) {
                return redirect()->back()->withInput()->withErrors(['end_date' => 'La fecha de fin debe ser mayor o igual que la fecha de inicio.']);
            }

            $data = $request->all();
            $data['id_curso'] = $courseId;
            $data['capacitador'] = $trainer;
            $data['codigo'] = $request->input('code');
            $data['certificado'] = $request->input('certificate');
            $data['lugar'] = $request->input('place');
            $data['estado'] = $request->input('status');
            $data['hora'] = $request->input('hour');
            $data['cupo'] = $request->input('quota');
            $data['modalidad'] = $request->input('modality');
            $data['fecha_inicio'] = $request->input('start_date');
            $data['fecha_fin'] = $request->input('end_date');





            $nextInstanceId = $this->courseInstanceService->getMaxInstanceId($courseId) + 1;
            $data['id_instancia'] = $nextInstanceId;

            if ($request->input('certificate') == "Participacion") {
                $data['examen'] = null;
            } else {
                $data['examen'] = $request->input('exam');
            }

            $this->courseInstanceService->create($data);




            if ($request->has('annexes') && is_array($request->input('annexes'))) {
                foreach ($request->input('annexes') as $annexId) {


                    $annexType = $this->annexService->getById($annexId);

                    // Verificamos si el tipo de anexo existe
                    if ($annexType) {
                        $this->annexService->insert_annex_course_instance($courseId, $nextInstanceId, $annexId, $annexType);
                    } else {

                        Log::warning("Attachment type not found for formulario_id: $annexId");
                    }
                }
            }


            return redirect()->route('cursos.instancias.index', $courseId)
                ->with('success', 'Instancia creada correctamente.');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error creating course instance: ' . $e->getMessage());
            return redirect()->back()->withErrors('There was a problem creating the course instance.');
        }
    }


    public function deleteCourseInstanceWithAssociations(int $courseId, int $instanceId)
    {
        try {

            $instance = $this->courseInstanceService->getInstanceById($instanceId, $courseId);

            if (!$instance) {
                return redirect()->route('curso.instancias.index', ['courseId' => $courseId])
                    ->withErrors('The instance was not found.');
            }

            $this->annexService->delete_annex_course_instance($instanceId, $courseId);

            $this->courseInstanceService->delete($instance, $courseId);

            return redirect()->back()->with('success', 'Instancia eliminada correctamente.');
        } catch (Exception $e) {

            Log::error('Error in class: ' . get_class($this) . ' .Error deleting course instance: ' . $e->getMessage());
            return redirect()->back()->withErrors('There was a problem deleting the course instance.');
        }
    }




    public function showEditCourseInstanceForm($instanceId, $courseId)
    {
        try {
            $instance = $this->courseInstanceService->getInstanceById($instanceId, $courseId);
            $course = $this->courseService->getById($courseId);
            $trainer = $this->courseInstanceService->getInstanceById($instanceId, $courseId)->capacitador;
            $persons = $this->personaService->getAll();
            $modality = $this->courseInstanceService->getInstanceById($instanceId, $courseId)->modalidad;
            $annexes = $this->courseInstanceService->getAnnexes();
            $selectedAnnexes = $this->courseInstanceService->getDocumentation($instanceId, $courseId);


            return view('cursos.instancias.edit', compact('instance', 'course', 'trainer', 'persons', 'modality', 'annexes', 'selectedAnnexes'));
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error returning the instance to courses.instancias.edit' . $e->getMessage());
            return redirect()->back()->withErrors('There was a problem returning the instance to courses.instancias.edit.');
        }


    }

    public function updateDetails(Request $request, $instanceId, $courseId)
    {
        try {

            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'nullable|date',
                'hour' => 'nullable|date_format:H:i',
                'quota' => 'required|integer',
                'modality' => 'nullable|string|max:255',
                'trainer' => 'nullable|string|max:255',
                'another_trainer' => 'nullable|string|max:255',
                'code' => 'nullable|string|max:49',
                'place' => 'nullable|string|max:255',
                'status' => 'required|string|in:Activo,No Activo',
                'version' => 'nullable|string|max:255',
                'annexes' => 'nullable|array',
                'certificate' => 'required|max:20',
                'exam' => 'nullable|string|max:200',

            ]);
            // Obtener el valor actual de hora de la base de datos (o del modelo)

            $trainer = $request->input('trainer');
            $hour = $request->input('hour');


            if ($request->input('another_trainer')) {
                $trainer = $request->input('another_trainer');


            }


            if ($request->input('end_date') !== null && $request->input('end_date') < $request->input('start_date')) {
                return redirect()->back()->withInput()->withErrors(['end_date' => 'La fecha de fin debe ser mayor o igual que la fecha de inicio.']);
            }
            $amountRegistered = $this->enrolamientoCursoService->getCountPersonsByInstanceId($instanceId, $courseId);
            $quota = $request->input('quota');
            if ($quota < $amountRegistered) {
                return redirect()->back()->withInput()->withErrors(['cupo' => 'El cupo no puede ser menor que la cantidad de personas ya inscriptas.']);
            }

            $instance = $this->courseInstanceService->getInstanceById($instanceId, $courseId);

            $data = $request->all();
            $data['capacitador'] = $trainer;
            $data['certificado'] = $request->input('certificate');
            $data['examen'] = $request->input('exam');

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

            if (!$request->has('annexes') || empty($request->input('annexes'))) {
                // Eliminar todas las relaciones con los anexos de esta instancia
                $this->annexService->delete_annex_course_instance($instanceId, $courseId);

            } elseif (is_array($request->input('annexes'))) {
                // Si se seleccionaron anexos, primero eliminamos las relaciones actuales
                $this->annexService->delete_annex_course_instance($instanceId, $courseId);

                // Insertamos los nuevos anexos seleccionados
                $annex = $request->input('annexes');
                foreach ($annex as $form_id) {
                    $annexType = $this->annexService->getById($form_id);

                    if ($annexType) {

                        $this->annexService->insert_annex_course_instance($courseId, $instanceId, $form_id, $annexType);
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


    public function getInstanceHelpers(int $instanceId, int $courseId, string $tipo)
    {
        try {

            $registered = $this->enrolamientoCursoService->getPersonsByInstanceId($instanceId, $courseId);

            $amountRegistered = $registered->count();

            $instance = $this->courseInstanceService->getInstanceById($instanceId, $courseId);
            $course = $this->courseService->getById($courseId);
            $annex = $this->courseInstanceService->getannexByType($courseId, $instanceId, $tipo);
            $amountApproved = $this->enrolamientoCursoService->getCountAprobadosInstancia($course->id, $instance->id_instancia);
            $registered->each(function ($enrolled) use ($instanceId, $courseId) {
                $enrolled->fecha_enrolamiento = $this->enrolamientoCursoService->getFechaCreacion($instanceId, $courseId, $enrolled->id_persona);
            });
            return view('cursos.instancias.inscriptos', compact('course', 'registered', 'amountRegistered', 'instance', 'annex', 'amountApproved'));

        } catch (Exception $e) {
            Log::error('Error en la clase: ' . get_class($this) . ' .Error getting the assistants from the instance: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener los asistentes de la instancia.');
        }
    }


    public function getCountRegistered(int $instanceId, int $courseId)
    {
        try {
            $instance = $this->courseInstanceService->getInstanceById($instanceId, $courseId);
            $registered = $this->enrolamientoCursoService->getPersonsByInstanceId($instanceId, $courseId);
            $countRegistered = $registered->count();
            $instance->amountRegistered = $countRegistered;


            return view('cursos.instancias.index', compact('$countRegistered'));
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting the assistants from the instance: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener los asistentes de la instancia.');
        }

    }


    public function getPeopleToSignUp(int $courseId, int $instanceId)
    {
        try {

            $instance = $this->courseInstanceService->getInstanceById($instanceId, $courseId);
            $course = $this->courseService->getById($courseId);

            $areasCourse = $this->courseService->getAreasByCourseId($courseId);
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


            $quota = $this->courseInstanceService->checkInstanceQuota($course->id, $instance->id_instancia);

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




    public function registerMultiplePeople(Request $request, int $instance_id, int $courseId, $manager_dni)
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
                    $course = $this->courseService->getById($courseId);
                    $startDate = $this->courseInstanceService->getStartDate($courseId, $instance_id);
                    $room = $this->courseInstanceService->get_room($courseId, $instance_id);
                    $hour = $this->courseInstanceService->get_hour($courseId, $instance_id);

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


    public function unsubscribePerson(int $userId, int $instanceId, int $courseId)
    {
        try {
            $this->enrolamientoCursoService->unEnroll($userId, $instanceId, $courseId);
            return redirect()->back()->with('success', 'La persona ha sido desenrolada correctamente.');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error unsubscribing the person' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al desinscribir la persona.');
        }

    }




    public function evaluateInstanceForPerson($userId, $instanceId, $courseId, $bandera)
    {
        try {

            $this->enrolamientoCursoService->evaluateInstance($userId, $instanceId, $courseId, $bandera);
            return redirect()->back()
                ->with('success', 'La persona fue evaluada correctamente.');

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error in evaluating the person' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al evaluar la persona.');


        }
    }

    public function evaluateInstanceForAll(Request $request, $courseId, $instanceId, $flag)
    {
        try {

            // Obtener todas las personas inscritas en esta instancia
            $registered = $this->enrolamientoCursoService->getPersonsByInstanceId($instanceId, $courseId);

            // Aprobar a todas las personas
            foreach ($registered as $enlistment) {
                $this->enrolamientoCursoService->evaluateInstance($enlistment->id_persona, $instanceId, $courseId, $flag);
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


    public function seeCourseWorkSheet(int $instanceId, int $courseId, string $tipo)
    {
        $registered = $this->enrolamientoCursoService->getPersonsByInstanceId($instanceId, $courseId);
        $instance = $this->courseInstanceService->getInstanceById($instanceId, $courseId);
        $course = $this->courseService->getById($courseId);
        $annex = $this->courseInstanceService->getannexByType($courseId, $instanceId, $tipo);



        $registered->each(function ($registered) use ($instanceId, $courseId) {
            $registered->fecha_enrolamiento = Carbon::parse($this->enrolamientoCursoService->getFechaCreacion($instanceId, $courseId, $registered->id_persona))
                ->format('d/m/Y');
        });

        $registeredChunks = array_chunk($registered->toArray(), 17);

        $imagePath = storage_path('app/public/cursos/logo-lafedar.png');
        if (file_exists($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath);
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
        } else {
            $imageBase64 = null;
        }

        return view('cursos.planillaCursos', compact('registered', 'annex', 'instance', 'course', 'imageBase64', 'registeredChunks'));
    }


    public function generatePdfWorkSheet(string $formulario_id, int $courseId, int $instanceId, Request $request)
    {
        $is_pdf = true;
        $instance = $this->courseInstanceService->getInstanceById($instanceId, $courseId);
        $course = $this->courseService->getById($courseId);
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


        $selectedDate = $request->input('selectedDate', null); // 'null' por defecto si no se pasa


        $annex = $this->courseInstanceService->getDocumentationById($formulario_id, $courseId, $instanceId);
        if (!$annex) {
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


        $html = view('cursos.planillaCursos', compact('registered', 'instance', 'course', 'annex', 'imageBase64', 'registeredChunks', 'is_pdf', 'selectedDate'))->render();


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




    public function showPreviousCourseWorkSheet(string $form_id, int $courseId, int $instanceId)
    {
        $course = $this->courseService->getById($courseId);
        $annex = $this->courseInstanceService->getDocumentationById($form_id, $courseId, $instanceId);
        $imagePath = storage_path('app/public/cursos/logo-lafedar.png');

        if (file_exists($imagePath)) {

            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath); // Obtener el tipo MIME de la imagen (ej. image/png)

            // Crear la cadena de imagen Base64
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
        } else {

            $imageBase64 = null;
        }


        return view('cursos.planillaPrevia', compact('annex', 'imageBase64'));
    }

    public function getDocumentation(int $instanceId, int $courseId)
    {

        $documents = $this->courseInstanceService->getDocumentation($instanceId, $courseId);
        $instance = $this->courseInstanceService->getInstanceById($instanceId, $courseId);
        $course = $this->courseService->getById($courseId);

        return view('cursos.documentacion', compact('documents', 'instance', 'course'));
    }


    public function sendCertificateToPeople($courseId, $instanceId)
    {

        $approved = $this->enrolamientoCursoService->getAprobados($courseId, $instanceId);
        $course = $this->courseService->getById($courseId);
        $instance = $this->courseInstanceService->getInstanceById($instanceId, $courseId);
        $registered = $this->enrolamientoCursoService->getPersonsByInstanceId($instanceId, $courseId);

        if ($instance->certificado == "Participacion") {
            $approved = $registered;
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

        $firmaPath = storage_path('app/public/cursos/firma_rrhh.png');

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
    public function generateCertificate(int $courseId, int $id_persona, int $id_instancia)
    {

        $course = $this->courseService->getById($courseId);

        $person = $this->personaService->getById($id_persona);
        $instance = $this->courseInstanceService->getInstanceById($id_instancia, $courseId);
        $enlistment = $this->enrolamientoCursoService->getEnrollment($person->id_p, $course->id, $instance->id_instancia)->first();
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



        $firmaPath = storage_path('app/public/cursos/firma_rrhh.png');

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

    public function generatePDFcertificate(int $instanceId, int $courseId, int $id_persona)
    {
        $is_pdf = true;
        $course = $this->courseService->getById($courseId);
        $person = $this->personaService->getById($id_persona);
        $date = now()->format('d/m/Y');
        $instance = $this->courseInstanceService->getInstanceById($instanceId, $courseId);


        $imagePath = storage_path('app/public/Imagenes-principal-nueva/LOGO-LAFEDAR.png');

        if (file_exists($imagePath)) {

            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath); // Obtener el tipo MIME de la imagen (ej. image/png)


            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageData;
        } else {

            $imageBase64 = null;
        }


        $firmaPath = storage_path('app/public/cursos/firma_rrhh.png');

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
    public function changeInstanceStatus(int $instanceId, int $courseId, string $bandera)
    {
        $this->courseInstanceService->changeInstanceStatus($instanceId, $courseId, $bandera);
        return redirect()->back();
    }




}
