<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Tipo_Equipo;
use App\User;
Use Session;
use DB;


class Tipo_EquipoController extends Controller
{
    public function index(Request $request)
    {
        $tipos_equipos = Tipo_Equipo::Index($request->get('tipos_equipos'))
        ->paginate(20);

        return view ('tipos_equipos.index',   
            array('tipos_equipos' => $tipos_equipos));
    }

    public function show_store_tipo_equipo()
    {
        return view('tipos_equipos.create');       
    }

    public function store_tipo_equipo(Request $request)
    {        
        //consulta en bd si existe el id
        $aux = DB::table('tipos_equipos')->where('tipos_equipos.id', $request['id'])->first(); 

        //mensaje de id existente
        if($aux){
            Session::flash('message','ID ingresado ya se encuentra asignado');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back()->withInput();
        }
        
        $tipo_equipo = new Tipo_Equipo;
        $tipo_equipo->id = $request['id'];
        $tipo_equipo->nombre = $request['nombre'];
        
        $tipo_equipo->save();

        Session::flash('message','Tipo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('tipos_equipos');
    }

    public function update_tipo_equipo(Request $request)
    {
        $tipo_equipo = DB::table('tipos_equipos')
        ->where('tipos_equipos.id',$request['id'])
        ->update([
            'nombre' => $request['nombre']
        ]);      
        Session::flash('message','Tipo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('tipos_equipos');
    }
    
    public function show_update_tipo_equipo($id)
    {
        $tipo_equipo = DB::table('tipos_equipos')
        ->select('tipos_equipos.id as id', 'tipos_equipos.nombre as nombre')
        ->where('tipos_equipos.id', $id)
        ->first();

        return view('tipos_equipos.update', ['tipo_equipo' => $tipo_equipo]);       
    }
}
