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
        $parametros = DB::table('parametros_mant')->get();
        return view('parametros_gen.index', compact('parametros'));
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
        $request->validate([
            'Nombre' => 'required',
            'Informacion' => 'required',
        ]);

        DB::table('parametros_mant')->insert([
            'Nombre' => $request->Nombre,
            'Informacion' => $request->Informacion,
        ]);

        return redirect()->back()->with('success', 'Parámetro agregado correctamente.');
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
       // Validar los datos del formulario
       $request->validate([
        'nombre' => 'required|string|max:255',
        'informacion' => 'required|string|max:255',
    ]);

    try {
        // Actualizar el registro en la base de datos
        DB::table('parametros_mant')->where('Id', $id)->update([
            'Nombre' => $request->nombre,
            'Informacion' => $request->informacion,
        ]);

        // Mensaje de éxito
        return redirect()->back()->with('success', 'Parámetro actualizado correctamente');
    } catch (\Exception $e) {
        // Mensaje de error
        return redirect()->back()->with('error', 'Error al actualizar el parámetro: ' . $e->getMessage());
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Eliminar el parámetro de la base de datos
            DB::table('parametros_mant')->where('Id', $id)->delete();
            
            // Mensaje de éxito
            return redirect()->back()->with('success', 'Parámetro eliminado correctamente');
        } catch (\Exception $e) {
            // Mensaje de error
            return redirect()->back()->with('error', 'Error al eliminar el parámetro: ' . $e->getMessage());
        }
    }
}
