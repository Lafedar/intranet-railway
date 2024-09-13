<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evento;
use DB;

class EventosController extends Controller
{
    public function index()
    {
    
       $personas=Evento::getPersons();
       $salas = Evento::getSalas();
        return view('eventos.index', compact('personas', 'salas'));
        
    }

    public function store(Request $request)
    {
        
        $datosEvento = $request->except(['_token', '_method']);
        $evento = Evento::createEvento($datosEvento);
        return response()->json($evento);

    }

    public function show()
    {
        $data['eventos']=Evento::all();
        return response()->json($data['eventos']);

    }
    
    public function update(Request $request, $id)
    {
        $datosEvento = $request->except(['_token', '_method']);
        $evento = Evento::findOrFail($id);
        $evento->updateEvento($datosEvento);

        return response()->json($evento);
    }

    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);
        $evento->deleteEvento();
        return response()->json(['message' => 'Evento eliminado correctamente']);
    }
}

