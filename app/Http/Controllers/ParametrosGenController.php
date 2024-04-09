<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ParametrosGenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Recuperar los datos de la base de datos
        $datos = DB::table('parametros_mant')->get(); // Reemplaza 'tu_tabla' con el nombre de tu tabla

        // Pasar los datos a la vista
        return view('parametros_gen.index', ['datos' => $datos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // Valida los datos recibidos del formulario
    $request->validate([
        'Nombre' => 'required',
        'Informacion' => 'required',
    ]);

    // Guarda los datos en la base de datos
    DB::table('parametros_mant')->insert([
        'Nombre' => $request->Nombre,
        'Informacion' => $request->Informacion,
    ]);

    // Redirige de vuelta al índice con un mensaje de éxito
    return redirect()->route('parametros_gen.index')->with('success', 'Los datos se han guardado correctamente');
    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
