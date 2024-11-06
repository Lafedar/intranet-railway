<?php

namespace App\Http\Controllers;

use App\Services\CursoInstanciaService;
use App\Services\CursoService;
use App\Services\EnrolamientoCursoService;
use App\Services\PersonaService;
use Auth;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Curso;
use DB;


class CursoInstanciaController extends Controller
{
    private $cursoInstanciaService;
    private $cursoService;
    private $enrolamientoCursoService;
    private $personaService;

    public function __construct(CursoInstanciaService $cursoInstanciaService, CursoService $cursoService, EnrolamientoCursoService $enrolamientoCursoService, PersonaService $personaService)
    {
        $this->cursoInstanciaService = $cursoInstanciaService;
        $this->cursoService = $cursoService;
        $this->enrolamientoCursoService = $enrolamientoCursoService;
        $this->personaService = $personaService;
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
        
        $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId);

        if (!$instancia) {
            
            return redirect()->route('cursos.instancias.index', ['cursoId' => $cursoId])
                             ->withErrors('La instancia no fue encontrada.');
        }

        $this->enrolamientoCursoService->deleteByInstanceId($cursoId, $instancia->id_instancia);

        $this->cursoInstanciaService->delete($instancia);
        
        return redirect()->route('cursos.instancias.index', ['cursoId' => $cursoId])
                         ->with('success', 'Instancia eliminada exitosamente.');
    } catch (\Exception $e) {
        // Registrar el error y redirigir con mensaje de error
        Log::error('Error en clase: ' . get_class($this) . ' .Error al eliminar la instancia del curso: ' . $e->getMessage());
        return redirect()->back()->withErrors('Hubo un problema al eliminar la instancia del curso.');
    }
}




    public function edit($instanciaId)
    {
        try{
            $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId);
        
            return view('cursos.instancias.edit', compact('instancia'));
        }
        catch(\Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al retornar la instancia a cursos.instancias.edit' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al retornar la instancia a cursos.instancias.edit.');
        }
        
        
    }
    
    public function update(Request $request, $instanciaId)
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
    
            // Solo valida si fecha_fin no es nula
            if ($request->input('fecha_fin') !== null && $request->input('fecha_fin') < $request->input('fecha_inicio')) {
                return redirect()->back()->withInput()->withErrors(['fecha_fin' => 'La fecha de fin debe ser mayor o igual que la fecha de inicio.']);
            }
            $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId);
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
        $curso = $this->cursoService->getById($cursoId);
        
        return view('cursos.instancias.inscriptos', compact('curso', 'inscritos'));
    }

    public function getCountAsistentes(int $instanciaId)
    {  
        $inscritos = $this->enrolamientoCursoService->getPersonsByInstanceId($instanciaId, $cursoId);
        $countInscritos = $inscritos->count();
        return view('cursos.instancias.index', compact('countInscritos'));
    }

    

   
    public function getPersonas(int $cursoId, int $instanciaId)
    {
        
        $instancia = $this->cursoInstanciaService->getInstanceById($instanciaId);
        $curso = $this->cursoService->getById($cursoId);

        $personas = $this->personaService->getAll();

        $personasEnroladas = $this->enrolamientoCursoService->getPersonsByInstanceId($instancia->id_instancia, $curso->id);

        $enroladasIds = $personasEnroladas->pluck('id_persona')->toArray();

        // Añadir el estado de enrolamiento a cada persona
        $personasConEstado = $personas->map(function ($persona) use ($enroladasIds) {
            // Verificar si la persona está enrolada
            $persona->estadoEnrolado = in_array($persona->id_p, $enroladasIds); // true si está enrolado, false si no
            
            return $persona;
        });

        
        return view('cursos.instancias.personas', compact('personasConEstado', 'curso', 'instancia'));
    }


    /*public function InscribirPersona(int $id_persona, int $instancia_id, int $numInstancia)
    {
        $user = $this->personaService->getById($id_persona);
        $estadoEnrolado = $this->enrolamientoCursoService->isEnrolled($user->dni, $numInstancia);

        if (!$estadoEnrolado) {
            $enrolamiento = $this->enrolamientoCursoService->enroll($user->dni, $instancia_id, $numInstancia);
            return redirect()->back()->with('success', 'Inscripción exitosa.');
                                    
        } else {
            return redirect()->back()->with('error', 'La persona ya está enrolada en este curso.');
                                    
        }
    }*/
    

    public function inscribirVariasPersonas(Request $request, int $instancia_id, int $numInstancia)
{
    // Obtener el array de personas seleccionadas
    $personasSeleccionadas = $request->input('personas', []);
    
    // Validar si se seleccionaron personas
    if (empty($personasSeleccionadas)) {
        return redirect()->back()->with('error', 'No se seleccionaron personas para inscribir.');
    }

    // Recorrer todas las personas seleccionadas y hacer la inscripción
    foreach ($personasSeleccionadas as $id_persona => $inscribir) {
        $user = $this->personaService->getById($id_persona);
        
        // Inscripción del usuario
        $this->enrolamientoCursoService->enroll($user->dni, $instancia_id, $numInstancia);
    }

    // Redirigir con el mensaje de éxito
    return redirect()->back()->with('success', 'Las personas seleccionadas han sido inscriptas exitosamente.');
}

    
    


   
   
   


   

}
