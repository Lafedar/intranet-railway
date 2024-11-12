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
            
            $instancias = $this->cursoInstanciaService->getInstancesByCourse($cursoId)->sortByDesc('created_at');
            $curso = $this->cursoService->getById($cursoId);

            if (!$curso) {
                throw new \Exception('Curso no encontrado.');
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
                if ($cupo == 0 || $cupo == null) {
                    $restantes = 0;
                    $cupo = $cantInscriptos;
                    $instancia->cupo = $cupo;
                } else {
                    $restantes = $cupo - $cantInscriptos;
                    
                }
                    
                $instancia->restantes = $restantes;
                
                return $instancia;
            });

            return view('cursos.instancias.index', compact('instancesEnrollment', 'curso', 'availability'));

        } catch (\Exception $e) {
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

        } catch (\Exception $e) {

            Log::error('Error in class: ' . get_class($this) . ' .Error al incribir al usuario ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al intentar inscribir al usuario.');

        }
    }

    public function create($cursoId)
    {
        try{
            $curso = Curso::findOrFail($cursoId); 
            return view('cursos.instancias.create', compact('curso')); 
        }
        catch(\Exception $e){
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
            'lugar' => 'nullable|string|max:255',
            'estado' => 'required|string|in:Activo,No Activo',
            'version' => 'nullable|string|max:255',
        ]);

        if ($request->input('fecha_inicio') !== null) {
            $fechaInicio = Carbon::parse($request->input('fecha_inicio'));
            $fechaActual = Carbon::now();
        
            // Verifica si la fecha de inicio es menor que la fecha actual
            if ($fechaInicio < $fechaActual) {
                return redirect()->back()->withInput()->withErrors(['fecha_inicio' => 'La fecha de inicio no puede ser menor que la fecha actual.']);
            }
        }

        // Solo valida si fecha_fin no es nula
        if ($request->input('fecha_fin') !== null && $request->input('fecha_fin') < $request->input('fecha_inicio')) {
            return redirect()->back()->withInput()->withErrors(['fecha_fin' => 'La fecha de fin debe ser mayor o igual que la fecha de inicio.']);
        }

        $data = $request->all();
        $data['id_curso'] = $cursoId;

        // Obtener el próximo id_instancia basado en el conteo de instancias
        $nextInstanciaId = $this->cursoInstanciaService->getCountInstances($cursoId) + 1;
        $data['id_instancia'] = $nextInstanciaId;

        $this->cursoInstanciaService->create($data);

        return redirect()->route('cursos.instancias.index', $cursoId)
                         ->with('success', 'Instancia creada exitosamente.');
    } catch (\Exception $e) {
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

        
        $this->cursoInstanciaService->delete($instancia, $cursoId);
        
        return redirect()->route('cursos.instancias.index', ['cursoId' => $cursoId])
                         ->with('success', 'Instancia eliminada exitosamente.');
    } catch (\Exception $e) {
        // Registrar el error y redirigir con mensaje de error
        Log::error('Error en clase: ' . get_class($this) . ' .Error al eliminar la instancia del curso: ' . $e->getMessage());
        return redirect()->back()->withErrors('Hubo un problema al eliminar la instancia del curso.');
    }
}




    public function edit($instanciaId, $cursoId)
    {
        try{
            $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId);
            $curso = $this->cursoService->getById($cursoId);
        
            return view('cursos.instancias.edit', compact('instancia', 'curso'));
        }
        catch(\Exception $e){
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
                'lugar' => 'nullable|string|max:255',
                'estado' => 'required|string|in:Activo,No Activo',
                'version' => 'nullable|string|max:255',
            ]);

            if ($request->input('fecha_inicio') !== null) {
                $fechaInicio = Carbon::parse($request->input('fecha_inicio'));
                $fechaActual = Carbon::now();
            
                // Verifica si la fecha de inicio es menor que la fecha actual
                if ($fechaInicio < $fechaActual) {
                    return redirect()->back()->withInput()->withErrors(['fecha_inicio' => 'La fecha de inicio no puede ser menor que la fecha actual.']);
                }
            }
            // Solo valida si fecha_fin no es nula
            if ($request->input('fecha_fin') !== null && $request->input('fecha_fin') < $request->input('fecha_inicio')) {
                return redirect()->back()->withInput()->withErrors(['fecha_fin' => 'La fecha de fin debe ser mayor o igual que la fecha de inicio.']);
            }
            $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId);
            $instancia->update($request->all());
        
            return redirect()->route('cursos.instancias.index', $instancia->id_curso)
                             ->with('success', 'Instancia actualizada exitosamente.');
        }catch(\Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al actualizar la instancia' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al actualizar la instancia.');
        }
       
    }

    public function getAsistentesInstancia(int $instanciaId, int $cursoId)
    {
        $inscritos = $this->enrolamientoCursoService->getPersonsByInstanceId($instanciaId, $cursoId);
        $inscriptosCount = $inscritos->count(); // Conteo de inscritos
       $instancia=$this->cursoInstanciaService->getInstanceById($instanciaId, $cursoId);
        $curso = $this->cursoService->getById($cursoId);
        
        return view('cursos.instancias.inscriptos', compact('curso', 'inscritos', 'inscriptosCount',  'instancia'));
    }

    public function getCountAsistentes(int $instanciaId, int $cursoId)
    {  
        $instancia=$this->cursoInstanciaService->getInstanceById($instanciaId);
        $inscriptos = $this->enrolamientoCursoService->getPersonsByInstanceId($instanciaId, $cursoId);
        $countInscriptos = $inscritos->count();
        $instancia->cantInscriptos = $countInscriptos;

        
        return view('cursos.instancias.index', compact('countInscriptos'));
    }


    public function getPersonas(int $cursoId, int $instanciaId)
    {
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

        $personasConEstado = $personas->map(function ($persona) use ($enroladasIds) {
            $persona->estadoEnrolado = in_array($persona->id_p, $enroladasIds);
            return $persona;
        });

        $cupo = $this->cursoInstanciaService->checkInstanceQuota($curso->id, $instancia->id_instancia);
        
        $cantInscriptos = $this->enrolamientoCursoService->getCountPersonsByInstanceId($instancia->id_instancia, $curso->id);
        $restantes = $cupo - $cantInscriptos;
        
        if ($filtro = request('filtro')) {
            $personasConEstado = $personasConEstado->filter(function ($persona) use ($filtro) {
                return stripos($persona->nombre_p, $filtro) !== false || stripos($persona->apellido, $filtro) !== false || stripos($persona->legajo, $filtro) !== false;
               
            });
        }

        return view('cursos.instancias.personas', compact('personasConEstado', 'curso', 'instancia','restantes'));
    }


    public function inscribirVariasPersonas(Request $request, int $instancia_id, int $cursoId)
    {
        
        $personasSeleccionadas = $request->input('personas', []);
    
        if (empty($personasSeleccionadas)) {
            return redirect()->back()->with('error', 'No se seleccionaron personas para inscribir.');
        }
        foreach ($personasSeleccionadas as $id_persona => $inscribir) {
            $user = $this->personaService->getById($id_persona);
            
           
            $this->enrolamientoCursoService->enroll($user->dni, $instancia_id, $cursoId);
        }

        
        return redirect()->back()->with('success', 'Las personas seleccionadas han sido inscriptas exitosamente.');
    }

    public function desinscribirPersona(int $userId, int $instanciaId, int $cursoId)
{
    
    $this->enrolamientoCursoService->unEnroll($userId, $instanciaId, $cursoId);
    
    
    return redirect()->back()->with('success', 'La persona ha sido desenrolada correctamente.');
}




public function evaluarInstancia($userId, $instanciaId, $cursoId, $bandera)
{
    try {
        
        $resultado = $this->enrolamientoCursoService->evaluarInstancia($userId, $instanciaId, $cursoId, $bandera);

        
        return redirect()->back()
                         ->with('success', 'La instancia fue evaluada correctamente.');

    } catch (\Exception $e) {
        
        return redirect()->back()
                         ->withErrors('Ocurrió un error al aprobar la instancia: ' . $e->getMessage());
    }
}





  
   
   


   

}
