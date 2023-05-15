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

class SolicitudController extends Controller{
    public function index(Request $request){
        $tiposSolicitudes = DB::table('tipo_solicitudes')->orderBy('nombre','asc')->get();
        $estados = DB::table('estados')->orderBy('nombre','asc')->get();
        $usuarios = DB::table('users')->orderBy('name','asc')->get();
        $model_as_roles = DB::table('model_has_roles')->get();
        $areaUserAutenticado = Solicitud::obtenerAreaUserAutenticado(Auth::id());
        $userAutenticado = Auth::id();

        $solicitudes = Solicitud::ID($request->get('id_solicitud'))
        ->Equipo($request->get('id_equipo'))
        ->Titulo($request->get('titulo'))
        ->Falla($request->get('id_falla'))  
        ->Relaciones_index($request->get('id_tipo_solicitud'), $request->get('id_estado'), $request->get('id_encargado'), $request->get('id_solicitante'), $request->get('fecha'))
        ->orderBy('id_solicitud', 'asc')
        ->paginate(20);

        return view('solicitudes.index', array('solicitudes' => $solicitudes, 'tiposSolicitudes' => $tiposSolicitudes, 'estados' => $estados,
        'usuarios' => $usuarios, 'areaUserAutenticado' => $areaUserAutenticado, 'userAutenticado' => $userAutenticado, 'model_as_roles' => $model_as_roles, 
        'id_equipo'=>$request->get('id_equipo'), 'id_solicitud'=>$request->get('id_solicitud'), 'titulo'=>$request->get('titulo'), 
        'id_tipo_solicitud'=>$request->get('id_tipo_solicitud'), 'id_estado'=>$request->get('id_estado'),'id_encargado'=>$request->get('id_encargado'), 
        'fecha' => $request->get('fecha'), 'id_solicitante'=>$request->get('id_solicitante')));
    }

    public function show_store_solicitud(){
        return view('solicitudes.create');       
    }

    public function store_solicitud(Request $request){        
        $aux = Solicitud::get()->max('id');
        if($aux == null){
            $aux = 0;
        }

        $fechaActual = Carbon::now()->format('Y-m-d H:i:s');

        $solicitud = new Solicitud;
        $solicitud->titulo = $request['titulo'];
        $solicitud->id_equipo = $request['equipo'];
        $solicitud->id_falla = $request['falla'];
        $solicitud->id_solicitante = $request['solicitante'];
        $solicitud->id_tipo_solicitud = $request['tipo_solicitud'];
        $solicitud->fecha_alta = $fechaActual;
        $solicitud->id_estado = 1;
        if($request['tipo_solicitud'] == 2){
            $solicitud->id_localizacion_edilicio = $request['localizacion'];
        }

        $solicitud->save();

        $historico_solicitud = new Historico_solicitudes;
        $historico_solicitud->id_solicitud = $aux+1;
        $historico_solicitud->id_estado = 1;
        $historico_solicitud->actual = 1;
        $historico_solicitud->descripcion = $request['descripcion'];
        $historico_solicitud->id_usuario = Auth::id();
        $historico_solicitud->fecha = $fechaActual;    

        $historico_solicitud->save();

        Session::flash('message','Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('solicitudes');
    }

    public function show_solicitud($id){
        $solicitud = Solicitud::withRelatedData($id)->first();
        $historico_solicitudes = Solicitud::historicoSolicitudes($id);

        return view('solicitudes.show', [
            'solicitud' => $solicitud,
            'historico_solicitudes' => $historico_solicitudes      
        ]);       
    }

    public function show_mostrar_equipos_mant(){
        $equipos = Solicitud::getEquiposMantenimiento();

        return view('solicitudes.show_equipo', ['equipos' => $equipos,]);
    }

    public function show_update_solicitud($id){
        $solicitud = Solicitud::showSolicitudUpdate($id);
        return view('solicitudes.update', ['solicitud' => $solicitud]);
    }

    public function update_solicitud(Request $request){
        $fechaActual = Carbon::now()->format('Y-m-d H:i:s');
        $ultimo_historico = Solicitud::ultimoHistoricoById($request['id_solicitud']);
        Solicitud::updateSoliciutud($request['id_solicitud'], $request['estado'], $fechaActual);
        $actualizo_ult = Solicitud::updateHistorico($ultimo_historico->id_solicitud, $ultimo_historico->id_estado, $ultimo_historico->fecha);

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
        $nuevo_historico->fecha = $fechaActual;    
        $nuevo_historico->save();

        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }

    public function show_assing_solicitud($id){
        //migrar a modelo
        $solicitud = Solicitud::
            find($id);

        return view('solicitudes.assing', [
            'solicitud' => $solicitud
        ]);       
    }

    public function assing_solicitud(Request $request){
        $solicitud = Solicitud::assingSolicitud($request['id_solicitud'], $request['user']); 

        Session::flash('message','Solicitud asignada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }

    //trae tabla de tipos de solicitudes 
    public function select_create(){
        return [Solicitud::getArea(),
        Solicitud::getLocalizaciones(),
        Solicitud::getTipoSolicitudes(),
        Solicitud::getEquiposMantenimiento(), 
        Solicitud::getFallas(),
        Solicitud::getTipoEquipos(),
        Solicitud::getFallasXTipo()];
    }   

    public function select_users(){
        return [ Solicitud::getUsers(),
        Solicitud::getModelHasRoles()];
    }  

    public function select_estado(){
        return Solicitud::getEstados();
    } 

    public function select_equipos(){
        return Solicitud::getEquiposMantenimiento();
    } 

    public function destroy_solicitud($id){
        $solicitud = Solicitud::find($id);

        $historico_solicitudes = Solicitud::getHistoricosDeUnaSolicitud($id);

        foreach ($historico_solicitudes as $historico_solicitud) {
            Solicitud::deleteHistorico($historico_solicitud->id_solicitud); 
        }

        $solicitud -> delete(); 
        Session::flash('message','Solicitud eliminada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }

}