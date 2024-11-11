<?php

namespace App\Http\Controllers;

use App\Services\CursoService;
use App\Services\CursoInstanciaService;
use App\Services\AreaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\EnrolamientoCursoService;
use App\Area;
use App\Models\Curso;
class CursoController extends Controller
{
    private CursoService $cursoService;
    private CursoInstanciaService $cursoInstanciaService;
    private EnrolamientoCursoService $enrolamientoCursoService;
    private AreaService $areaService;

    public function __construct(CursoService $cursoService, CursoInstanciaService $cursoInstanciaService, EnrolamientoCursoService $enrolamientoCursoService, AreaService $areaService)
    {
        $this->cursoService = $cursoService;
        $this->cursoInstanciaService = $cursoInstanciaService;
        $this->enrolamientoCursoService = $enrolamientoCursoService;
        $this->areaService = $areaService;
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

        $cursosData = $this->cursoService->getAll()->load('areas');

        if ($nombreCurso) {
            $cursosData = $cursosData->filter(function ($curso) use ($nombreCurso) {
                return str_contains(strtolower($curso->titulo), strtolower($nombreCurso));
            });
        }

      
        if ($areaId) {
            $cursosData = $cursosData->filter(function ($curso) use ($areaId) {
                return $curso->areas->contains('id_a', $areaId);
            });
        }

        $cursosData = $cursosData->sortByDesc('created_at');

        $cursosData = $cursosData->map(function ($curso) {
            $curso->cantInscriptos = $this->enrolamientoCursoService->getCountPersonas($curso->id);
            return $curso;
        });

        $areas = $this->areaService->getAll();

        return view('cursos.index', compact('cursosData', 'areas', 'nombreCurso', 'areaId'));

    } catch (\Exception $e) {
        Log::error('Error al mostrar los cursos: ' . $e->getMessage());
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
                throw new \Exception('El curso no fue encontrado.');
            }
            return view('cursos.show', compact('curso'));
        } catch (\Exception $e) {
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
        $areas = Area::all();  // Recupera todas las áreas

        return view('cursos.create', compact('areas'));
    }

    /**
     * Almacenar un nuevo curso en la base de datos.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */


    
    
    public function store(Request $request)
{
    try {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:65530',
            'obligatorio' => 'required|boolean',
            'codigo' => 'nullable|string',
            'tipo' => 'required|string',
            'area' => 'required|array',  
        ]);

    
        $curso = $this->cursoService->create($validatedData);
        $curso->areas()->attach($validatedData['area']);  // Usar attach para asociar las áreas

        
        return redirect()->route('cursos.index')->with('success', 'Curso creado exitosamente.');
    } catch (\Exception $e) {
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
            $areas = Area::all();
            if (!$curso) {
                throw new \Exception('El curso no fue encontrado.');
            }
            return view('cursos.edit', compact('curso', 'areas'));
        } catch (\Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al obtener el curso: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener el curso.');
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
    
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'titulo' => 'required|string|max:100',
                'descripcion' => 'nullable|string|max:65530',
                'obligatorio' => 'required|boolean',
                'codigo' => 'nullable|string',
                'tipo' => 'required|string',
                'area' => 'nullable|array',  // Asegúrate de que el campo sea un array y sea opcional
            ]);
    
            // Actualizar el curso
            $curso->update($validatedData);
    
            // Si 'area' está presente en los datos validados, sincronizar las relaciones
            if (isset($validatedData['area'])) {
                $curso->areas()->sync($validatedData['area']); // Sincroniza las áreas seleccionadas
            }
    
            return redirect()->route('cursos.index')->with('success', 'Curso actualizado exitosamente.');
    
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el curso: ' . $e->getMessage());
            Log::error('Error al actualizar el curso: ' . $e->getMessage());
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
                throw new \Exception('El curso no fue encontrado.');
            }
            
            
            $instancias = $this->cursoInstanciaService->getInstancesByCourse($id);
            
            // Eliminar los enrolamientos de cada instancia
            foreach ($instancias as $instancia) {
                $this->enrolamientoCursoService->deleteByInstanceId($curso->id, $instancia->id); 
                $this->cursoInstanciaService->delete($instancia);
            }

            
            $this->cursoService->delete($curso);
            return redirect()->route('cursos.index')->with('success', 'El curso y sus instancias fueron eliminados exitosamente.');
        } catch (\Exception $e) {
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
        catch(\Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al obtener los incriptos del curso: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener los incriptos del curso.');
        }
       
    }
   


    
    }

   

