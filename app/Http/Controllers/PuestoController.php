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


class PuestoController extends Controller
{
   public function puestos(Request $request)
   {
    $puestos = Puesto::Relaciones()            
    ->Puesto($request->get('puesto'))
    ->Usuario($request->get('usuario'))
    ->Area($request->get('area'))
    ->orderBy('desc_puesto','asc')
    ->paginate(20);

    return view ('puestos.puestos', array('puestos'=>$puestos, 'puesto'=>$request->get('puesto'),'usuario'=>$request->get('usuario'), 'area'=>$request->get('area')));
}

public function select_area(){
    return DB::table('area')->get();
}
public function select_persona(){

    $aux = DB::table('puestos')->where('persona','!=',null)->get();
    foreach ($aux as $aux1) {
        $data[] = $aux1->persona;
    }

    return DB::table('personas')->whereNotIn('id_p', $data)->where('personas.activo',1)->orderBy('personas.apellido', 'asc')->get();
}


public function store(Request $request)
{
    $puesto= new Puesto;
    $puesto->desc_puesto = $request['desc_puesto'];
    $puesto->area = $request['area'];
    $puesto->persona = $request['persona'];
    $puesto->obs = $request['obs'];
    $puesto->telefono_ip = $request['telefono_ip'];
    $puesto->save();

    Session::flash('message','Puesto agregado con éxito');
    Session::flash('alert-class', 'alert-success');

    return redirect('puestos');
}

public function edit_puesto($id)
{   
    $puestos = DB::table('puestos')
    ->leftjoin('personas','puestos.persona','personas.id_p')
    ->leftjoin('area','puestos.area','area.id_a')
    ->where('puestos.id_puesto',$id)
    ->first();

    $areas = DB::table('area')->get();

    $personas = DB::table('personas')->get();

    return view ('puestos.edit_puesto', array('puesto' => $puestos,'area' => $areas, 'personas' => $personas));
}


public function update_puesto(Request $request)
{   
    $puesto = DB::table('puestos')
    ->where('puestos.id_puesto',$request['id'])
    ->update([
        'desc_puesto' => $request['desc_puesto'],
        'area' => $request['area'],
        'persona' => $request['persona'],
        'obs' => $request['obs'],
        'telefono_ip' => $request['telefono_ip'],
    ]);      

    Session::flash('message','Puesto modificado con éxito');
    Session::flash('alert-class', 'alert-success');
    
    return redirect('puestos');
}

}
