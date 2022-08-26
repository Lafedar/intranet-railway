<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use DB;
use Auth;
use App\Novedad;
Use Session;
use Mail;
use Illuminate\Routing\Controller;
use Carbon\Carbon;


class HomeController extends Controller
{
    public function index()
    {
        return view ('home.inicio');
    }
    public function notificaciones(Request $request){
        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        return DB::table('novedades')->where('novedades.fecha_desde','<=',$date)->where('novedades.fecha_hasta','>=',$date)->get();

    }

    public function internos()
    {
        $personas = DB::table('personas')
        ->where('personas.interno','>=','0')
        ->leftjoin('area', 'area.id_a', 'personas.area')
        ->orderBy('interno','ASC')
        ->paginate(200);    
                
        return view ('internos.internos', array('personas'=>$personas));

    }
    public function novedades()
    {
        return view ('home.create_novedades');

    }
    public function store_novedades(Request $request)
    {
        if(strlen($request['descripcion'])<=200){

        $personas = DB::table('personas')->select('correo')->where('personas.rango',1)->orwhere('personas.rango',2)->orderBy('nombre_p')->get();

        $novedad= new Novedad;
        $novedad->descripcion = $request['descripcion'];
        $novedad->fecha_desde = $request['fecha_desde'];
        $novedad->fecha_hasta = $request['fecha_hasta'];
        $novedad->save();

        if ($request['enviar_correo'] == 1){
            Mail::send('home.mail',array('novedad'=>$novedad), function($message) use ($personas,$novedad){
                $message->from('notificaciones@lafedar.com', 'Notificaciones');
                foreach ($personas as $persona) {
                    $message->to($persona->correo)->subject('Nueva novedad');
                }
            });
        }      
        
        Session::flash('message','Novedad agregada con éxito');
        Session::flash('alert-class', 'alert-success');
        
        return redirect('empleado');

        }else{
            Session::flash('message','La descripción es demasiado extensa, intentelo nuevamente');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back()->withInput();
        }
    }

    public function sistemas()
    {
        return view ('home.sistemas');
    }
    public function documentos (){
        return view ('home.documentos');
    }
    public function powerbis ()
    {
        return view ('home.powerbis');
    }
}
