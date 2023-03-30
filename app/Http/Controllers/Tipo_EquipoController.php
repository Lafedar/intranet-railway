<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Tipo_Equipo;
use App\Fallaxtipo;
use App\User;
Use Session;
use DB;


class Tipo_EquipoController extends Controller
{
    public function index(Request $request)
    {
        $tipos_equipos = Tipo_Equipo::Index($request->get('tipos_equipos'))
        ->paginate(20);

        $fallas = DB::table('fallasxtipo')
        ->leftjoin('fallas', 'fallas.id', 'fallasxtipo.id_falla')
        ->leftjoin('tipo_solicitudes', 'tipo_solicitudes.id', 'fallasxtipo.id_tipo_solicitud')
        ->select('fallasxtipo.id_tipo_equipo as id_tipo_equipo', 'fallas.nombre as nom_falla', 'tipo_solicitudes.nombre as nom_tipo_solicitud')
        ->get();

        return view ('tipos_equipos.index', array('tipos_equipos'=>$tipos_equipos, 'fallas'=>$fallas));
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

    public function show_update_tipo_equipo($id)
    {
        $tipo_equipo = DB::table('tipos_equipos')
        ->select('tipos_equipos.id as id', 'tipos_equipos.nombre as nombre')
        ->where('tipos_equipos.id', $id)
        ->first();

        return view('tipos_equipos.update', ['tipo_equipo' => $tipo_equipo]);       
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

    public function show_assing_tipo_equipo($id)
    {
        //migrar a modelo
        $tipo_equipo = Tipo_Equipo::
            find($id);

        return view('tipos_equipos.assing', [
            'tipo_equipo' => $tipo_equipo
        ]);       
    }

    public function assing_tipo_equipo(Request $request)
    {
        $fallaxtipo = new Fallaxtipo;
        $fallaxtipo->id_tipo_equipo = $request['id_tipo_equipo'];
        $fallaxtipo->id_falla = $request['fallas'];
        if($request['id_tipo_equipo'] == 0){
            $fallaxtipo->id_tipo_solicitud = 2;
        }
        else{$fallaxtipo->id_tipo_solicitud = 1;}

        $fallaxtipo->save();

        Session::flash('message','Falla asignada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('tipos_equipos');
    }
    
    public function select_fallas()
    {
        return [DB::table('fallas')->get(), DB::table('fallasxtipo')->get()];
    }
}
