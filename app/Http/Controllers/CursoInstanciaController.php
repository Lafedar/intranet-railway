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
    private $personasService;

    public function __construct(CursoInstanciaService $cursoInstanciaService, CursoService $cursoService, EnrolamientoCursoService $enrolamientoCursoService, PersonasService $personasService)
    {
        $this->cursoInstanciaService = $cursoInstanciaService;
        $this->cursoService = $cursoService;
        $this->enrolamientoCursoService = $enrolamientoCursoService;
        $this->personasService = $personasService;
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


    
public function destroy(int $id)
{
    try {
        $instancia = $this->cursoInstanciaService->getInstanceById($id);

        if (!$instancia) {
            return redirect()->route('cursos.instancias.index', ['cursoId' => $cursoId])
                             ->withErrors('La instancia no fue encontrada.');
        }
        
        $cursoId = $instancia->id_curso; // Obtengo el ID del curso de la instancia
        $id_instancia = $instancia->id_instancia;
       
        // Obtener los enrolados de la instancia específica
        $enrolados = $this->enrolamientoCursoService->getPersonsByInstanceId($id_instancia, $cursoId);
        
        // Elimina los enrolados de esa instancia
        foreach ($enrolados as $enrolado) {
            $deleted = $this->enrolamientoCursoService->delete($enrolado);
            if (!$deleted) {
                Log::error('Error al eliminar el enrolado: ' . $enrolado->id);
            }
        }

        // Eliminar la instancia
        $this->cursoInstanciaService->delete($instancia);
        
        return redirect()->route('cursos.instancias.index', ['cursoId' => $cursoId])
                         ->with('success', 'Instancia eliminada exitosamente.');
    } catch (\Exception $e) {
        Log::error('Error in class: ' . get_class($this) . ' .Error al eliminar la instancia del curso: ' . $e->getMessage());
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
        $curso = $this->cursoService->getById($cursoId);
        
        return view('cursos.instancias.inscriptos', compact('curso', 'inscritos'));
    }


    public function getPersonas(){
        $personas = $this -> personasService->getAll();
        return view('cursos.instancias.personas', compact('personas'));
    }
}
