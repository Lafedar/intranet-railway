<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Equipamiento;
use App\Puesto;
use App\Software;
use App\Relacion;
use App\Relasoft;
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


class SoftwareController extends Controller
{
    

   
   public function Software (Request $request)
   {
      
        $nombre=$request->get('software');  
        $version=$request->get('version');
             


        $software = Software::orderBy('id_s','asc')
        ->software ($nombre)
        ->version   ($version)
        
        ->paginate(200);

    return view ('Software.soft',compact('software'),compact('version'));
}
 

    public function soft_store(Request $request)
{
    $software= new Software;
    $software->id_s  =$request['id_s'];
    $software->Software = $request['Software'];
    $software->Licencia = $request['Licencia'];
    $software->t_Licencia = $request['t_Licencia'];
    $software->Version = $request['Version'];
    $software->fecha_inst = $request['fecha_inst'];
    $software->Obs = $request['Obs'];
    //dd($software);
    $software->save();
         
    Session::flash('message','Sofware agregado con éxito');
    Session::flash('alert-class', 'alert-success');

    return redirect('Software');
}
 
public function updates(Request $request)
    {   
       $software = DB::table('softinst')
        ->where('softinst.id_s',$request['id_s'])
        ->update([
            'id_s' => $request['id_s'],
            'Software' => $request['Software'],
            'Version' => $request['Version'],
            'Licencia' => $request['Licencia'],
            't_Licencia' => $request['t_Licencia'],
            'fecha_inst' => $request['fecha_inst'],
            'Obs' => $request['Obs']

]);
        Session::flash('message','Software modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        
        return redirect('Software');
            
    }  


public function store_srelacions(Request $request)
{

    $relacion= new Relasoft;
    $relacion->softw = $request['softw'];
    $relacion->equipo = $request['equipo'];
    $relacion->licen=$request['licen'];
    $relacion->estado = 1;
    $relacion->save();

    Session::flash('message','Relacion agregada con éxito');
    Session::flash('alert-class', 'alert-success');

    return redirect('equipamiento');

}
    //Elimina la relación entre equipamiento y puesto
public function destroy_srelacions(Request $request, $id)
    {
        $relacion = Relasoft::find($id);
        $relacion->estado = 0;
        $relacion->save();

    Session::flash('message','Relacion eliminada con éxito');
    Session::flash('alert-class', 'alert-success');
    
    return redirect('Instalado');   
    }



/*
public function Instalado (Request $request){

        $relacion = Relasoft::all();
         
     return view ('Software.versoft',compact('relacion'));
   }
*/
public function Instalado(Request $request){

         
        $tabla_soft = Relasoft::Relacione()
        ->Software($request->get('software'))
        ->Version($request->get('version'))
        ->Equipo($request->get('equipo'))
        ->Licen($request->get('licen'))
        ->id($request->get('id'))
        ->estado($request->get('estado'))
        ->orderBy('equipo','asc')
        ->paginate(20);
//dd($tabla_soft);
        return view ('Software.versoft', array('tabla_soft'=>$tabla_soft, 'software'=>$request->get('software'),'version'=>$request->get('version'),'equipo'=>$request->get('equipo'),'licen'=>$request->get('licen'),'estado'=>$request->get('estado'), 'id'=>$request->get('id')));
    }

    public function updat(Request $request)
    {   
       $softwar = DB::table('relasoft')
        ->where('relasoft.id_rs',$request['id'])
        ->update([
            
            'softw' => $request['softw'],
            'equipo' => $request['equipo'],
            'licen' => $request['licen'],
            'observacion' => $request['observacion']
            
            

]);

        
        Session::flash('message','Software modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        
        return redirect('Instalado');
            
    }      
       

}