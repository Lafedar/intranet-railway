<?php

namespace App\Http\Controllers;

use App\Services\CursoInstanciaService;
use App\Services\CursoService;
use App\Services\EnrolamientoCursoService;
use Auth;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Curso;


class CursoInstanciaController extends Controller
{
    private $cursoInstanciaService;
    private $cursoService;
    private $enrolamientoCursoService;

    public function __construct(CursoInstanciaService $cursoInstanciaService, CursoService $cursoService, EnrolamientoCursoService $enrolamientoCursoService)
    {
        $this->cursoInstanciaService = $cursoInstanciaService;
        $this->cursoService = $cursoService;
        $this->enrolamientoCursoService = $enrolamientoCursoService;
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
            
            $instancias = $this->cursoInstanciaService->getInstancesByCourse($cursoId);
            
            $curso = $this->cursoService->getById($cursoId);

            if (!$curso) {
                throw new \Exception('Curso no encontrado.');
            }

            //verificar la disponiblidad de las instancias de los cursos
            $availability = $this->cursoInstanciaService->checkAvailability($instancias);

            $userDni = Auth::user()->dni;

            //obtener las instancias en la que el usuario esta inscripto
            $instancesEnrollment = $instancias->map(function ($instancia) use ($userDni) {                  
                $isEnrolled = $this->enrolamientoCursoService->isEnrolled($userDni, $instancia->id);
                $instancia->isEnrolled = $isEnrolled;
                return $instancia;
            });
            
            return view('cursos.instancias.index', compact('instancesEnrollment', 'curso', 'availability'));
        
        } catch (\Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al obtener instancias del curso: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener las instancias del curso.');
        }
    }


    public function inscription($courseId, $instanceId)
    {
        try {

            $userDni = Auth::user()->dni; 
            $enroll = $this->enrolamientoCursoService->enroll($userDni, $instanceId);
            return redirect()->route('cursos.instancias.index', $courseId);

        } catch (\Exception $e) {

            Log::error('Error in class: ' . get_class($this) . ' .Error al incribir al usuario ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al intentar inscribir al usuario.');

        }
    }

    public function create($cursoId)
    {
        $curso = Curso::findOrFail($cursoId); 
        return view('cursos.instancias.create', compact('curso')); 
    }

    public function store(Request $request, $cursoId)
    {
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            
        ]);

        
        $data = $request->all();
        $data['curso_id'] = $cursoId; // Asegúrate de incluir el ID del curso

        
        $this->instanciaService->create($data);

       
        return redirect()->route('cursos.instancias.index', $cursoId)
                         ->with('success', 'Instancia creada exitosamente.');
    }
}
