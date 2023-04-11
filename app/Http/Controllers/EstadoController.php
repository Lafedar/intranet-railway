<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Estado;
use App\User;
Use Session;
use DB;


class EstadoController extends Controller
{
    public function index(Request $request)
    {
        $estados = Estado::Index($request->get('estados'))
        ->paginate(20);

        return view ('estados.index',   
            array('estados' => $estados));
    }

    public function show_store_estado()
    {
        return view('estados.create');       
    }

    public function store_estado(Request $request)
    {        
        //consulta en bd si existe el id
        $aux = DB::table('estados')->where('estados.id', $request['id'])->first(); 

        //mensaje de id existente
        if($aux){
            Session::flash('message','ID ingresado ya se encuentra asignado');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back()->withInput();
        }
        
        $estado = new Estado;
        $estado->id = $request['id'];
        $estado->nombre = $request['nombre'];
        
        $estado->save();

        Session::flash('message','Estado agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('estados');
    }

    public function update_estado(Request $request)
    {
        $estado = DB::table('estados')
        ->where('estados.id',$request['id'])
        ->update([
            'nombre' => $request['nombre']
        ]);      
        Session::flash('message','Estado modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('estados');
    }
    
    public function show_update_estado($id)
    {
        $estado = DB::table('estados')
        ->select('estados.id as id', 'estados.nombre as nombre')
        ->where('estados.id', $id)
        ->first();

        return view('estados.update', ['estado' => $estado]);       
    }
}
