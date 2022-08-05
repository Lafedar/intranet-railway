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

class EquipamientoController extends Controller
{   

    public function index(Request $request)
    {
        $tipo_equipamiento = DB::table('tipo_equipamiento')->orderBy('equipamiento','asc')->get();

        $equipamientos = Equipamiento::Ip($request->get('ip'))
        ->Equipo($request->get('equipo'))
        ->Relaciones($request->get('tipo'))
        ->Puesto($request->get('puesto'))
        ->Area($request->get('area'))
        ->Usuario($request->get('usuario'))
        ->paginate(20);

        return view ('equipamiento.inicio', array('equipamientos'=>$equipamientos, 'equipo'=>$request->get('equipo'),'puesto'=>$request->get('puesto'),'ip'=>$request->get('ip'),'tipo_equipamiento'=>$tipo_equipamiento,'tipo'=>$request->get('tipo'), 'usuario'=>$request->get('usuario'), 'area'=>$request->get('area')));
    }

    public function select_tipo_equipamiento(){
        return DB::table('tipo_equipamiento')->orderBy('equipamiento','asc')->get();
    }

    public function create()
    {
        $tipo_equipamiento = DB::table('tipo_equipamiento')->get();

        return view ('equipamiento.create_equipamiento', array('tipo_equipamiento' => $tipo_equipamiento));
    }

    public function store(Request $request)
    {
        
        $aux_id=DB::table('equipamientos')->where('equipamientos.id_e',$request['id_e'])->first();

        if($aux_id){
            Session::flash('message','ID ingresado ya se encuentra asignado');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back()->withInput();
        }

        if($request['ip'] != null){
        $aux_ip=DB::table('equipamientos')->where('equipamientos.ip', $request['ip'])->first();

        if($aux_ip){
            Session::flash('message','Direccion IP ingresada ya se encuentra asignada');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back()->withInput();
        }
        }

        $equipamiento= new Equipamiento;
        $equipamiento->id_e = $request['id_e'];
        $equipamiento->marca = $request['marca'];
        $equipamiento->modelo = $request['modelo'];
        $equipamiento->num_serie = $request['num_serie'];
        $equipamiento->ip = $request['ip'];
        $equipamiento->obs = $request['obs'];
        $equipamiento->pulgadas = $request['pulgadas'];
        $equipamiento->procesador = $request['procesador'];
        $equipamiento->disco = $request['disco'];
        $equipamiento->memoria = $request['memoria'];
        $equipamiento->tipo = $request['tipo_equipamiento'];
        $equipamiento->toner = $request['toner'];
        $equipamiento->unidad_imagen = $request['unidad_imagen'];
        $equipamiento->oc = $request['oc'];
        $equipamiento->save();

        Session::flash('message','Equipamiento agregado con éxito');
        Session::flash('alert-class', 'alert-success');

        return redirect('equipamiento');
    }
    public function modal_editar_equipamiento (Request $request,$id){
        return DB::table('equipamientos')
        ->leftjoin('tipo_equipamiento','equipamientos.tipo','tipo_equipamiento.id')
        ->where('equipamientos.id_e',$id)
        ->first();
    }

    public function update(Request $request)
    {   
       $equipamiento = DB::table('equipamientos')
        ->where('equipamientos.id_e',$request['id_e'])
        ->update([
            'id_e' => $request['id_e'],
            'marca' => $request['marca'],
            'modelo' => $request['modelo'],
            'num_serie' => $request['num_serie'],
            'ip' => $request['ip'],
            'obs' => $request['obs'],
            'pulgadas' => $request['pulgadas'],
            'procesador' => $request['procesador'],
            'disco' => $request['disco'],
            'memoria' => $request['memoria'],
            'tipo' => $request['tipo_equipamiento'],
            'toner' => $request['toner'],
            'unidad_imagen' => $request['unidad_imagen'],
            'oc' => $request['oc']
        ]);      
        
        Session::flash('message','Equipamiento modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        
        return redirect('equipamiento');
    }
    
    public function show($id)
    {
        //
    }
    public function listado_ip(Request $request)
    {
        $listado= array();
        for($i=1; $i<255;$i++){          
            $equipamiento = Equipamiento::ListadoIp($i)->first();

            if($equipamiento == null){
                $listado[$i][0] = "10.41.20.".$i;
                $listado[$i][1]='Libre';
                $listado[$i][2]='';
                $listado[$i][3]='';
                $listado[$i][4]='';
            }
            else{
               $listado[$i][0] = "10.41.20.".$i;
               $listado[$i][1]=$equipamiento->id_equipamiento;
               $listado[$i][2]=$equipamiento->tipo;
               $listado[$i][3]=$equipamiento->nombre.' '.$equipamiento->apellido;
               $listado[$i][4]=$equipamiento->obs;
           }
       }

       return view ('equipamiento.listado_ip', array('listado'=> $listado));
   }

   //****************RELACIONES**********************

   public function select_puesto()
   {
    
    return DB::table('puestos')
    ->leftjoin('personas','puestos.persona','personas.id_p')
    ->orderBy('puestos.desc_puesto')->get();

}

public function store_relacion(Request $request)
{

    $relacion= new Relacion;
    $relacion->equipamiento = $request['equipamiento'];
    $relacion->puesto = $request['puesto'];
    $relacion->estado = 1;
    $relacion->save();

    Session::flash('message','Relacion agregada con éxito');
    Session::flash('alert-class', 'alert-success');

    return redirect('equipamiento');

}
public function destroy_relacion(Request $request)
{
    $relacion = DB::table('relaciones')->where('relaciones.id_r',$request['relacion'])
    ->update([
        'estado'=> 0]);

    Session::flash('message','Relacion eliminada con éxito');
    Session::flash('alert-class', 'alert-success');
    
    return redirect('equipamiento');
}

public function select_soft()
   {
        
    return DB::table('softinst')->orderBy('id_s','asc')->get();  

}

}