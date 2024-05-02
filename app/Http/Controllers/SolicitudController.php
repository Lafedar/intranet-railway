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
    public function index(Request $request){
        $userAutenticado = Auth::id();
        $areaUserAutenticado = Solicitud::obtenerAreaUserAutenticado($userAutenticado);
        $personaAutenticada1 = Solicitud::obtenerIdPersonaAutenticada($userAutenticado);
        $personaAutenticada = $personaAutenticada1->id_p;
    
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
            $solicitudes = $solicitudesQuery->where('id_encargado', $personaAutenticada)->paginate(20);
        } elseif (Gate::allows('ver-solicitudes-sin-asignar')) {
            // Empleados que pueden asignar
            $solicitudes = $solicitudesQuery->where(function ($query) use ($personaAutenticada) {
                $query->where('id_encargado', $personaAutenticada)
                    ->where('id_tipo_solicitud', '!=', 3)
                    ->orWhereNull('id_encargado');
            })->paginate(20);
        } elseif (Gate::allows('ver-todas-las-solicitudes-y-proyectos')){
            $solicitudes = $solicitudesQuery->paginate(20);
        } elseif (Gate::allows('ver-proyectos')){
            //revisar -----------------
            $solicitudes = $solicitudesQuery->where('id_encargado', $personaAutenticada)->orWhere('id_tipo_solicitud', 3)->where('historico_solicitudes.actual', '=', 1)->paginate(20);
        }else{
            // usuarios
            $solicitudes = $solicitudesQuery->where(function ($query) use ($areaUserAutenticado, $personaAutenticada) {
                $query->where('id_area', $areaUserAutenticado->area)
                    ->orWhere('id_solicitante', $personaAutenticada);
            })->paginate(20);
        }
    
        $tiposSolicitudes = DB::table('tipo_solicitudes')->orderBy('nombre','asc')->get();
        $estados = DB::table('estados')->orderBy('nombre','asc')->get();
        $usuariosConSolicitudes = DB::table('solicitudes_temp')
    ->select('id_encargado')
    ->distinct()
    ->pluck('id_encargado')
    ->toArray();

$usuarios = DB::table('users')
    ->leftJoin('personas', 'personas.usuario', 'users.id')
    ->select('users.id as idUsuario', 'users.name as name', 'personas.id_p as idPersona')
    ->whereIn('personas.id_p', $usuariosConSolicitudes)
    ->orderBy('users.name', 'asc')
    ->get();

        $model_as_roles = DB::table('model_has_roles')->get();
    

        return view('solicitudes.index', [
            'solicitudes' => $solicitudes,
            'tiposSolicitudes' => $tiposSolicitudes,
            'estados' => $estados,
            'usuarios' => $usuarios,
            'areaUserAutenticado' => $areaUserAutenticado,
            'personaAutenticada' => $personaAutenticada1,
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
            ->leftJoin('personas', 'personas.id_p', 'historico_solicitudes.id_persona')
            ->leftJoin('estados', 'estados.id', 'historico_solicitudes.id_estado')
            ->select('estados.nombre as estado', 
            'historico_solicitudes.fecha as fecha', 
            DB::raw("CASE WHEN personas.apellido IS NULL THEN personas.nombre_p ELSE CONCAT(personas.nombre_p, ' ', personas.apellido) END as nombre"), 
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

        $idPersona = Solicitud::obtenerIdPersonaAutenticada(Auth::id());
        $fechaActual = Carbon::now()->format('Y-m-d H:i:s');

        $solicitud = new Solicitud;
        $solicitud->titulo = $request['titulo'];
        if($request['tipo_solicitud'] == 1){
            $solicitud->id_equipo = $request['equipo'];
        }
        if($request['tipo_solicitud'] != 3){
            $solicitud->id_falla = $request['falla'];
        }
        $solicitud->id_solicitante = $idPersona->id_p;
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
        $historico_solicitud->id_persona = $idPersona->id_p;
        $historico_solicitud->fecha = $fechaActual;    

        $historico_solicitud->save();

        Session::flash('message','Solicitud agregado con éxito');
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
        $idPersona = Solicitud::obtenerIdPersonaAutenticada(Auth::id());

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
        $nuevo_historico->id_persona = $idPersona->id_p;
        $nuevo_historico->fecha = $fechaActual;    
        $nuevo_historico->save();

        $mailNombreSolicitante = Solicitud::obtenerMailNombreTituloSolicitante($request['id_solicitud']);
        $nombreEstadoSolicitud = Solicitud::obtenerNombreEstadoSolicitud($request['id_solicitud']);

        //da error cuando el correo no existe
        if($request['estado'] == 5){
            try {
                Mail::to($mailNombreSolicitante->email)->send(new \App\Mail\aprobarSolicitud($mailNombreSolicitante->nombre, $request['id_solicitud'],
                    $nombreEstadoSolicitud, $mailNombreSolicitante->titulo));
            } catch (\Exception $e) {}
        }else{
            try {
                Mail::to($mailNombreSolicitante->email)->send(new \App\Mail\cambioDeEstadoSolicitud($mailNombreSolicitante->nombre, $request['id_solicitud'], 
                    $nombreEstadoSolicitud, $mailNombreSolicitante->titulo));
            } catch (\Exception $e) {}
        }

        $mailsParaRepuestos = Solicitud::obtenerUsersCorreoRepuestos();
        
        if($request['rep']){
            foreach($mailsParaRepuestos as $mail){
                try {
                    Mail::to($mail->email)->send(new \App\Mail\avisoDeRepuesto($request['id_solicitud'], $nombreEstadoSolicitud,
                        $mailNombreSolicitante->titulo, $request['descripcionRep']));
                } catch (\Exception $e) {}
            }
        }
        
        Session::flash('message','Solicitud modificado con éxito');
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
        $idPersona = Solicitud::obtenerIdPersonaAutenticada(Auth::id());
        $idEncargado = Solicitud::obtenerIdPersonaAutenticada($request['user']);
        $solicitud = Solicitud::assingSolicitud($request['id_solicitud'], $idEncargado->id_p); 
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
        $nuevo_historico->id_persona = $idPersona->id_p;
        $nuevo_historico->fecha = $fechaActual;    
        $nuevo_historico->save();

        $mailNombreSolicitante = Solicitud::obtenerMailNombreTituloSolicitante($request['id_solicitud']);
        $nombreEstadoSolicitud = Solicitud::obtenerNombreEstadoSolicitud($request['id_solicitud']);

        //da error cuando el correo no existe
        try {
            Mail::to($mailNombreSolicitante->email)->send(new \App\Mail\cambioDeEstadoSolicitud($mailNombreSolicitante->nombre, $request['id_solicitud'],
             $nombreEstadoSolicitud, $mailNombreSolicitante->titulo));
        } catch (\Exception $e) {}

        Session::flash('message','Solicitud asignada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }

    public function select_tablas(){
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

    public function aprobar_solicitud($id){
        $solicitud = Solicitud::find($id);
        $idPersona = Solicitud::obtenerIdPersonaAutenticada(Auth::id());
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
        $nuevo_historico->id_persona = $idPersona->id_p;
        $nuevo_historico->fecha = $fechaActual;    
        $nuevo_historico->save();

        Session::flash('message','Solicitud aprobada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }

    public function show_reclamar_solicitud($id){
        //migrar a modelo
        $solicitud = Solicitud::
            find($id);

        return view('solicitudes.reclaim', [
            'solicitud' => $solicitud
        ]);       
    }

    public function reclaim_solicitud(Request $request){
        $solicitud = Solicitud::find($request['id_solicitud']);
        $idPersona = Solicitud::obtenerIdPersonaAutenticada(Auth::id());
        $fechaActual = Carbon::now()->format('Y-m-d H:i:s');
        $ultimo_historico = Solicitud::ultimoHistoricoById($request['id_solicitud']);
        Solicitud::updateSoliciutud($request['id_solicitud'], 7, $fechaActual);
        $actualizo_ult = Solicitud::updateHistorico($ultimo_historico->id_solicitud, $ultimo_historico->id_estado, $ultimo_historico->fecha);

        $nuevo_historico = new Historico_solicitudes;
        $nuevo_historico->id_solicitud = $request['id_solicitud'];
        $nuevo_historico->id_estado = 7; //id de estado
        $nuevo_historico->descripcion = $request['descripcion'];
        $nuevo_historico->repuestos = null;
        $nuevo_historico->descripcion_repuestos = null;
        $nuevo_historico->actual = 1;
        $nuevo_historico->id_persona = $idPersona->id_p;
        $nuevo_historico->fecha = $fechaActual;    
        $nuevo_historico->save();

        Session::flash('message','Solicitud reclamada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }
    public function getSolicitud($idSolicitud){
        return DB::table('solicitudes_temp as solicitudes')
            ->leftJoin('historico_solicitudes', 'historico_solicitudes.id_solicitud', 'solicitudes.id')
            ->leftJoin('equipos_mant_temp', 'equipos_mant_temp.id', 'solicitudes.id_equipo')
            ->leftJoin('area', 'area.id_a', 'equipos_mant_temp.id_area')
            ->leftJoin('localizaciones as localizacionesEquipos', 'localizacionesEquipos.id', 'equipos_mant_temp.id_localizacion')
            ->leftJoin('localizaciones as localizacionesEdilicios', 'localizacionesEdilicios.id', 'solicitudes.id_localizacion_edilicio')
            ->leftjoin('tipo_solicitudes', 'tipo_solicitudes.id', 'solicitudes.id_tipo_solicitud')
            ->select('solicitudes.id as idSolicitud', 
            'solicitudes.titulo as titulo', 
            'solicitudes.id_equipo as idEquipo', 
            'historico_solicitudes.descripcion as descripcion', 
            'historico_solicitudes.fecha as fecha', 
            'historico_solicitudes.id_estado as estado',
            'tipo_solicitudes.nombre as nombreTipoSolicitud',
            'solicitudes.id_area_proyecto as idAreaProyecto',
            'equipos_mant_temp.id_area as idAreaEquipo', 
            'equipos_mant_temp.id_localizacion as idLocalizacionEquipo', 
            'equipos_mant_temp.descripcion as descripcionEquipo',
            'solicitudes.id_localizacion_edilicio as idLocalizacionEdilicio',
            'localizacionesEdilicios.id_area as idAreaEdilicio',
            'solicitudes.id_falla as idFalla')
            ->where('solicitudes.id', $idSolicitud)
            ->where('historico_solicitudes.id_estado', 1)
            ->orderBy('historico_solicitudes.fecha', 'asc')
            ->limit(1)
            ->get();
    }
    public function show_edit_solicitud($id){
        $solicitud = Solicitud::showSolicitudUpdate($id);
        return view('solicitudes.edit', ['solicitud' => $solicitud]);
    }
    public function edit_solicitud(Request $request){
        if($request['tipo_solicitud1'] == 1){
            Solicitud::editSolicitud($request['idSolicitud1'], $request['estado1'], $request['titulo1'], $request['descripcion1'], $request['equipo1'], $request['falla1'], $request['tipo_solicitud1'], null, null);
        }
        elseif($request['tipo_solicitud1'] == 2){
            Solicitud::editSolicitud($request['idSolicitud1'], $request['estado1'], $request['titulo1'], $request['descripcion1'], null, $request['falla1'], $request['tipo_solicitud1'], null, $request['localizacion1']);
        }
        elseif($request['tipo_solicitud1'] == 3){
            Solicitud::editSolicitud($request['idSolicitud1'], $request['estado1'], $request['titulo1'], $request['descripcion1'], null, null, $request['tipo_solicitud1'], $request['area1'], null);
        }
       
        Session::flash('message','Solicitud editada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('solicitudes');
    }
}