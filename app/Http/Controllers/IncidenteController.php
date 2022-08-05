<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Equipamiento;
use App\Puesto;
use App\Relacion;
use App\Persona;
use App\Incidente;
use App\User;
use Auth;
use DB;
Use Redirect;
use Illuminate\Support\Facades\Input;
Use Session;
use Illuminate\Routing\Controller;
use Carbon\Carbon;

class IncidenteController extends Controller
{
   public function incidentes(Request $request){

  $incidentes = Incidente::busca($request->get('resuelto'))
  ->Equipamiento($request->get('equipamiento'))
  ->paginate(20);

  return view ('incidentes.incidentes', array('incidentes'=>$incidentes, 'equipamiento' => $request->get('equipamiento')));

}

public function store_incidente(Request $request)
{
    $incidente = new Incidente;
    $incidente->descripcion = $request['descripcion'];
    $incidente->solucion = $request['solucion'];
    $incidente->equipamiento = $request['equipamiento'];

    if($request['resuelto'] == 1){
        $incidente->resuelto = $request['resuelto'];
    }
    else{
        $incidente->resuelto = 0;
    }

    $incidente->save();

    Session::flash('message','Incidente agregado con éxito');
    Session::flash('alert-class', 'alert-success');

    return redirect('equipamiento');

}

public function update_incidente(Request $request)
{   
    $incidente = DB::table('incidentes')
    ->where('incidentes.id_i',$request['incidente'])
    ->update([
        'solucion' => $request['solucion'],
        'resuelto' => 1
    ]);      

    Session::flash('message','Incidente resuelto');
    Session::flash('alert-class', 'alert-success');

    return redirect('incidentes');
}
}
