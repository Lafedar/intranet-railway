<?php

namespace App\Http\Controllers;

use App\Services\CursoService;
use App\Services\CursoInstanciaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CursoController extends Controller
{
    private CursoService $cursoService;
    private CursoInstanciaService $cursoInstanciaService;

    public function __construct(CursoService $cursoService, CursoInstanciaService $cursoInstanciaService)
    {
        $this->cursoService = $cursoService;
        $this->cursoInstanciaService = $cursoInstanciaService;
    }

    /**
     * Mostrar una lista de todos los cursos.
     *
     * @return \Illuminate\View\View
     */
    public function listAll()
    {
        try{
            $cursos = $this->cursoService->getAll()->sortByDesc('created_at'); 
            return view('cursos.index', compact('cursos'));
        }
        catch(\Exception $e)
        {
            // Registrar el error y redirigir con un mensaje de error
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al mostrar los cursos: ' . $e->getMessage());
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
        return view('cursos.create');
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
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:100',
            'descripcion' => 'string|max:65530',
            'obligatorio' => 'required|boolean',
            'codigo' => 'nullable|string',
            'tipo' => 'required|string',
        ]);

        
            $curso = $this->cursoService->create($validatedData);
           
            return redirect()->route('cursos.index')->with('success', 'Curso creado exitosamente.');
        } catch (\Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            session()->flash('error', 'Error al crear el curso: ' . $e->getMessage());
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al crear el curso: ' . $e->getMessage());
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
            if (!$curso) {
                throw new \Exception('El curso no fue encontrado.');
            }
            return view('cursos.edit', compact('curso'));
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
        try{

        
            $curso = $this->cursoService->getById($id);

            if (!$curso) {
                return redirect()->route('cursos.index')->withErrors('El curso no fue encontrado.');
            }

            $validatedData = $request->validate([
                'titulo' => 'required|string|max:100',
                'descripcion' => 'string|max:65530',
                'obligatorio' => 'required|boolean',
                'codigo' => 'nullable|string',
                'tipo' => 'required|string',
            ]);

        
            $this->cursoService->update($curso, $validatedData);
            
            return redirect()->route('cursos.index')->with('success', 'Curso actualizado exitosamente.');
        } catch (\Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            session()->flash('error', 'Error al actualizar el curso: ' . $e->getMessage());
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al actualizar el curso: ' . $e->getMessage());
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

            // Obtener las instancias asociadas al curso
            $instancias = $this->cursoInstanciaService->getInstancesByCourse($id);

            // Eliminar las instancias
            foreach ($instancias as $instancia) {
                $this->cursoInstanciaService->delete($instancia);
            }

            // Eliminar el curso
            $this->cursoService->delete($curso);
            return redirect()->route('cursos.index')->with('success', 'Curso y sus instancias eliminados exitosamente.');
        } catch (\Exception $e) {
            // Registrar el error y redirigir con un mensaje de error
            session()->flash('error', 'Error al eliminar el curso: ' . $e->getMessage());
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al eliminar el curso: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al eliminar el curso.');
        }
    }
    }

   

