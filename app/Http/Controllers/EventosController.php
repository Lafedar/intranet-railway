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

    }

    public function show()
    {
        $eventos = Evento::all()->map(function ($evento) {
            return [
                'id' => $evento->id,
                'title' => $evento->sala, 
                'start' => $evento->start,
                'titulo' => $evento->titulo,
                'end' => $evento->end,
                'descripcion' => $evento->descripcion,
                'pedido_por' => $evento->pedido_por,
                'color' => $evento->color,
                'textColor' => $evento->textColor,
                'sala' => $evento->sala,
                
            ];
        });
    
        return response()->json($eventos);
    
    }
    
    public function update(Request $request, $id)
    {
        $datosEvento = $request->except(['_token', '_method']);
        $evento = Evento::findOrFail($id);
        $evento->updateEvento($datosEvento);
    }

    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);
        $evento->deleteEvento();
        
    }
}

