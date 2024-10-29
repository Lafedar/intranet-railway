<?php

namespace App\Http\Controllers;

use App\Services\CursoService;
use App\Services\CursoInstanciaService;
use Illuminate\Http\Request;

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
        $cursos = $this->cursoService->getAll()->sortByDesc('created_at'); 
        return view('cursos.index', compact('cursos'));
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
            return redirect()->route('cursos.index')->withErrors($e->getMessage());
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
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:100',
            'descripcion' => 'string|max:65530',
            'obligatorio' => 'required|boolean',
            'codigo' => 'nullable|string',
            'tipo' => 'required|string',
        ]);

        try {
            $curso = $this->cursoService->create($validatedData);
            return redirect()->route('cursos.index')->with('success', 'Curso creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('cursos.create')->withErrors('Error al crear el curso: ' . $e->getMessage());
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
            return redirect()->route('cursos.index')->withErrors($e->getMessage());
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

        try {
            $this->cursoService->update($curso, $validatedData);
            return redirect()->route('cursos.index')->with('success', 'Curso actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('cursos.edit', $id)->withErrors('Error al actualizar el curso: ' . $e->getMessage());
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
            return redirect()->route('cursos.index')->withErrors($e->getMessage());
        }
    }
    }

   

