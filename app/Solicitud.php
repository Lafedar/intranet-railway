<?php

namespace App;
use App\Historico_solicitudes;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Estado;

class Solicitud extends Model{
    public $table = "solicitudes_temp";
    public $timestamps = false;
    public function scopeID($query, $id_solicitud){
        if($id_solicitud){
            return $query -> where('id_solicitud','LIKE',"%$id_solicitud%");
        }
    }
    public function scopeTitulo($query, $titulo){
        if($titulo){
            return $query -> where('titulo','LIKE',"%$titulo%");
        }
    }
    public  function scopeEquipo ($query, $id_equipo){
    	if($id_equipo){
    	    return $query -> where('id_equipo','LIKE', "%$id_equipo%");
    	}
    }
    public  function scopeFalla ($query, $id_falla){
    	if($id_falla){
    	    return $query -> where('id_falla','LIKE', "%$id_falla%");
    	}
    }   
    public function scopeRelaciones_index($query, $id_tipo_solicitud, $id_estado, $id_encargado, $id_solicitante, $fecha){
        $query->leftJoin('historico_solicitudes', 'historico_solicitudes.id_solicitud', 'solicitudes_temp.id')
            ->leftJoin('fallas', 'fallas.id', 'solicitudes_temp.id_falla')
            ->leftJoin('personas as usuario_encargado', 'usuario_encargado.id_p', 'solicitudes_temp.id_encargado')
            ->leftJoin('personas as usuario_solicitante', 'usuario_solicitante.id_p', 'solicitudes_temp.id_solicitante')
            ->leftJoin('tipo_solicitudes', 'tipo_solicitudes.id', 'solicitudes_temp.id_tipo_solicitud')
            ->leftJoin('estados', 'estados.id', 'solicitudes_temp.id_estado')
            ->leftJoin('equipos_mant_temp', 'equipos_mant_temp.id', 'solicitudes_temp.id_equipo')
            ->leftJoin('area', 'area.id_a', 'equipos_mant_temp.id_area')
            ->select(
                'solicitudes_temp.id as id',
                'solicitudes_temp.titulo as titulo',
                'tipo_solicitudes.nombre as tipo_solicitud',
                'tipo_solicitudes.id as id_tipo_solicitud',
                'fallas.nombre as falla',
                'usuario_encargado.nombre_p as nombre_encargado',
                'usuario_encargado.apellido as apellido_encargado',
                'usuario_encargado.id_p as id_encargado',
                'usuario_solicitante.nombre_p as nombre_solicitante',
                'usuario_solicitante.apellido as apellido_solicitante',
                'usuario_solicitante.id_p as id_solicitante',
                'solicitudes_temp.id_equipo as id_equipo',
                'estados.nombre as estado',
                'solicitudes_temp.fecha_alta as fechaEmision',
                'solicitudes_temp.fecha_finalizacion as fechaFinalizacion',
                'area.id_a as area',
                DB::raw('(SELECT descripcion FROM historico_solicitudes WHERE id_solicitud = solicitudes_temp.id AND id_estado = 1 LIMIT 1) AS descripcion')
            )
            ->where('historico_solicitudes.actual', '=', 1);

        if ($id_tipo_solicitud != 0) {
            $query->where('id_tipo_solicitud', $id_tipo_solicitud);
        }
        if ($id_estado != 0) {
            $query->where('solicitudes_temp.id_estado', $id_estado);
        }
        if ($id_encargado != 0) {
            $query->where('id_encargado', $id_encargado);
        }
        if ($id_solicitante != 0) {
            $query->where('id_solicitante', $id_solicitante);
        }
        if ($fecha != null) {
            $query->where('fecha_alta', 'LIKE', "%$fecha%");
        }
        
        return $query;
    }
    public function scopeWithRelatedData($query, $id){
        return $query->select('solicitudes_temp.id as id', 
                'solicitudes_temp.titulo as titulo', 
                'tipo_solicitudes.nombre as tipo_solicitud', 
                'fallas.nombre as falla', 
                'usuario_encargado.nombre_p as nombre_encargado', 
                'usuario_encargado.apellido as apellido_encargado', 
                'usuario_solicitante.nombre_p as nombre_solicitante', 
                'usuario_solicitante.apellido as apellido_solicitante', 
                'solicitudes_temp.id_equipo as id_equipo', 
                'estados.nombre as estado', 
                'area_equipo.nombre_a as area_equipo', 
                'area_edilicio.nombre_a as area_edilicio', 
                'area_proyecto.nombre_a as area_proyecto',
                'loc_equipo.nombre as loc_equipo', 
                'loc_edilicio.nombre as loc_edilicio')
            ->leftjoin('fallas', 'fallas.id', 'solicitudes_temp.id_falla')
            ->leftjoin('historico_solicitudes', 'historico_solicitudes.id_solicitud', 'solicitudes_temp.id')
            ->leftjoin('estados', 'historico_solicitudes.id_estado', 'estados.id')
            ->leftjoin('personas as usuario_encargado', 'usuario_encargado.id_p', 'solicitudes_temp.id_encargado')
            ->leftjoin('personas as usuario_solicitante', 'usuario_solicitante.id_p', 'solicitudes_temp.id_solicitante')
            ->leftjoin('tipo_solicitudes', 'tipo_solicitudes.id', 'solicitudes_temp.id_tipo_solicitud')
            ->leftjoin('equipos_mant_temp', 'equipos_mant_temp.id', 'solicitudes_temp.id_equipo')
            ->leftjoin('localizaciones as loc_equipo', 'loc_equipo.id' ,'equipos_mant_temp.id_localizacion')
            ->leftjoin('localizaciones as loc_edilicio', 'loc_edilicio.id' ,'solicitudes_temp.id_localizacion_edilicio')
            ->leftjoin('area as area_equipo', 'area_equipo.id_a', 'equipos_mant_temp.id_area')
            ->leftjoin('area as area_edilicio', 'area_edilicio.id_a', 'loc_edilicio.id_area')
            ->leftjoin('area as area_proyecto', 'area_proyecto.id_a', 'solicitudes_temp.id_area_proyecto')
            ->where('historico_solicitudes.actual', '=', 1)
            ->where('solicitudes_temp.id', $id);
    }
    public function scopeHistoricoSolicitudes($query, $id){
        return $query->leftjoin('estados', 'estados.id', 'historico_solicitudes.id_estado') 
            ->where('id_solicitud', $id)
            ->select('historico_solicitudes.descripcion as descripcion', 
                'estados.nombre as estado', 
                'historico_solicitudes.fecha as fecha', 
                'historico_solicitudes.repuestos as rep', 
                'historico_solicitudes.descripcion_repuestos as desc_rep')
            ->from('historico_solicitudes')
            ->orderBy('fecha', 'desc')
            ->get();
    }
    public static function getEquiposMantenimientoConLocalizacionYArea(){
        return DB::table('equipos_mant_temp')
        ->leftJoin('localizaciones', 'localizaciones.id', 'equipos_mant_temp.id_localizacion')
        ->leftJoin('area', 'area.id_a', 'equipos_mant_temp.id_area')
        ->select('equipos_mant_temp.id as id', 'equipos_mant_temp.marca as marca', 'equipos_mant_temp.modelo as modelo', 'equipos_mant_temp.descripcion as descripcion',
        'localizaciones.nombre as localizacion', 'area.nombre_a as area')
        ->orderBy('id', 'asc')
        ->get();
    }
    public static function getEquiposMantenimiento(){
        return DB::table('equipos_mant_temp')->get();
    }
    public static function getEstados(){
        return DB::table('estados')->get();
    }
    public static function getFallas(){
        return DB::table('fallas')->get();
    }
    public static function getTipoEquipos(){
        return DB::table('tipos_equipos')->get();
    }
    public static function getFallasXTipo(){
        return DB::table('fallasxtipo')->get();
    }
    public static function getArea(){
        return DB::table('area')->get();
    }
    public static function getLocalizaciones(){
        return DB::table('localizaciones')->get();
    }
    public static function getTipoSolicitudes(){
        return DB::table('tipo_solicitudes')->get();
    }
    public static function getUsers(){
        return DB::table('users')->get();
    }
    public static function getModelHasRoles(){
        return DB::table('model_has_roles')->get();
    }
    public static function showSolicitudUpdate($id) {
        $solicitud = Solicitud::leftjoin('fallas', 'fallas.id', 'solicitudes_temp.id_falla')
            ->leftjoin('historico_solicitudes', 'historico_solicitudes.id_solicitud', 'solicitudes_temp.id')
            ->leftjoin('estados', 'historico_solicitudes.id_estado', 'estados.id')
            ->leftjoin('users as usuario_encargado', 'usuario_encargado.id', 'solicitudes_temp.id_encargado')
            ->leftjoin('users as usuario_solicitante', 'usuario_solicitante.id', 'solicitudes_temp.id_solicitante')
            ->leftjoin('tipo_solicitudes', 'tipo_solicitudes.id', 'solicitudes_temp.id_tipo_solicitud')
            ->leftjoin('equipos_mant_temp', 'equipos_mant_temp.id', 'solicitudes_temp.id_equipo')
            ->leftjoin('localizaciones', 'localizaciones.id' ,'equipos_mant_temp.id_localizacion')
            ->leftjoin('area', 'area.id_a', 'localizaciones.id_area')
            ->where('historico_solicitudes.actual', '=', 1)
            ->select('solicitudes_temp.id as id')
            ->find($id);
        return $solicitud;
    }
    public static function ultimoHistoricoById($id){
        return DB::table('historico_solicitudes')
        ->select('historico_solicitudes.id_solicitud as id_solicitud', 
            'historico_solicitudes.id_estado as id_estado', 
            'historico_solicitudes.fecha as fecha')
        ->where('historico_solicitudes.id_solicitud', $id)
        ->where('historico_solicitudes.actual', 1)
        ->first();
    }
    public static function updateHistorico($id, $estado, $fecha){
        DB::table('historico_solicitudes')
        ->where('historico_solicitudes.id_solicitud',$id)
        ->where('historico_solicitudes.id_estado',$estado) //id de estado
        ->where('historico_solicitudes.fecha',$fecha)
        ->update(['actual' => 0]);
    }
    public static function updateSoliciutud($id, $estado, $fecha){
        if ($estado == 6) {
            DB::table('solicitudes_temp')
                ->where('id', $id)
                ->update(['id_estado' => $estado, 'fecha_finalizacion' => $fecha]);
        } else {
            DB::table('solicitudes_temp')
                ->where('id', $id)
                ->update(['id_estado' => $estado]);
        }
    }
    public static function assingSolicitud($idSolicitud, $idUser){
        DB::table('solicitudes_temp')
        ->where('solicitudes_temp.id', $idSolicitud)
        ->update(['id_encargado' => $idUser]);
    }
    public static function getHistoricosDeUnaSolicitud($id){
        return DB::table('historico_solicitudes')
        ->where('historico_solicitudes.id_solicitud', $id)
        ->get();
    }
    public static function deleteHistorico($id){
       DB::table('historico_solicitudes')
        ->where('id_solicitud', $id)
        ->update(['id_estado' => 8]); 
        
    }
    public static function obtenerAreaUserAutenticado($idUser){
        return (DB::table('personas')
        ->leftJoin('users', 'users.id', 'personas.usuario')
        ->select('personas.area as area')
        ->where('users.id', $idUser)
        ->first());
    }
    public static function obtenerIdPersonaAutenticada($idUser){
        return (DB::table('personas')
        ->select('personas.id_p as id_p')
        ->where('usuario', $idUser)
        ->first());

    }
    public static function obtenerMailNombreTituloSolicitante($idSolicitud){
        $consulta = DB::table('personas')
        ->leftJoin('solicitudes_temp', 'solicitudes_temp.id_solicitante', 'personas.id_p')
        ->select('personas.correo as email', DB::raw('CONCAT(personas.nombre_p, " ", personas.apellido) as nombre'), 'solicitudes_temp.titulo as titulo')
        ->where('solicitudes_temp.id', $idSolicitud)
        ->first();

        return $consulta;
    }

    public static function obtenerMailNombreTituloEncargado($idSolicitud){
        $consulta = DB::table('personas')
        ->leftJoin('solicitudes_temp', 'solicitudes_temp.id_encargado', 'personas.id_p')
        ->select('personas.correo as email', DB::raw('CONCAT(personas.nombre_p, " ", personas.apellido) as nombre'), 'solicitudes_temp.titulo as titulo')
        ->where('solicitudes_temp.id', $idSolicitud)
        ->first();

        return $consulta;
    }
    public static function obtenerNombreEstadoSolicitud($idSolicitud){
        $consulta = DB::table('estados')
        ->leftJoin('solicitudes_temp', 'solicitudes_temp.id_estado', 'estados.id')
        ->select('estados.nombre as nombre')
        ->where('solicitudes_temp.id', $idSolicitud)
        ->first();

        return $consulta->nombre;
    }
    public static function obtenerUsersCorreoRepuestos(){
        $consulta = DB::table('permissions')
        ->leftjoin('role_has_permissions', 'role_has_permissions.permission_id', 'permissions.id')
        ->leftjoin('model_has_roles', 'model_has_roles.role_id', 'role_has_permissions.role_id')
        ->leftjoin('users', 'users.id', 'model_has_roles.model_id')
        ->select('users.email as email')
        ->where('permissions.name', 'correo-de-repuestos')
        ->get();

        return $consulta;
    }
    public static function editSolicitud($id, $estado, $titulo, $descripcion, $equipo, $falla, $tipo, $area, $localizacion){
        DB::table('solicitudes_temp')
            ->where('solicitudes_temp.id', $id)
            ->update([
                'titulo' => $titulo, 
                'id_equipo' => $equipo, 
                'id_falla' => $falla, 
                'id_tipo_solicitud' => $tipo, 
                'id_area_proyecto' => $area, 
                'id_localizacion_edilicio' => $localizacion]);
        
        //este codigo se utiliza desarrollo por incompatibilidad en las versiones de la base de datos con la consulta
        /*$fecha = DB::table('historico_solicitudes')
            ->select('fecha')
            ->where('historico_solicitudes.id_solicitud', $id)
            ->where('historico_solicitudes.id_estado', $estado)
            ->orderBy('fecha', 'asc')
            ->limit(1)
            ->value('fecha');

        DB::table('historico_solicitudes AS hs')
            ->where('hs.id_solicitud', $id)
            ->where('hs.id_estado', $estado)
            ->where('hs.fecha', $fecha)
            ->update(['descripcion' => $descripcion]);
        }*/

        DB::table('historico_solicitudes')
            ->where('historico_solicitudes.id_solicitud', $id)
            ->where('historico_solicitudes.id_estado',$estado)
            ->where('historico_solicitudes.fecha', '=', function ($query) use ($id, $estado) {
                $query->select('fecha')
                    ->from('historico_solicitudes')
                    ->where('historico_solicitudes.id_solicitud', $id)
                    ->where('historico_solicitudes.id_estado', $estado)
                    ->orderBy('fecha', 'asc')
                    ->limit(1);
            })
            ->update(['historico_solicitudes.descripcion' => $descripcion]);
    }
    public static function obtenerSolicitante($idSolicitud){
        return DB::table('solicitudes_temp')
            ->join('personas', 'solicitudes_temp.id_solicitante', '=', 'personas.id_p')
            ->select('personas.nombre_p', 'personas.apellido')
            ->where('solicitudes_temp.id', $idSolicitud)
            ->first();
    }

    public static function obtenerEncargado($idSolicitud){
        return DB::table('solicitudes_temp')
            ->join('personas', 'solicitudes_temp.id_encargado', '=', 'personas.id_p')
            ->select('personas.nombre_p', 'personas.apellido')
            ->where('solicitudes_temp.id', $idSolicitud)
            ->first();
    }

    public static function ultimoRecordatorio($idSolicitud) {
        return DB::table('solicitudes_temp')
            ->where('id', $idSolicitud)
            ->latest()
            ->first();
    }
    public function estado()
{
    return $this->belongsTo(Estado::class, 'id_estado');
}

    

 
}

?>
