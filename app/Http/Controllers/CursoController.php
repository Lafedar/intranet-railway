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
        try{
            $nombreCurso = $request->input('nombre_curso', '');
            $areaId = $request->input('area_id', null);

            $userDni = auth()->user()->dni;
            $personaDni = $this->personaService->getByDni($userDni);
            $persona = $this->personaService->getById($personaDni->id_p);

            if (auth()->user()->hasRole(['administrador', 'Gestor-cursos'])) {
                $cursosData = $this->cursoService->getAll()->load('areas');
            } else {
                $cursosData = $this->enrolamientoCursoService->getCursosByUserId($persona->id_p); 
            
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

            $cursosData = $cursosData->map(function ($curso) {
                $curso->cantInscriptos = $this->enrolamientoCursoService->getCountPersonas($curso->id);
                
                // Ahora se calcula el porcentaje de aprobados para cada curso
                $curso->porcentajeAprobados = $this->enrolamientoCursoService->getPorcentajeAprobacion($curso->id, $curso->id);
                
                return $curso;
            });
            $cursosData = $cursosData->sortByDesc('curso.created_at');
            $areas = $this->areaService->getAll();
            $totalAreas = $areas->count();
            
           
        return view('cursos.index', compact('cursosData', 'areas', 'nombreCurso', 'areaId', 'totalAreas'));

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al mostrar los cursos: ' . $e->getMessage());
            
            return redirect()->back()->withErrors('Hubo un problema al mostrar los cursos.');
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
            return redirect()->back()->withErrors('Hubo un problema al mostrar el curso.');
        }
    }

    /**
     * Mostrar el formulario para crear un nuevo curso.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try{
            $areas = $this->areaService->getAll();  // Recupera todas las áreas
            $anexos = Anexo::select('formulario_id')->distinct()->get(); 
        
            return view('cursos.create', compact('areas', 'anexos'));
        }catch(Exception $e){
            // Registrar el error y redirigir con un mensaje de error
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al abrir la vista cursos.create: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al mostrar la vista cursos.create.');
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
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:253',
            'descripcion' => 'nullable|string|max:253',
            'obligatorio' => 'required|boolean',
            'codigo' => 'nullable|string',
            'tipo' => 'required|string',
            'area' => 'required|array',  
        ]);
        
    
        $curso = $this->cursoService->create($validatedData);
        $curso->areas()->attach($validatedData['area']);  // Usar attach para asociar las áreas
        
        
        return redirect()->route('cursos.index')->with('success', 'Curso creado exitosamente.');
    } catch (Exception $e) {
        // Registrar el error y redirigir con un mensaje de error
        Log::error('Error in class: ' . get_class($this) . ' .Error al crear el curso: ' . $e->getMessage());
        return redirect()->back()->withErrors('Hubo un problema al crear el curso.');
    }
}*/
public function store(Request $request)
{
    try {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:253',
            'descripcion' => 'nullable|string|max:253',
            'obligatorio' => 'required|boolean',
            'codigo' => 'nullable|string',
            'tipo' => 'required|string',
            'area' => 'required|array',
            'anexos' => 'nullable|array',  // Validación de anexos (pueden ser nulos o una lista de anexos)
        ]);
        
        // Crear el curso
        $curso = $this->cursoService->create($validatedData);
        
        // Asociar las áreas al curso
        $curso->areas()->attach($validatedData['area']);
        
        // Asociar los anexos a través de la tabla intermedia 'curso_anexo'
        /*if (isset($validatedData['anexos'])) {
            foreach ($validatedData['anexos'] as $formulario) {
                
                $curso->anexos()->attach($formulario);
            }
        }*/
        if (isset($validatedData['anexos'])) {
            foreach ($validatedData['anexos'] as $formularioId) {
                // Obtener el tipo del anexo por formulario_id
                $anexo = Anexo::where('formulario_id', $formularioId)->first();
        
                if ($anexo) {
                    // Asociar el curso con el anexo en la tabla intermedia incluyendo el tipo
                    $curso->anexos()->attach($formularioId, [
                        'tipo' => $anexo->tipo // Añadir el tipo a la relación
                    ]);
                } else {
                    // Manejar el caso si no se encuentra el anexo (opcional)
                    Log::error("Anexo no encontrado para formulario_id: $formularioId");
                }
            }
        }
        
        return redirect()->route('cursos.index')->with('success', 'Curso creado exitosamente.');
    } catch (Exception $e) {
        // Registrar el error y redirigir con un mensaje de error
        Log::error('Error al crear el curso: ' . $e->getMessage());
        return redirect()->back()->withErrors('Hubo un problema al crear el curso.');
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
            $anexos = Anexo::select('formulario_id')->distinct()->get(); 
            // Obtener los formulario_id relacionados con el curso
            $selectedAnexos = $curso->anexos->pluck('formulario_id')->toArray();
            if (!$curso) {
                throw new Exception('El curso no fue encontrado.');
            }
            return view('cursos.edit', compact('curso', 'areas', 'anexos', 'selectedAnexos'));
        } catch (Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al abrir la vista cursos.edit: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener la vista cursos.edit.');
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
                 'anexos' => 'nullable|array',
             ]);
     
             // Actualizar el curso con los datos validados
             $curso->update($validatedData);
     
             // Actualizar las áreas (sincroniza las áreas seleccionadas)
             if (isset($validatedData['area'])) {
                 $curso->areas()->sync($validatedData['area']);
             }
     
             // Sincronizar los anexos con los nuevos valores
             if (isset($validatedData['anexos'])) {
                 $anexos = [];
     
                 foreach ($validatedData['anexos'] as $formularioId) {
                     // Obtener el tipo del anexo por formulario_id
                     $anexo = Anexo::where('formulario_id', $formularioId)->first();
     
                     if ($anexo) {
                         // Preparar el anexo para sincronizar, incluyendo el tipo
                         $anexos[$formularioId] = [
                             'tipo' => $anexo->tipo // Añadir el tipo a la relación
                         ];
                     } else {
                         // Manejar el caso si no se encuentra el anexo (opcional)
                         Log::error("Anexo no encontrado para formulario_id: $formularioId");
                     }
                 }
     
                 // Sincronizar anexos con sus tipos
                 $curso->anexos()->sync($anexos);
             }
     
             return redirect()->route('cursos.index')->with('success', 'Curso actualizado exitosamente.');
     
         } catch (Exception $e) {
             session()->flash('error', 'Error al actualizar el curso: ' . $e->getMessage());
             Log::error('Error in class: ' . get_class($this) . ' .Error al actualizar el curso: ' . $e->getMessage());
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

            
            $this->cursoService->delete($curso);
            return redirect()->route('cursos.index')->with('success', 'El curso y sus instancias fueron eliminados exitosamente.');
        } catch (Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            session()->flash('error', 'Error al eliminar el curso: ' . $e->getMessage());
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al eliminar el curso: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al eliminar el curso.');
        }
    }



    public function getInscriptos(int $cursoId){
        try{
            $inscritos = $this->enrolamientoCursoService->getPersonsByCourseId($cursoId);
            $curso = $this->cursoService->getById($cursoId);
            return view('cursos.inscriptos', compact('inscritos', 'curso'));
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al obtener los incriptos del curso: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener los incriptos del curso.');
        }
       
    }
    


    
    }

   

