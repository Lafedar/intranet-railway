<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NovedadService;

class NovedadesController extends Controller
{
    protected $novedadService;

    public function __construct(NovedadService $novedadService)  /*inyecto dependencias*/
    {
        $this->novedadService = $novedadService;
    }

    public function index()
    {
        $novedades = $this->novedadService->getAll();
        return view('novedades.index', compact('novedades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $this->novedadService->create($request->all());

        return redirect()->route('novedades.index')->with('success', 'Novedad creada con éxito');
    }
}