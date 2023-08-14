<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Persona;
use App\Permiso;
use App\User;
use App\Empleado;
use Auth;
use DB;
Use Session;
use Mail;
use Illuminate\Routing\Controller;
use Carbon\Carbon;

class PermisosController extends Controller
{
    public function index(Request $request)
    {
        $tipo_permisos = DB::table('tipo_permiso')->get();

        if( auth()->user()->id == 44)
            $jefe = DB::table('personas')->where('personas.usuario', 19 )->first();
        
        else{
            $jefe = DB::table('personas')->where('personas.usuario', auth()->user()->id )->first();
            
        }
        
        if($jefe->rango == 1){
            $permisos = Permiso::Relaciones($jefe->id_p, $request->get('motivo'))
            ->Empleado($request->get('empleado'))
            ->paginate(10);
        }
        else{
            $permisos = Permiso::Relaciones($jefe->id_p, $request->get('motivo'))
            ->Empleado($request->get('empleado'))
            ->Jefe()
            ->paginate(10);
        }
        
        return view ('permisos.index', array('permisos'=>$permisos,'empleado'=>$request->get('empleado'),'tipo_permisos'=>$tipo_permisos));
    }


    public function show($id)
    {
        $permisos = DB::table('permisos')
        ->join('tipo_permiso', 'permisos.motivo','tipo_permiso.id_tip')
        ->where('permisos.id',$id)
        ->first();

        $autorizado = DB::table('personas')
        ->join('area','personas.area','area.id_a')
        ->where('personas.id_p',$permisos->autorizado)->first();

        $autorizante = DB::table('personas')->where('personas.id_p',$permisos->autorizante)->first();

        return view ('permisos.show', ['permiso' => $permisos, 'autorizado' => $autorizado,'autorizante' => $autorizante]);
    }


    public function store(Request $request)
    {

        $jefe = DB::table('personas')->where('personas.usuario', auth()->user()->id )->first();

        $permiso_nuevo = new Permiso;
        $permiso_nuevo->autorizado = $request['autorizado'];
        $permiso_nuevo->autorizante = $jefe->id_p;
        $permiso_nuevo->motivo = $request['motivo'];
        $permiso_nuevo->fecha_desde = $request['fecha_desde'];
        $permiso_nuevo->fecha_hasta = $request['fecha_hasta']; 
        $permiso_nuevo->descripcion = $request['descripcion'];
        $permiso_nuevo->hora_desde = $request['hora_desde'];
        $permiso_nuevo->hora_hasta = $request['hora_hasta'];
        $permiso_nuevo->save();

        $permiso = Permiso::Buscapermiso($permiso_nuevo->id)->first();

        Mail::send('permisos.mail',array('permiso' => $permiso, 'jefe' => $jefe), function($message) use ($jefe,$permiso){
            $message->from('notificaciones@lafedar.com', 'Notificaciones');
            $message->to('gustavo.lecman@lafedar.com')->subject('Solicitud de permiso'.' '.$permiso->area);
            $message->cc($jefe->correo);
            $message->cc('laura.cersofio@lafedar.com');
        });
        
        Session::flash('message','Permiso agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('permisos');
    }  

    public function destroy_permiso(Request $request, $id)
    {
        $jefe = DB::table('personas')->where('personas.usuario', auth()->user()->id )->first();

        $permiso = Permiso::Buscapermiso($id)->first();
        
        Mail::send('permisos.mail_cancelado',array('permiso' => $permiso, 'jefe' => $jefe), function($message) use ($permiso){
            $message->from('notificaciones@lafedar.com', 'Notificaciones');
            $message->to('gustavo.lecman@lafedar.com')->subject('Permiso cancelado'.' '.$permiso->area);
            $message->cc('laura.cersofio@lafedar.com');
        });
        
        $permiso_borrar = Permiso::find($id);
        $permiso_borrar->delete();

        Session::flash('message','Permiso cancelado con éxito');
        Session::flash('alert-class', 'alert-success');     
        return redirect('permisos');
    }

    public function select_autorizado(){

        $persona = DB::table('personas')->where('personas.usuario', auth()->user()->id )->first();
        //dd($persona);
        if($persona->jefe == 1){
            return Empleado::Busca_personas($persona->id_p);
        }
        else{
            return Empleado::Busca_personas($persona->id_p)
            ->Rango()
            ->get();
        }
    }

    public function select_tipo_permiso(){
        return DB::table('tipo_permiso')->where('tipo_permiso.desc', '!=', 'Licencia de cumpleaños')->get();
    }
}