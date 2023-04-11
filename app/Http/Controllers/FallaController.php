<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Falla;
use App\User;
Use Session;
use DB;


class FallaController extends Controller
{
    public function index(Request $request)
    {
        $fallas = Falla::Index($request->get('fallas'))
        ->paginate(20);

        return view ('fallas.index',   
            array('fallas' => $fallas));
    }

    public function show_store_falla()
    {
        return view('fallas.create');       
    }

    public function store_falla(Request $request)
    {        
        //consulta en bd si existe el id
        $aux = DB::table('fallas')->where('fallas.id', $request['id'])->first(); 

        //mensaje de id existente
        if($aux){
            Session::flash('message','ID ingresado ya se encuentra asignado');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back()->withInput();
        }
        
        $falla = new Falla;
        $falla->id = $request['id'];
        $falla->nombre = $request['nombre'];
        
        $falla->save();

        Session::flash('message','Falla agregada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('fallas');
    }

    public function update_falla(Request $request)
    {
        $falla = DB::table('fallas')
        ->where('fallas.id',$request['id'])
        ->update([
            'nombre' => $request['nombre']
        ]);      
        Session::flash('message','Falla modificada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('fallas');
    }
    
    public function show_update_falla($id)
    {
        $falla = DB::table('fallas')
        ->select('fallas.id as id', 'fallas.nombre as nombre')
        ->where('fallas.id', $id)
        ->first();

        return view('fallas.update', ['falla' => $falla]);       
    }
}
