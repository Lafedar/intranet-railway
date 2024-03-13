<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evento;
use DB;

class EventosController extends Controller
{
    //
     //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        $personas=DB::table('personas')->orderBy('personas.nombre_p', 'asc')->get();
        return view ('eventos.index',  compact('personas'));
        
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
        
        $datosEvento=request()->except(['_token','_method']);
        

        Evento::insert($datosEvento);
        print_r($datosEvento);


      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
        $data['eventos']=Evento::all();
        return response()->json($data['eventos']);

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
        $datosEvento=request()->except(['_token','_method']);

        $respuesta=Evento::where('id','=',$id)->update($datosEvento);
        return response()->json($respuesta);

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
        $eventos = Evento::findOrFail($id);
        Evento::destroy($id);
        return response()->json($id);
    }
}
