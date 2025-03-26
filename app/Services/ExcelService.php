<?php

namespace App\Services;

use App\Mail\InscripcionCursoMail;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Exception;



class ExcelService
{


    protected $personaService;
    protected $courseService;
    protected $courseInstanceService;
    protected $enrolamientocourseService;

    // Inyectar las dependencias a través del constructor
    public function __construct(
        PersonaService $personaService,
        courseService $courseService,
        CourseInstanceService $courseInstanceService,
        EnrolamientocourseService $enrolamientocourseService
    ) {
        $this->personaService = $personaService;
        $this->courseService = $courseService;
        $this->courseInstanceService = $courseInstanceService;
        $this->enrolamientocourseService = $enrolamientocourseService;
    }
    public function inscribirDesdeExcel($request, int $instancia_id, int $cursoId)
    {
        //VALIDACION DEL ARCHIVO
        if (!$request->hasFile('excel_file')) {
            return ['error' => 'No se cargó ningún archivo Excel.'];
        }

        $file = $request->file('excel_file');

        // Cargar el archivo Excel usando PhpSpreadsheet
        try {
            $spreadsheet = IOFactory::load($file);
        } catch (Exception $e) {
            return ['error' => 'Hubo un problema al procesar el archivo Excel.'];
        }

        $sheet = $spreadsheet->getActiveSheet();
        $cabecera = $sheet->rangeToArray('A1:Z1')[0];

        // Validar si la columna F tiene el valor "DNI"
        if (strtoupper(trim($cabecera[5])) !== 'DNI') {
            return ['error' => 'El archivo no tiene la estructura correcta.'];
        }

        $inscriptos = $this->enrolamientocourseService->getPersonsByInstanceId($instancia_id, $cursoId);
        $inscriptosDni = collect($inscriptos)->pluck('dni')->toArray(); // Extraemos los DNIs de los inscriptos

        $personasParaInscribir = [];
        $personasNoCorrespondientes = [];
        $personasNoEncontradas = [];

        // Recorrer las filas del archivo Excel
        foreach ($sheet->getRowIterator(2) as $row) {
            $dni = $sheet->getCell('F' . $row->getRowIndex())->getValue(); // Columna F (6) es el DNI
            $nombre1 = $sheet->getCell('G' . $row->getRowIndex())->getValue(); // Columna G (7) es Nombre1
            $apellido1 = $sheet->getCell('H' . $row->getRowIndex())->getValue(); // Columna H (8) es Apellido1

            if (empty($dni)) {
                continue; // Si el DNI está vacío, se omite la fila
            }
            // Verificar si ya está inscrito en la base de datos
            if (in_array($dni, $inscriptosDni)) {
                continue; // Si ya está inscrito, lo omitimos
            }

            // Validar si el DNI existe en la base de datos
            $persona = $this->personaService->getByDni($dni);
            if (!$persona) {
                $personasNoEncontradas[] = "$dni - $nombre1 $apellido1";
                continue;
            }

            $areaPersona = $this->personaService->getAreaByDni($dni);
            $areasCurso = $this->courseService->getAreasByCourseId($cursoId);
            $areaValida = false;

            foreach ($areasCurso as $area) {
                if ($area->id_a == 'tod') {
                    $personasParaInscribir[] = [
                        'dni' => $dni,
                        'nombre1' => $nombre1,
                        'apellido1' => $apellido1
                    ];
                    $areaValida = true;
                    break;
                } elseif ($areaPersona == $area->id_a) {
                    $personasParaInscribir[] = [
                        'dni' => $dni,
                        'nombre1' => $nombre1,
                        'apellido1' => $apellido1
                    ];
                    $areaValida = true;
                    break;
                }
            }

            if (!$areaValida) {
                $personasNoCorrespondientes[] = "$dni - $nombre1 $apellido1";
            }
        }

        // Verificar cuántas personas pueden ser inscritas
        $cupo = $this->courseInstanceService->checkInstanceQuota($cursoId, $instancia_id);
        $cantInscriptos = $this->enrolamientocourseService->getCountPersonsByInstanceId($instancia_id, $cursoId);
        $restantes = $cupo - $cantInscriptos;

        if (count($personasParaInscribir) > $restantes) {
            return ['error' => 'No se pueden inscribir esas personas, ya que la cantidad excede el cupo disponible.'];
        }

        // Inscribir a las personas válidas
        foreach ($personasParaInscribir as $persona) {
            $inscrito = $this->enrolamientocourseService->isEnrolled($persona['dni'], $instancia_id, $cursoId);
            if ($inscrito) {
                continue; // Si ya está inscrito, no inscribir nuevamente
            }

            $this->enrolamientocourseService->enroll($persona['dni'], $instancia_id, $cursoId);
            $user = $this->personaService->getByDni($persona['dni']);
            $curso = $this->courseService->getById($cursoId)->titulo;
            $fechaInicio = $this->courseInstanceService->getStartDate($cursoId, $instancia_id);

            $imageBase64Firma = null;
            $imagePath2 = storage_path('app/public/cursos/firma.jpg');
            if (file_exists($imagePath2)) {
                $imageData = base64_encode(file_get_contents($imagePath2));
                $mimeType = mime_content_type($imagePath2);
                $imageBase64Firma = 'data:' . $mimeType . ';base64,' . $imageData;
            }

            if (!empty($user->correo)) {
                Mail::to($user->correo)->send(new InscripcionCursoMail($user, $curso, $fechaInicio, $imageBase64Firma));
            }
        }

        // Generar archivo .txt si hay datos incorrectos
        if (!empty($personasNoCorrespondientes) || !empty($personasNoEncontradas)) {
            $filepath = storage_path('app/public/archivo_personas.txt');
            $contenido = "Personas que no corresponden al Area:\n";
            if (!empty($personasNoCorrespondientes)) {
                $contenido .= implode("\n", $personasNoCorrespondientes) . "\n";
            } else {
                $contenido .= "No hay personas que no correspondan al área.\n";
            }

            $contenido .= "\nPersonas con DNI incorrecto:\n";
            if (!empty($personasNoEncontradas)) {
                $contenido .= implode("\n", $personasNoEncontradas) . "\n";
            } else {
                $contenido .= "No hay personas con DNI incorrecto.\n";
            }

            file_put_contents($filepath, $contenido);

            return ['archivo_descargable' => 'archivo_personas.txt'];
        }

        return ['success' => 'Las personas fueron inscriptas exitosamente y se les envió un mail.'];
    }



}
