<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Permiso;
use App\Empleado;
use App\User;
use Auth;
use DB;
use Session;
use App\Models\MiAgenda;
use App\Empresa;
use Illuminate\Routing\Controller;

class PersonaController extends Controller
{
    public function index(Request $request)
    {
        $personas = MiAgenda::orderBy('nombre', 'asc')
            ->Empresa($request->get('empresa'))
            ->Nombre($request->get('nombre'))
            ->paginate(50);

        return view('persona.index', array('personas' => $personas, 'nombre' => $request->get('nombre'), 'empresa' => $request->get('empresa')));
    }

    public function create()
    {

        return view('persona.create');
    }

    public function store(Request $request)
    {
        $creador = auth()->user()->id;

        $personas = new Persona;
        $personas->nombre = $request['nombre'];
        $personas->apellido = $request['apellido'];
        $personas->direccion = $request['direccion'];
        $personas->empresa = $request['empresa'];
        $personas->interno = $request['interno'];
        $personas->telefono = $request['telefono'];
        $personas->celular = $request['celular'];
        $personas->correo = $request['correo'];
        $personas->creador = $creador;
        $personas->save();
        Session::flash('message', 'Contacto agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('persona');
    }

    public function show($id)
    {
        //        
    }

    public function edit($id)
    {
        $personas = Persona::find($id);
        return view('persona.edit', ['persona' => $personas]);
    }

    public function update(Request $request, $id)
    {
        $modificador = auth()->user()->id;

        $personas = Persona::find($request['id']);

        $personas->nombre = $request['nombre'];
        $personas->apellido = $request['apellido'];
        $personas->direccion = $request['direccion'];
        $personas->empresa = $request['empresa'];
        $personas->interno = $request['interno'];
        $personas->telefono = $request['telefono'];
        $personas->celular = $request['celular'];
        $personas->correo = $request['correo'];
        $personas->modificador = $modificador;
        $personas->save();

        Session::flash('message', 'Contacto modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('persona');
    }

    public function destroy($request, $id)
    {
    }

    public function destroy_contacto(Request $request, $id)
    {
        $personas = Persona::find($id);
        $personas->delete();

        return response()->json([
            'message' => 'Contacto eliminado con éxito'
        ]);

    }
    public function buscar(Request $request)
    {
        $dni = $request->input('dni');
        \Log::info("DNI recibido: " . $dni); // Esto va al storage/logs/laravel.log
        $persona = Persona::where('dni', $dni)->first();

        if ($persona) {
            return response()->json($persona, 200);
        } else {
            return response()->json(['error' => 'Persona no encontrada'], 404);
        }
    }

    public function checkMail(Request $request)
    {
        $mail = $request->input('mail');

        $bandera = DB::table('personas')->where('correo', $mail)->exists();

        if ($bandera) {
            return response()->json($bandera);
        } else {
            return response()->json(['error' => 'Persona no encontrada'], 404);
        }
    }

}
