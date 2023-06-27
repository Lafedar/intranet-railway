<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Historico_solicitudes;
use Illuminate\Http\Request;
use App\Tipo_solicitud;
use App\Solicitud;
use Carbon\Carbon;
use App\Estado;
use App\Falla;
use App\User;
Use Session;
use DB;


class SolicitudController extends Controller{
    public function index(Request $request)
    {
        $userAutenticado = Auth::id();
        $areaUserAutenticado = Solicitud::obtenerAreaUserAutenticado($userAutenticado);
    
        $solicitudesQuery = Solicitud::ID($request->get('id_solicitud'))
            ->Equipo($request->get('id_equipo'))
            ->Titulo($request->get('titulo'))
            ->Falla($request->get('id_falla'))
            ->Relaciones_index($request->get('id_tipo_solicitud'), $request->get('id_estado'), $request->get('id_encargado'), $request->get('id_solicitante'), $request->get('fecha'))
            ->orderBy('id_solicitud', 'desc');
        if (Gate::allows('ver-todas-las-solicitudes')) {
            // Jefe
            $solicitudes = $solicitudesQuery->where('id_tipo_solicitud', '!=', 3)->paginate(20);
        } elseif (Gate::allows('ver-solicitudes-asignadas')) {
            // Empleados - Solicitudes asignadas
            $solicitudes = $solicitudesQuery->where('id_encargado', $userAutenticado)->paginate(20);
        } elseif (Gate::allows('ver-solicitudes-sin-asignar')) {
            // Empleados que pueden asignar
            $solicitudes = $solicitudesQuery->where(function ($query) use ($userAutenticado) {
                $query->where('id_encargado', $userAutenticado)
                    ->where('id_tipo_solicitud', '!=', 3)
                    ->orWhereNull('id_encargado');
            })->paginate(20);
        } elseif (Gate::allows('ver-todas-las-solicitudes-y-proyectos')){
            $solicitudes = $solicitudesQuery->paginate(20);
        } elseif (Gate::allows('ver-proyectos')){
            //revisar -----------------
            $solicitudes = $solicitudesQuery->where('id_encargado', $userAutenticado)->orWhere('id_tipo_solicitud', 3)->where('historico_solicitudes.actual', '=', 1)->paginate(20);
        }else{
            // usuarios
            $solicitudes = $solicitudesQuery->where(function ($query) use ($areaUserAutenticado, $userAutenticado) {
                $query->where('id_area', $areaUserAutenticado->area)
                    ->orWhere('id_solicitante', $userAutenticado);
            })->paginate(20);
        }
    
        $tiposSolicitudes = DB::table('tipo_solicitudes')->orderBy('nombre','asc')->get();
        $estados = DB::table('estados')->orderBy('nombre','asc')->get();
        $usuarios = DB::table('users')->orderBy('name','asc')->get();
        $model_as_roles = DB::table('model_has_roles')->get();
    

        return view('solicitudes.index', [
            'solicitudes' => $solicitudes,
            'tiposSolicitudes' => $tiposSolicitudes,
            'estados' => $estados,
            'usuarios' => $usuarios,
            'areaUserAutenticado' => $areaUserAutenticado,
            'userAutenticado' => $userAutenticado,
            'model_as_roles' => $model_as_roles,
            'id_equipo' => $request->get('id_equipo'),
            'id_solicitud' => $request->get('id_solicitud'),
            'titulo' => $request->get('titulo'),
            'id_tipo_solicitud' => $request->get('id_tipo_solicitud'),
            'id_estado' => $request->get('id_estado'),
            'id_encargado' => $request->get('id_encargado'),
            'fecha' => $request->get('fecha'),
            'id_solicitante' => $request->get('id_solicitante'),
        ]);
    }

    public static function getHistoricos($solicitud){
        return DB::table('historico_solicitudes')
        ->leftJoin('users', 'users.id', 'historico_solicitudes.id_usuario')
        ->leftJoin('estados', 'estados.id', 'historico_solicitudes.id_estado')
        ->select('estados.nombre as estado', 
        'historico_solicitudes.fecha as fecha', 
        'users.name as nombre', 
        'historico_solicitudes.descripcion as descripcion', 
        'historico_solicitudes.repuestos as repuestos')
        ->where('id_solicitud', $solicitud)
        ->get();
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
        $solicitud->id = $aux+1;
        $solicitud->titulo = $request['titulo'];
        if($request['tipo_solicitud'] == 1){
            $solicitud->id_equipo = $request['equipo'];
        }
        if($request['tipo_solicitud'] != 3){
            $solicitud->id_falla = $request['falla'];
        }
        $solicitud->id_solicitante = $request['solicitante'];
        $solicitud->id_tipo_solicitud = $request['tipo_solicitud'];
        $solicitud->fecha_alta = $fechaActual;
        $solicitud->id_estado = 1;
        if($request['tipo_solicitud'] == 2){
            $solicitud->id_localizacion_edilicio = $request['localizacion'];
        }
        if($request['tipo_solicitud'] == 3){
            $solicitud->id_area_proyecto = $request['area'];
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
        $equipos = Solicitud::getEquiposMantenimientoConLocalizacionYArea();

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

        $mailNombreSolicitante = Solicitud::obtenerMailNombreSolicitante($request['id_solicitud']);
        $nombreEstadoSolicitud = Solicitud::obtenerNombreEstadoSolicitud($request['id_solicitud']);

        //da error cuando el correo no existe
        if($request['estado'] == 5){
            try {
                Mail::to($mailNombreSolicitante->email)->send(new \App\Mail\aprobarSolicitud($mailNombreSolicitante->nombre, $request['id_solicitud'], $nombreEstadoSolicitud));
            } catch (\Exception $e) {}
        }else{
            try {
                Mail::to($mailNombreSolicitante->email)->send(new \App\Mail\cambioDeEstadoSolicitud($mailNombreSolicitante->nombre, $request['id_solicitud'], $nombreEstadoSolicitud));
            } catch (\Exception $e) {}
        }
        
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

        $fechaActual = Carbon::now()->format('Y-m-d H:i:s');
        $ultimo_historico = Solicitud::ultimoHistoricoById($request['id_solicitud']);
        Solicitud::updateSoliciutud($request['id_solicitud'], 2, $fechaActual);
        $actualizo_ult = Solicitud::updateHistorico($ultimo_historico->id_solicitud, $ultimo_historico->id_estado, $ultimo_historico->fecha);

        $nuevo_historico = new Historico_solicitudes;
        $nuevo_historico->id_solicitud = $request['id_solicitud'];
        $nuevo_historico->id_estado = 2; //id de estado
        $nuevo_historico->descripcion = null;
        $nuevo_historico->repuestos = null;
        $nuevo_historico->descripcion_repuestos = null;
        $nuevo_historico->actual = 1;
        $nuevo_historico->id_usuario = Auth::id();
        $nuevo_historico->fecha = $fechaActual;    
        $nuevo_historico->save();

        $mailNombreSolicitante = Solicitud::obtenerMailNombreSolicitante($request['id_solicitud']);
        $nombreEstadoSolicitud = Solicitud::obtenerNombreEstadoSolicitud($request['id_solicitud']);

        //da error cuando el correo no existe
        try {
            Mail::to($mailNombreSolicitante->email)->send(new \App\Mail\cambioDeEstadoSolicitud($mailNombreSolicitante->nombre, $request['id_solicitud'], $nombreEstadoSolicitud));
        } catch (\Exception $e) {}

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
        return [Solicitud::getUsers(),
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
    public function aprobar_solicitud($id){
        $solicitud = Solicitud::find($id);

        $fechaActual = Carbon::now()->format('Y-m-d H:i:s');
        $ultimo_historico = Solicitud::ultimoHistoricoById($solicitud->id);
        Solicitud::updateSoliciutud($solicitud->id, 6, $fechaActual);
        $actualizo_ult = Solicitud::updateHistorico($ultimo_historico->id_solicitud, $ultimo_historico->id_estado, $ultimo_historico->fecha);

        $nuevo_historico = new Historico_solicitudes;
        $nuevo_historico->id_solicitud = $solicitud->id;
        $nuevo_historico->id_estado = 6; //id de estado
        $nuevo_historico->descripcion = null;
        $nuevo_historico->repuestos = null;
        $nuevo_historico->descripcion_repuestos = null;
        $nuevo_historico->actual = 1;
        $nuevo_historico->id_usuario = Auth::id();
        $nuevo_historico->fecha = $fechaActual;    
        $nuevo_historico->save();

        Session::flash('message','Solicitud aprobada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }
}