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
        'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $novedad = new Novedad();
    $novedad->titulo = $request->titulo;
    $novedad->descripcion = $request->descripcion;

    $imagenPaths = []; // Array para almacenar las rutas

    if ($request->hasFile('imagenes')) {
        foreach ($request->file('imagenes') as $imagen) {
            $path = $imagen->store('images', 'public'); // Almacena la imagen y obtiene el path
            $imagenPaths[] = $path; // Agrega el path al array
        }
    }

    $novedad->imagen = implode(',', $imagenPaths); // Almacena las rutas como un string separado por comas
    $novedad->save();

    return redirect()->route('novedades.index')->with('success', 'Novedad creada con éxito');
}




}
