<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evento;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\PersonaService;

class EventosController extends Controller
{
    private PersonaService $personaService;

    public function __construct(PersonaService $personaService)
    {

        $this->personaService = $personaService;
    }


    public function index()
    {
        $personas = $this->personaService->getAll();
        $salas = Evento::getSalas();
        return view('eventos.index', compact('personas', 'salas'));

    }


    public function store(Request $request)
    {

        $datosEvento = $request->except(['_token', '_method']);
        $validatedData = $this->validateEvento($datosEvento);
        $evento = Evento::create($validatedData);

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
        $validatedData = $this->validateEvento($datosEvento);
        $evento = Evento::findOrFail($id);
        $evento->update($validatedData);
    }

    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);
        $evento->deleteEvento();

    }
    public function validateEvento(array $data)
    {
        $rules = [
            'sala' => 'required|string|max:50',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'pedido_por' => 'required|string|max:255',
            'color' => 'required|string',
            'textColor' => 'required|string',
            'start' => 'required',
            'end' => 'required'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}

