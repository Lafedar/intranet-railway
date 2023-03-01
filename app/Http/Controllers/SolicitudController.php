<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Solicitud;
use App\Historico_solicitudes;
use App\Falla;
use App\Tipo_solicitud;
use App\User;
Use Session;
use DB;


class SolicitudController extends Controller
{
    public function index(Request $request)
    {
        $solicitudes = Solicitud::ID($request->get('id_solicitud'))
        ->Equipo($request->get('id_equipo'))
        ->Titulo($request->get('id_titulo'))
        ->Relaciones_index($request->get('relaciones'))
        ->orderBy('id_solicitud', 'desc')
        ->paginate(20);

        return view('solicitudes.index', [
        'solicitudes' => $solicitudes
        ]);
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
            ->leftjoin('localizaciones', 'localizaciones.id' ,'equipos_mant.id_localizacion')
            ->leftjoin('area', 'area.id_a', 'localizaciones.id_area')
            ->where('historico_solicitudes.actual', '=', 1)
            ->select('solicitudes.id as id', 'solicitudes.titulo as titulo', 'tipo_solicitudes.nombre as tipo_solicitud', 'fallas.nombre as falla', 
            'usuario_encargado.name as nombre_encargado', 'usuario_solicitante.name as nombre_solicitante', 'solicitudes.id_equipo as id_equipo', 
            'estados.nombre as estado', 'area.nombre_a as area', 'localizaciones.nombre as localizacion')
            ->find($id);
        $historico_solicitudes = DB::table('historico_solicitudes')
            ->leftjoin('estados', 'estados.id', 'historico_solicitudes.id_estado') 
            ->where('id_solicitud', $id)
            ->select('historico_solicitudes.fecha as fecha', 'historico_solicitudes.descripcion as descripcion', 'estados.nombre as estado')
            ->orderBy('fecha', 'desc')
            ->get();

        return view('solicitudes.show', [
            'solicitud' => $solicitud,
            'historico_solicitudes' => $historico_solicitudes      
        ]);       
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
            ->select('solicitudes.id as id', 'solicitudes.titulo as titulo', 'tipo_solicitudes.nombre as tipo_solicitud', 'fallas.nombre as falla', 
            'usuario_encargado.name as nombre_encargado', 'usuario_solicitante.name as nombre_solicitante', 'solicitudes.id_equipo as id_equipo', 
            'estados.nombre as estado', 'area.nombre_a as area', 'localizaciones.nombre as localizacion')
            ->find($id);
        $historico_solicitudes = DB::table('historico_solicitudes')
            ->leftjoin('estados', 'estados.id', 'historico_solicitudes.id_estado') 
            ->where('id_solicitud', $id)
            ->select('historico_solicitudes.fecha as fecha', 'historico_solicitudes.descripcion as descripcion', 'estados.nombre as estado')
            ->orderBy('fecha', 'desc')
            ->get();

        return view('solicitudes.update', [
            'solicitud' => $solicitud,
            'historico_solicitudes' => $historico_solicitudes      
        ]);       
    }

    public function update_solicitud(Request $request)
    {
        $historico_antiguo = DB::table('historico_solicitudes')
        ->select('historico_solicitudes.id_solicitud as id_solicitud', 'historico_solicitudes.id_estado as id_estado ', 
        'historico_solicitudes.fecha as fecha ')
        ->where('historico_solicitudes.id_solicitud', $request['id'])
        ->where('historico_solicitudes.actual', 1);
        dd($historico_antiguo);
        /*$solicitud = DB::table('solicitudes')
        ->where('solicitudes.id',$request['id'])
        ->update(['titulo' => $request['titulo']]); */

        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }
    //trae tabla de tipos de solicitudes 
    public function select_tipo_solicitud()
    {
        return DB::table('tipo_solicitudes')->get();
    }   

    public function select_area()
    {
        return [DB::table('area')->get(), DB::table('localizaciones')->get()];
    }  

    public function select_localizacion()
    {
        return DB::table('localizaciones')->get();
    }  

    public function select_equipo()
    {
        return DB::table('equipos_mant')->get();
    }  

    public function select_falla()
    {
        return DB::table('fallas')->get();
    }  

    public function select_estado()
    {
        return DB::table('estados')->get();
    } 

    public function store_solicitud(Request $request)
    {        
        $aux = Solicitud::get()->max('id');
        if($aux==null)
        {
            $aux = 0;
        }

        $solicitud = new Solicitud;
        $solicitud->titulo = $request['titulo'];
        $solicitud->obs = $request['obs'];
        $solicitud->fecha = $request['fecha'];
        $solicitud->frecuencia = $request['frecuencia'];
        $solicitud->categoria = '2';

        if($request->file('pbix'))
        {
            $file = $request->file('pbix');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();         
            Storage::disk('public')->put('powerbi/'.$name, \File::get($file));
            $solicitud->pbix = 'powerbi\\'.$name;
        }

        $solicitud->save();

        Session::flash('message','Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('solicitudes');
    }

    public function destroy_solicitud($id)
    {
        $solicitud = Solicitud::find($id);
        if($solicitud->pbix != null){
            unlink(storage_path('app\\public\\'.$solicitud->pbix));
        }

        $solicitud -> delete(); 
        Session::flash('message','Archivo eliminado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }

}