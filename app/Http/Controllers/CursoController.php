<?php

namespace App\Http\Controllers;

use App\Services\CursoService;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    private CursoService $cursoService;

    public function __construct(CursoService $cursoService)
    {
        $this->cursoService = $cursoService;
    }

    /**
     * Mostrar una lista de todos los cursos.
     *
     * @return \Illuminate\View\View
     */
    public function listAll()
    {
        $cursos = $this->cursoService->getAll();
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
        $curso = $this->cursoService->getById($id);

        if (!$curso) {
            return redirect()->route('cursos.index')->withErrors('El curso no fue encontrado.');
        }

        return view('cursos.show', compact('curso'));
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
        $data = $request->all();
        $this->cursoService->create($data);

        return redirect()->route('cursos.index')->with('success', 'Curso creado exitosamente.');
    }

    /**
     * Mostrar el formulario para editar un curso existente.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $curso = $this->cursoService->getById($id);

        if (!$curso) {
            return redirect()->route('cursos.index')->withErrors('El curso no fue encontrado.');
        }

        return view('cursos.edit', compact('curso'));
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

        $data = $request->all();
        $this->cursoService->update($curso, $data);

        return redirect()->route('cursos.show', $id)->with('success', 'Curso actualizado exitosamente.');
    }

    /**
     * Eliminar un curso de la base de datos.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $curso = $this->cursoService->getById($id);

        if (!$curso) {
            return redirect()->route('cursos.index')->withErrors('El curso no fue encontrado.');
        }

        $this->cursoService->delete($curso);

        return redirect()->route('cursos.index')->with('success', 'Curso eliminado exitosamente.');
    }
}
