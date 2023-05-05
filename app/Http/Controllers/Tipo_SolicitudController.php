<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Tipo_Solicitud;
use App\User;
Use Session;
use DB;


class Tipo_SolicitudController extends Controller
{
    public function index(Request $request)
    {
        $tipos_solicitudes = Tipo_Solicitud::Index($request->get('tipos_solicitudes'))
        ->paginate(20);

        return view ('tipos_solicitudes.index',   
            array('tipos_solicitudes' => $tipos_solicitudes));
    }

    public function show_store_tipo_solicitud()
    {
        return view('tipos_solicitudes.create');       
    }

    public function store_tipo_solicitud(Request $request)
    {        
        $tipo_solicitud = new Tipo_Solicitud;
        $tipo_solicitud->id = $request['id'];
        $tipo_solicitud->nombre = $request['nombre'];
        
        $tipo_solicitud->save();

        Session::flash('message','Tipo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('tipos_solicitudes');
    }

    public function update_tipo_solicitud(Request $request)
    {
        $tipo_solicitud = DB::table('tipo_solicitudes')
        ->where('tipo_solicitudes.id',$request['id'])
        ->update([
            'nombre' => $request['nombre']
        ]);      
        Session::flash('message','Tipo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('tipos_solicitudes');
    }
    
    public function show_update_tipo_solicitud($id)
    {
        $tipo_solicitud = DB::table('tipo_solicitudes')
        ->select('tipo_solicitudes.id as id', 'tipo_solicitudes.nombre as nombre')
        ->where('tipo_solicitudes.id', $id)
        ->first();

        return view('tipos_solicitudes.update', ['tipo_solicitud' => $tipo_solicitud]);       
    }
}
