<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ParametrosGenSistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parametros = DB::table('parametros_mant')->get();
        return view('parametros_gen_sistemas.index', compact('parametros'));
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
            'id_param' => 'required',
            'descripcion_param' => 'required',
            'valor_param' => 'required',
            'origen' => 'required',
        ]);

        DB::table('parametros_mant')->insert([
            'id_param' => $request->id_param,
            'descripcion_param' => $request->descripcion_param,
            'valor_param' => $request->valor_param,
            'origen' => $request->origen,
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
       $request->validate([
        'descripcion_param' => 'required|string|max:255',
        'valor_param' => 'required|string|max:255',
       
    ]);

    try {
        DB::table('parametros_mant')->where('id_param', $id)->update([
            'descripcion_param' => $request->descripcion_param,
            'valor_param' => $request->valor_param,
            
        ]);

        return redirect()->back()->with('success', 'Parámetro actualizado correctamente');
    } catch (\Exception $e) {
        
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
            
            DB::table('parametros_mant')->where('id_param', $id)->delete();
            
            return redirect()->back()->with('success', 'Parámetro eliminado correctamente');
        } catch (\Exception $e) {
            
            return redirect()->back()->with('error', 'Error al eliminar el parámetro: ' . $e->getMessage());
        }
    }

   

public function obtenerMegabytesMaximos()
{
    $megabytesMaximos = DB::table('parametros_mant')
                        ->where('id_param', 'PMB')
                        ->value('valor_param');

    return response()->json(['megabytesMaximos' => $megabytesMaximos]);
}

}
