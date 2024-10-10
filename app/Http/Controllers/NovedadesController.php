<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use Auth;
use DB;
Use Session;
use App\Novedad;
use GuzzleHttp\Client;



use Illuminate\Routing\Controller;

class NovedadesController extends Controller
{
    public function index()
{
    // Obtener todas las novedades
    $novedades = Novedad::all();

    // Pasar las novedades a la vista
    return view('novedades.index', compact('novedades'));
}

public function store(Request $request)
{
    
    $request->validate([
        'titulo' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $novedad = new Novedad();
    $novedad->titulo = $request->titulo;
    $novedad->descripcion = $request->descripcion;
    $novedad->created_at = now(); // Establece la fecha actual

    if ($request->hasFile('imagen')) {
        $path = $request->file('imagen')->store('images', 'public');
        $novedad->imagen = $path;
    }

    $novedad->save();

    return redirect()->route('novedades.index')->with('success', 'Novedad creada con éxito');
}




}
