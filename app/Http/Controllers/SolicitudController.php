<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Solicitud;
use App\Historico_solicitudes;
use App\Falla;
use App\Tipo_solicitud;
use App\User;
use App\Estado;
use Carbon\Carbon;
Use Session;
use DB;
use Illuminate\Support\Facades\Auth;

class SolicitudController extends Controller
{
    public function index(Request $request)
    {
        $tiposSolicitudes = DB::table('tipo_solicitudes')->orderBy('nombre','asc')->get();
        $estados = DB::table('estados')->orderBy('nombre','asc')->get();
        $usuarios = DB::table('users')->orderBy('name','asc')->get();
        $model_as_roles = DB::table('model_has_roles')->get();

        $solicitudes = Solicitud::ID($request->get('id_solicitud'))
        ->Equipo($request->get('id_equipo'))
        ->Titulo($request->get('titulo'))
        ->Falla($request->get('id_falla'))
        ->Relaciones_index($request->get('id_tipo_solicitud'), $request->get('id_estado'), $request->get('id_encargado'), $request->get('id_solicitante'))
        ->orderBy('id_solicitud', 'desc')
        ->paginate(20);

        return view('solicitudes.index', array('solicitudes' => $solicitudes, 'tiposSolicitudes' => $tiposSolicitudes, 'estados' => $estados,
        'usuarios' => $usuarios, 'model_as_roles' => $model_as_roles, 'id_equipo'=>$request->get('id_equipo'), 'id_solicitud'=>$request->get('id_solicitud'), 
        'titulo'=>$request->get('titulo'), 'id_tipo_solicitud'=>$request->get('id_tipo_solicitud'), 'id_estado'=>$request->get('id_estado'),
        'id_encargado'=>$request->get('id_encargado'), 'id_solicitante'=>$request->get('id_solicitante')));
    }

    public function show_store_solicitud()
    {
        return view('solicitudes.create');       
    }

    public function store_solicitud(Request $request)
    {        
        $aux = Solicitud::get()->max('id');
        if($aux == null)
        {
            $aux = 0;
        }

        $solicitud = new Solicitud;
        $solicitud->titulo = $request['titulo'];
        $solicitud->id_equipo = $request['equipo'];
        $solicitud->id_falla = $request['falla'];
        $solicitud->id_solicitante = $request['solicitante'];
        $solicitud->id_tipo_solicitud = $request['tipo_solicitud'];
        if($request['tipo_solicitud'] == 2)
        {
            $solicitud->id_localizacion_edilicio = $request['localizacion'];
        }

        $solicitud->save();

        $historico_solicitud = new Historico_solicitudes;
        $historico_solicitud->id_solicitud = $aux+1;
        $historico_solicitud->id_estado = 1;
        $historico_solicitud->actual = 1;
        $historico_solicitud->descripcion = $request['descripcion'];
        $historico_solicitud->id_usuario = Auth::id();
        $historico_solicitud->fecha = Carbon::now()->format('Y-m-d H:i:s');    

        $historico_solicitud->save();

        Session::flash('message','Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('solicitudes');
    }

    public function show_solicitud($id)
    {
        //migrar a modelo
        $solicitud = Solicitud::leftjoin('fallas', 'fallas.id', 'solicitudes.id_falla')
            ->leftjoin('historico_solicitudes', 'historico_solicitudes.id_solicitud', 'solicitudes.id')
            ->leftjoin('estados', 'historico_solicitudes.id_estado', 'estados.id')
            ->leftjoin('users as usuario_encargado', 'usuario_encargado.id', 'solicitudes.id_encargado')
            ->leftjoin('users as usuario_solicitante', 'usuario_solicitante.id', 'solicitudes.id_solicitante')
            ->leftjoin('tipo_solicitudes', 'tipo_solicitudes.id', 'solicitudes.id_tipo_solicitud')
            ->leftjoin('equipos_mant', 'equipos_mant.id', 'solicitudes.id_equipo')
            ->leftjoin('localizaciones as loc_equipo', 'loc_equipo.id' ,'equipos_mant.id_localizacion')
            ->leftjoin('localizaciones as loc_edilicio', 'loc_edilicio.id' ,'solicitudes.id_localizacion_edilicio')
            ->leftjoin('area as area_equipo', 'area_equipo.id_a', 'loc_equipo.id_area')
            ->leftjoin('area as area_edilicio', 'area_edilicio.id_a', 'loc_edilicio.id_area')
            ->where('historico_solicitudes.actual', '=', 1)
            ->select('solicitudes.id as id', 'solicitudes.titulo as titulo', 'tipo_solicitudes.nombre as tipo_solicitud', 'fallas.nombre as falla', 
            'usuario_encargado.name as nombre_encargado', 'usuario_solicitante.name as nombre_solicitante', 'solicitudes.id_equipo as id_equipo', 
            'estados.nombre as estado', 'area_equipo.nombre_a as area_equipo', 'area_edilicio.nombre_a as area_edilicio', 'loc_equipo.nombre as loc_equipo', 
            'loc_edilicio.nombre as loc_edilicio')
            ->find($id);
        $historico_solicitudes = DB::table('historico_solicitudes')
            ->leftjoin('estados', 'estados.id', 'historico_solicitudes.id_estado') 
            ->where('id_solicitud', $id)
            ->select('historico_solicitudes.fecha as fecha', 'historico_solicitudes.descripcion as descripcion', 'estados.nombre as estado', 
            'historico_solicitudes.repuestos as rep', 'historico_solicitudes.descripcion_repuestos as desc_rep')
            ->orderBy('fecha', 'desc')
            ->get();

        return view('solicitudes.show', [
            'solicitud' => $solicitud,
            'historico_solicitudes' => $historico_solicitudes      
        ]);       
    }

    public function show_mostrar_equipos_mant()
    {
        $equipos = DB::table('equipos_mant')
        ->get();

        return view('solicitudes.show_equipo', ['equipos' => $equipos,]);       
    }
    
    public function mostrar_equipos_mant()
    {
        //return redirect('solicitudes');
    }

    public function show_update_solicitud($id)
    {
        //migrar a modelo
        $solicitud = Solicitud::leftjoin('fallas', 'fallas.id', 'solicitudes.id_falla')
            ->leftjoin('historico_solicitudes', 'historico_solicitudes.id_solicitud', 'solicitudes.id')
            ->leftjoin('estados', 'historico_solicitudes.id_estado', 'estados.id')
            ->leftjoin('users as usuario_encargado', 'usuario_encargado.id', 'solicitudes.id_encargado')
            ->leftjoin('users as usuario_solicitante', 'usuario_solicitante.id', 'solicitudes.id_solicitante')
            ->leftjoin('tipo_solicitudes', 'tipo_solicitudes.id', 'solicitudes.id_tipo_solicitud')
            ->leftjoin('equipos_mant', 'equipos_mant.id', 'solicitudes.id_equipo')
            ->leftjoin('localizaciones', 'localizaciones.id' ,'equipos_mant.id_localizacion')
            ->leftjoin('area', 'area.id_a', 'localizaciones.id_area')
            ->where('historico_solicitudes.actual', '=', 1)
            ->select('solicitudes.id as id')
            ->find($id);

        return view('solicitudes.update', [
            'solicitud' => $solicitud,
   
        ]);       
    }

    public function update_solicitud(Request $request)
    {
        $ultimo_historico = DB::table('historico_solicitudes')
        ->select('historico_solicitudes.id_solicitud as id_solicitud', 'historico_solicitudes.id_estado as id_estado', 
        'historico_solicitudes.fecha as fecha')
        ->where('historico_solicitudes.id_solicitud', $request['id_solicitud'])
        ->where('historico_solicitudes.actual', 1)
        ->first();

        $actualizo_ult = DB::table('historico_solicitudes')
        ->where('historico_solicitudes.id_solicitud',$ultimo_historico->id_solicitud)
        ->where('historico_solicitudes.id_estado',$ultimo_historico->id_estado) //id de estado
        ->where('historico_solicitudes.fecha',$ultimo_historico->fecha)
        ->update(['actual' => 0]);

        $nuevo_historico = new Historico_solicitudes;
        $nuevo_historico->id_solicitud = $request['id_solicitud'];
        $nuevo_historico->id_estado = $request['estado']; //id de estado
        $nuevo_historico->descripcion = $request['descripcion'];
        $nuevo_historico->repuestos = $request['rep'];
        if($request['rep']){
            $nuevo_historico->repuestos = $request['rep'];
            $nuevo_historico->descripcion_repuestos = $request['descripcionRep'];
        } else{
            $nuevo_historico->repuestos = 0;
            $nuevo_historico->descripcion_repuestos = "";
        }
        $nuevo_historico->actual = 1;
        $nuevo_historico->id_usuario = Auth::id();
        $nuevo_historico->fecha = Carbon::now()->format('Y-m-d H:i:s');    
        $nuevo_historico->save();

        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }

    public function show_assing_solicitud($id)
    {
        //migrar a modelo
        $solicitud = Solicitud::
            find($id);

        return view('solicitudes.assing', [
            'solicitud' => $solicitud
        ]);       
    }

    public function assing_solicitud(Request $request)
    {
        $solicitud = DB::table('solicitudes')
        ->where('solicitudes.id', $request['id_solicitud'])
        ->update(['id_encargado' => $request['user']]);

        Session::flash('message','Solicitud asignada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }

    //trae tabla de tipos de solicitudes 
    public function select_create()
    {
        return [DB::table('area')->get(), 
        DB::table('localizaciones')->get(), 
        DB::table('tipo_solicitudes')->get(), 
        DB::table('equipos_mant')->get(), 
        DB::table('fallas')->get(), 
        DB::table('tipos_equipos')->get(),
        DB::table('fallasxtipo')->get()];
    }   

    public function select_users()
    {
        return [DB::table('users')->get(),
        DB::table('model_has_roles')->get()];
    }  

    public function select_estado()
    {
        return DB::table('estados')->get();
    } 

    public function select_equipos()
    {
        return DB::table('equipos_mant')->get();
    } 

    public function destroy_solicitud($id)
    {
        $solicitud = Solicitud::find($id);

        $historico_solicitudes = DB::table('historico_solicitudes')
        ->where('historico_solicitudes.id_solicitud', $id)
        ->get();

        foreach ($historico_solicitudes as $historico_solicitud) {
            DB::table('historico_solicitudes')->where('id_solicitud', $historico_solicitud->id_solicitud)->delete();
        }

        $solicitud -> delete(); 
        Session::flash('message','Solicitud eliminada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }

}