<?php
use Spatie\Health\Http\Controllers\HealthCheckResultsController;
use App\Http\Controllers\CursoController; 
use App\Http\Controllers\CursoInstanciaController;

Auth::routes();


   //****************MENU INICIAL**********************
Route::get('/home', 'HomeController@index');

Route::get('/', 'HomeController@index');

Route::get('/internos','HomeController@internos');

Route::get('notificaciones','HomeController@notificaciones')->name('notificaciones');


Route::group(['middleware' => ['auth']], function () {
  Route::resource('permisos','PermisosController')->middleware('role:administrador|jefe|rrhh');

  Route::get('destroy_permiso/{id}','PermisosController@destroy_permiso')->name('destroy_permiso');

  Route::get('select_autorizado','PermisosController@select_autorizado')->name('select_autorizado');

  Route::get('select_tipo_permiso','PermisosController@select_tipo_permiso')->name('select_tipo_permiso');
});

   //****************RECEPCION**********************
Route::group(['middleware' => ['auth']], function () {
  Route::resource('persona', 'PersonaController')->middleware('role:administrador|recepcion|rrhh');
  
  Route::get('destroy_contacto/{id}', 'PersonaController@destroy_contacto')->name('destroy_contacto');
});

   //****************EMPLEADOS**********************
   Route::group(['middleware' => ['auth']], function () {
    Route::resource('empleado', 'EmpleadoController')->middleware('role:administrador|rrhh');
  
    Route::get('showUpdateAreaXJefe/{id_ja}',['uses' => 'EmpleadoController@showUpdateAreaXJefe'])->name('showUpdateAreaXJefe');
    Route::get('deleteAreaXJefe/{id_ja}', ['uses' => 'EmpleadoController@deleteAreaXJefe'])->name('deleteAreaXJefe');
    Route::get('obtenerNuevoListadoAreaXJefe/{idJefe}', ['uses' => 'EmpleadoController@obtenerNuevoListadoAreaXJefe'])->name('obtenerNuevoListadoAreaXJefe');
    Route::get('showStoreAreaXJefe/{id_ja}', ['uses' => 'EmpleadoController@showStoreAreaXJefe'])->name('showStoreAreaXJefe');
    Route::get('storeRelacionJefeXArea/{jefeId}/{areaId}/{turnoId}', ['uses' => 'EmpleadoController@storeRelacionJefeXArea'])->name('storeRelacionJefeXArea');
  
    Route::get('/novedades','HomeController@novedades')->middleware('role:administrador|rrhh');
    Route::post('/store_novedades','HomeController@store_novedades')->middleware('role:administrador|rrhh');
    Route::get('destroy_empleado/{id}', 'EmpleadoController@destroy_empleado')->name('destroy_empleado');
  
    Route::get('selectAreasTurnos', 'EmpleadoController@selectAreasTurnos');
    Route::get('selectAreaEmpleados', 'EmpleadoController@selectAreaEmpleados');
    Route::get('selectTurnosEmpleados', 'EmpleadoController@selectTurnosEmpleados');

    Route::get('/empleado/curso', [EmpleadoController::class, 'curso'])->name('empleado.curso');
  });
   //****************PUESTOS**********************
Route::group(['middleware' => ['auth']], function () {

  Route::get('/puestos', 'PuestoController@puestos')->middleware('role:administrador|ingenieria');

  Route::get('destroy_puesto/{id_puesto}', 'PuestoController@destroy_puesto')->middleware('role:administrador');

  Route::get('show_store_puesto',['uses' => 'PuestoController@show_store_puesto'])->name('show_store_puesto');
  Route::post('store_puesto','PuestoController@store_puesto')->name('store_puesto');
  
  Route::get('show_update_puesto/{puesto}',['uses' => 'PuestoController@show_update_puesto'])->name('show_update_puesto');
  Route::post('update_puesto','PuestoController@update_puesto')->name('update_puesto');

  Route::get('select_area','PuestoController@select_area')->name('select_area');
  Route::get('select_persona','PuestoController@select_persona')->name('select_persona');
  Route::get('select_localizaciones','PuestoController@select_localizaciones')->name('select_localizaciones');
  Route::get('select_localizaciones_by_area/{areaId}', 'PuestoController@select_localizaciones_by_area')->name('select_localizaciones_by_area');
  Route::get('select_area_by_localizacion/{localizacionId}', 'PuestoController@select_area_by_localizacion')->name('select_area_by_localizacion');
  Route::get('getPuesto/{idPuesto}', ['uses' => 'PuestoController@getPuesto'])->name('getPuesto');
});

   //****************EQUIPAMIENTO**********************
Route::group(['middleware' => ['auth']], function () {

  Route::get('/sistemas','HomeController@sistemas')->middleware('role:administrador|ingenieria');

  Route::resource('equipamiento', 'EquipamientoController')->middleware('role:administrador|ingenieria');

  Route::get('listado_ip', 'EquipamientoController@listIp')->middleware('role:administrador|ingenieria');

  Route::get('select_puesto','EquipamientoController@select_puesto')->name('select_puesto');

  Route::post('/store_relacion','EquipamientoController@store_relacion')->middleware('role:administrador');

  Route::get('destroy_relacion/{relacion}', ['uses' => 'EquipamientoController@destroy_relacion'])->middleware('role:administrador');

  Route::get('select_tipo_equipamiento', 'EquipamientoController@select_tipo_equipamiento')->name('select_tipo_equipamiento');

  Route::get('select_ips', 'EquipamientoController@select_ips')->name('select_ips');

  Route::get('modal_editar_equipamiento/{id}','EquipamientoController@modal_editar_equipamiento')->name('modal_editar_equipamiento')->middleware('role:administrador');

  Route::get('/listado-ip', 'EquipamientoController@listIp')->name('listado_ip');

});


   //****************INCIDENTES**********************
Route::group(['middleware' => ['auth']], function () {

  Route::get('create_incidente/{id_equipamiento}', 'IncidenteController@create_incidente')->middleware('role:administrador');

  Route::post('/store_incidente','IncidenteController@store_incidente')->middleware('role:administrador');

  Route::get('/incidentes', 'IncidenteController@incidentes')->middleware('role:administrador');

  Route::post('/update_incidente','IncidenteController@update_incidente')->middleware('role:administrador');
});

   //****************VISITAS**********************
Route::group(['middleware' => ['auth']], function () {

  Route::resource('visitas', 'VisitaController')->middleware('role:administrador|guardia|rrhh');

  Route::get('/asignar','VisitaController@asignar')->middleware('role:administrador|guardia|rrhh');

  Route::post('/baja','VisitaController@baja')->middleware('role:administrador|guardia|rrhh');

  Route::get('/consulta','VisitaController@consulta')->middleware('role:administrador|guardia|rrhh');

  Route::post('/añadir_empresa','VisitaController@añadir_empresa')->middleware('role:administrador|guardia|rrhh');

  Route::post('/añadir_externo','VisitaController@añadir_externo')->middleware('role:administrador|guardia|rrhh');

  Route::get('ExternoByEmpresa/{id}', 'VisitaController@getExterno')->middleware('role:administrador|guardia|rrhh');

  Route::get('fotoExterno/{id}','VisitaController@fotoExterno')->name('fotoExterno');

  Route::get('listado','VisitaController@listado')->name('listado');
 
  Route::post('/editar_externo','VisitaController@editar_externo')->middleware('role:administrador|guardia|rrhh');

  Route::get('destroy_externo/{dni}','VisitaController@destroy_externo')->name('destroy_externo');

});

   //****************USUARIOS**********************
Route::group(['middleware' => ['auth']], function () {

  Route::get('/usuarios', 'UsuarioController@usuarios')->middleware('role:administrador');

  Route::get('create_usuario', 'UsuarioController@create_usuario')->middleware('role:administrador');

  Route::get('destroy_usuario/{id}','UsuarioController@destroy_usuario')->name('destroy_usuario');

  Route::post('asignar_rol', ['uses' => 'UsuarioController@asignar_rol'])->middleware('role:administrador');

  Route::post('revocar_rol', ['uses' => 'UsuarioController@revocar_rol'])->middleware('role:administrador');

  Route::get('select_roles/{id}','UsuarioController@select_roles')->name('select_roles');

  Route::get('select_revocar_roles/{id}','UsuarioController@select_revocar_roles')->name('select_revocar_roles');

  Route::get('select_personas', 'UsuarioController@select_personas')->name('select_personas');
  
  Route::post('store_usuario', 'UsuarioController@store_usuario')->middleware('role:administrador');
});

//****************USUARIOS**********************
Route::group(['middleware' => ['auth']], function () {

  Route::get('/roles', 'RolController@roles')->middleware('role:administrador');

  Route::get('destroy_rol/{id}','rolController@destroy_rol')->name('destroy_rol');

  Route::post('store_rol', ['uses' => 'RolController@store_rol'])->middleware('role:administrador');

  Route::post('store_permiso', ['uses' => 'RolController@store_permiso'])->middleware('role:administrador');

  Route::post('asignar_permiso', ['uses' => 'RolController@asignar_permiso'])->middleware('role:administrador');

  Route::post('revocar_permiso', ['uses' => 'RolController@revocar_permiso'])->middleware('role:administrador');

  Route::get('select_permiso/{id}','RolController@select_permiso')->name('select_permiso');

  Route::get('select_revocar_permiso/{id}','RolController@select_revocar_permiso')->name('select_revocar_permiso');
});

   //****************MEDICO**********************

Route::group(['middleware' => ['auth']], function () {

  Route::resource('medico','MedicoController')->middleware('role:administrador|medico|rrhh');

  Route::post('/añadir_motivo','MedicoController@añadir_motivo')->middleware('role:administrador|medico|rrhh');

  Route::get('/historia_clinica','MedicoController@historia_clinica')->middleware('role:administrador|medico|rrhh');

  Route::post('/store_historia_clinica','MedicoController@store_historia_clinica')->middleware('role:administrador|medico|rrhh');

  Route::get('reporte_medico/{id_paciente}', 'MedicoController@reporte_medico')->middleware('role:administrador|medico|rrhh');
});

   //****************DOCUMENTACION**********************
Route::get('documentos','HomeController@documentos');

Route::group(['middleware' => ['auth']], function () {
  Route::get('planos','PlanoController@planos')->middleware('role:administrador|planos|ingenieria');
  Route::post('store_planos','PlanoController@store_planos')->middleware('role:administrador|planos|ingenieria');
  Route::get('destroy_plano/{plano}', ['uses' => 'PlanoController@destroy_planos'])->middleware('role:administrador|ingenieria');
  Route::post('update_planos','PlanoController@update_planos')->middleware('role:administrador|ingenieria|planos')->name('update_planos');
});

  //****************PROYECTOS**********************
Route::group(['middleware' => ['auth']], function () {
  Route::get('proyectos','ProyectoController@proyectos')->middleware('role:administrador|ingenieria|proyectos');
  Route::post('store_proyectos','ProyectoController@store_proyectos')->middleware('role:administrador|ingenieria');
  Route::post('update_proyectos','ProyectoController@update_proyectos')->middleware('role:administrador|ingenieria')->name('update_proyectos');
  Route::get('destroy_proyecto/{proyecto}', ['uses' => 'ProyectoController@destroy_proyecto'])->middleware('role:administrador|ingenieria')->name('destroy_proyecto');
});

//****************POLITICAS**********************
Route::group(['middleware' => ['auth']], function () {
  
  Route::post('store_politica','PoliticaController@store_politica')->name('agregar-politica')->middleware('role:administrador|politicas');
  Route::get('destroy_politica/{politica}', ['uses' => 'PoliticaController@destroy_politica'])->middleware('role:administrador|politicas');
  Route::post('update_politica','PoliticaController@update_politica')->middleware('role:administrador|politicas')->name('update_politicas');
});
Route::get('politicas','PoliticaController@index');//->middleware('role:administrador|politicas');

//****************INSTRUCTIVOS**********************
Route::group(['middleware' => ['auth']], function () {
  Route::get('instructivos','InstructivoController@index');
  Route::get('/instructivos', 'InstructivoController@index')->name('instructivos.index');

  Route::get('show_store_instructivo',['uses' => 'InstructivoController@show_store_instructivo'])->name('show_store_instructivo');
  Route::post('store_instructivo','InstructivoController@store_instructivo')->name('store_instructivo')->middleware('role:administrador|instructivos');

  Route::get('show_update_instructivo/{instructivo}',['uses' => 'InstructivoController@show_update_instructivo'])->name('show_update_instructivo');
  Route::post('update_instructivo','InstructivoController@update_instructivo')->name('update_instructivo')->middleware('role:administrador|instructivos');

  Route::get('destroy_instructivo/{instructivo}', ['uses' => 'InstructivoController@destroy_instructivo'])->middleware('role:administrador|instructivos');
  Route::get('select_tipo_instructivos', 'InstructivoController@select_tipo_instructivos')->name('select_tipo_instructivos');

});

//******************************QAD-Controller

Route::group(['middleware' => ['auth']], function () {
  Route::get('qad','QADController@planos')->middleware('role:administrador|ingenieria|planos');

Route::get('qad','QADController@index')->middleware('role:administrador|ingenieria|planos');

Route::get('ot','QADController@ot');

Route::get('oc','QADController@oc');
});

//***************Evento-Calendario-reserva*********************


// formulario
// formulario
Route::get('Evento/form','EventController@form');
Route::post('Evento/create','EventController@create');
// Detalles de evento
Route::get('Evento/details/{id}','EventController@details');
Route::post('Evento/', 'EventController@updates');
// Calendario
Route::get('Evento','EventController@index');
Route::get('Evento/index/{month}','EventController@index_month');
//cancelar evento
Route::patch('update/{evento}','EventController@updates')->name('event.update');

//*******************Software**********************************
 Route::group(['middleware' => ['auth']], function () {

  Route::get('/Software', 'SoftwareController@Software')->middleware('role:administrador');

  Route::get('select_soft','EquipamientoController@select_soft')->name('select_soft');

  
  Route::post('/soft_store','SoftwareController@soft_store')->middleware('role:administrador');

  Route::get('/Instalado','SoftwareController@Instalado')->middleware('role:administrador');


  Route::post('/store_srelacions','SoftwareController@store_srelacions')->middleware('role:administrador');

  Route::get('destroy_srelacions/{id}', 'SoftwareController@destroy_srelacions')->name('destroy_srelacions');
   Route::post('/updates','SoftwareController@updates')->name('software.updates')->middleware('role:administrador');

  Route::post('/updat','SoftwareController@updat')->name('software.update')->middleware('role:administrador');

});

 //***********************************fullCalendar**************************************

 Route::resource('/eventos','EventosController');

 //********************************Almuerzo***********************************************

 Route::get('/almuerzo','AlmuerzoController@inicio')->name('almuerzo.inicio');
 Route::get('/nuevo_al','AlmuerzoController@nuevo')->name('nuevo');
 Route::get('/elegir_m','AlmuerzoController@selec')->name('seleccionar');
 Route::post('/guardar_a','AlmuerzoController@carga_inicial')->name('guardara');
 Route::post('/actualizar','AlmuerzoController@actualizar')->name('guarda');
 Route::get('/nuev_sem','AlmuerzoController@nuevasemana')->name('almuerzo.nuevasemana');

 Route::post('/cargar_al', 'AlmuerzoController@cargar')->name('almuerzo.cargar');
 
 Route::get('mostrar/{fecha_desde}','AlmuerzoController@mostrar')->name('almuerzo.mostrar');

 Route::get('almu/{id}','AlmuerzoController@actualizaral');

 
 Route::post('export', 'AlmuerzoController@export');
 Route::get('download', 'AlmuerzoController@download')->name('almuerzo.download');
 Route::put('/cambiar_a','AlmuerzoController@update')->name('almuerzo.update');
 Route::get('/comi', 'AlmuerzoController@Menu')->name('menusid');
 Route::get('/clog','AlmuerzoController@aloguin')->name('alogin');
 Route::post('/almuerzo','AlmuerzoController@elegir')->name('aeleccion');

 
 Route::get('/seman/{id}','AlmuerzoController@mostrarsemana')->name('semanaactual');
 Route::get('/cerase', 'AlmuerzoController@semana_cer')->name('cerrarsem');
 Route::put('/cerse','AlmuerzoController@cerrar_semana')->name('cerrarsema');

  //***********************************Power BI*************************************
  Route::group(['middleware' => ['auth']], function () 
  {
    Route::resource('powerbis', 'PowerbisController')->middleware('role:administrador');
   
  });
  
  //Route::get('nombre','HomeController@nombre');
  //Route::get('powerbis','HomeController@powerbis');//->middleware('role:administrador');
   
  

   //****************Ventas**********************

Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('ventas','VentaController')->middleware('role:administrador|venta');
  Route::post('store_venta','VentaController@store_venta')->name('agregar-powerbi')->middleware('role:administrador|venta');
  Route::get('destroy_venta/{venta}', ['uses' => 'VentaController@destroy_venta'])->middleware('role:administrador|venta');
  Route::post('update_venta','VentaController@update_venta')->middleware('role:administrador|venta')->name('update_ventas');
});
  //****************Compras**********************
Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('compras','CompraController')->middleware('role:administrador|compra');
  Route::post('store_compra','CompraController@store_compra')->name('agregar-powerbi')->middleware('role:administrador|compra');
  Route::get('destroy_compra/{compra}', ['uses' => 'CompraController@destroy_compra'])->middleware('role:administrador|compra');
  Route::post('update_compra','CompraController@update_compra')->middleware('role:administrador|compra')->name('update_compras');
});
  
  //****************Calidad**********************
Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('calidades','CalidadController')->middleware('role:administrador|calidad');
  Route::post('store_calidad','CalidadController@store_calidad')->name('agregar-powerbi')->middleware('role:administrador|calidad');
  Route::get('destroy_calidad/{calidad}', ['uses' => 'CalidadController@destroy_calidad'])->middleware('role:administrador|calidad');
  Route::post('update_calidad','CalidadController@update_calidad')->middleware('role:administrador|calidad')->name('update_calidades');
});
  //****************Costos***********************
Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('costos','CostoController')->middleware('role:administrador|costo');
  Route::post('store_costo','CostoController@store_costo')->name('agregar-powerbi')->middleware('role:administrador|costo');
  Route::get('destroy_costo/{costo}', ['uses' => 'CostoController@destroy_costo'])->middleware('role:administrador|costo');
  Route::post('update_costo','CostoController@update_costo')->middleware('role:administrador|costo')->name('update_costos');
});
  //****************Produccion***********************
Route::group(['middleware' => ['auth']], function () 
{
    Route::resource('producciones','ProduccionController')->middleware('role:administrador|produccion');
    Route::post('store_produccion','ProduccionController@store_produccion')->name('agregar-powerbi')->middleware('role:administrador|produccion');
    Route::get('destroy_produccion/{produccion}', ['uses' => 'ProduccionController@destroy_produccion'])->middleware('role:administrador|produccion');
    Route::post('update_produccion','ProduccionController@update_produccion')->middleware('role:administrador|produccion')->name('update_producciones');
});
    //****************Rrhhs***********************
Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('rrhhs','RrhhController')->middleware('role:administrador|rrhh');
  Route::post('store_rrhh','RrhhController@store_rrhh')->name('agregar-powerbi')->middleware('role:administrador|rrhh');
  Route::get('destroy_rrhh/{rrhh}', ['uses' => 'RrhhController@destroy_rrhh'])->middleware('role:administrador|rrhh');
  Route::post('update_rrhh','RrhhController@update_rrhh')->middleware('role:administrador|rrhh')->name('update_rrhhs');
});
//***********************************Frecuencias*************************************
Route::get('/frecuencias', 'FrecuenciasController@index');

   //****************Mantenimiento**********************
   Route::get('mantenimiento','HomeController@mantenimiento');
Route::group(['middleware' => ['auth']], function () 
{
  
  Route::resource('solicitudes','SolicitudController');
  Route::resource('historico_solicitudes','SolicitudController');

  Route::get('show_store_solicitud',['uses' => 'SolicitudController@show_store_solicitud'])->name('show_store_solicitud');
  Route::post('store_solicitud','SolicitudController@store_solicitud')->name('store_solicitud');

  Route::get('show_assing_solicitud/{solicitud}',['uses' => 'SolicitudController@show_assing_solicitud'])
    ->middleware('role:administrador|Jefe-Mantenimiento|Empleado-Mantenimiento-Asigna-Solicitudes|Empleado-Mantenimiento-Ve-Proyectos|Empleado-Mantenimiento-Ve-Proyectos-Asigna')
    ->name('show_assing_solicitud');
  Route::post('assing_solicitud','SolicitudController@assing_solicitud')
    ->middleware('role:administrador|Jefe-Mantenimiento|Empleado-Mantenimiento-Asigna-Solicitudes|Empleado-Mantenimiento-Ve-Proyectos|Empleado-Mantenimiento-Ve-Proyectos-Asigna')
    ->name('assing_solicitud');

  Route::get('show_update_solicitud/{solicitud}',['uses' => 'SolicitudController@show_update_solicitud'])
    ->middleware('role:administrador|Jefe-Mantenimiento|Empleado-Mantenimiento|Empleado-Mantenimiento-Asigna-Solicitudes|Empleado-Mantenimiento-Ve-Solicitudes|Empleado-Mantenimiento-Ve-Proyectos|Ver-Todas-Las-Solicitudes-Y-Proyectos|Empleado-Mantenimiento-Ve-Proyectos-Asigna')
    ->name('show_update_solicitud');
  Route::post('update_solicitud','SolicitudController@update_solicitud')->name('update_solicitud');

  Route::get('show_edit_solicitud/{solicitud}',['uses' => 'SolicitudController@show_edit_solicitud'])->name('show_edit_solicitud');
  Route::post('edit_solicitud','SolicitudController@edit_solicitud')->name('edit_solicitud');

  Route::get('show_reclamar_solicitud/{solicitud}',['uses' => 'SolicitudController@show_reclamar_solicitud'])->name('show_reclamar_solicitud');
  Route::post('reclaim_solicitud','SolicitudController@reclaim_solicitud')->name('reclaim_solicitud');

  Route::get('show_mostrar_equipos_mant',['uses' => 'SolicitudController@show_mostrar_equipos_mant'])->name('show_mostrar_equipos_mant');
  Route::post('mostrar_equipos_mant','SolicitudController@mostrar_equipos_mant')->name('mostrar_equipos_mant');
  
  Route::get('show_solicitud/{solicitud}', ['uses' => 'SolicitudController@show_solicitud'])->name('show_solicitud');
  Route::get('aprobar_solicitud/{solicitud}', ['uses' => 'SolicitudController@aprobar_solicitud']);
  Route::get('destroy_solicitud/{solicitud}', ['uses' => 'SolicitudController@destroy_solicitud']);

  Route::get('select_tablas', 'SolicitudController@select_tablas')->name('select_tablas');
  Route::get('select_estado', 'SolicitudController@select_estado')->name('select_estado');
  Route::get('select_users', 'SolicitudController@select_users')->name('select_users');
  Route::get('select_equipos', 'SolicitudController@select_equipos')->name('select_equipos');
  Route::get('getHistoricos/{solicitud}', ['uses' => 'SolicitudController@getHistoricos'])->name('getHistoricos');
  Route::get('getSolicitud/{idSolicitud}', ['uses' => 'SolicitudController@getSolicitud'])->name('getSolicitud');

  Route::post('enviar-recordatorio/{id}', 'SolicitudController@enviarRecordatorio')->name('enviar.recordatorio');

  Route::post('/verificar-envio-permitido/{id}', 'SolicitudController@verificarEnvioPermitido')->name('verificar.envio.permitido');
});

Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('equipos_mant','Equipo_mantController')->middleware('role:administrador|Jefe-GarantiaDeCalidad|Jefe-Mantenimiento|Empleado-Mantenimiento');
  Route::get('show_store_equipo_mant',['uses' => 'Equipo_mantController@show_store_equipo_mant'])->middleware('role:administrador|Jefe-GarantiaDeCalidad|Jefe-Mantenimiento')->name('show_store_equipo_mant');
  Route::post('store_equipo_mant','Equipo_mantController@store_equipo_mant')->middleware('role:administrador|Jefe-GarantiaDeCalidad|Jefe-Mantenimiento')->name('store_equipo_mant');
  Route::get('show_update_equipo_mant/{equipo_mant}',['uses' => 'Equipo_mantController@show_update_equipo_mant'])->middleware('role:administrador|Jefe-GarantiaDeCalidad|Jefe-Mantenimiento')->name('show_update_equipo_mant');
  Route::post('update_equipo_mant','Equipo_mantController@update_equipo_mant')->middleware('role:administrador|Jefe-GarantiaDeCalidad|Jefe-Mantenimiento')->name('update_equipo_mant');

  Route::get('select_tipo_equipo', 'Equipo_mantController@select_tipo_equipo')->name('select_tipo_equipo');
  Route::get('select_area_localizacion', 'Equipo_mantController@select_area_localizacion')->name('select_area_localizacion');
});

Route::get('parametros_mantenimiento','HomeController@parametros_mantenimiento');
Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('areas','AreaController')->middleware('role:administrador|Jefe-Mantenimiento');
  Route::get('show_store_area',['uses' => 'AreaController@show_store_area'])->middleware('role:administrador|Jefe-Mantenimiento')->name('show_store_area');
  Route::post('store_area','AreaController@store_area')->name('store_area');
  Route::get('show_update_area/{area}',['uses' => 'AreaController@show_update_area'])->name('show_update_area');
  Route::post('update_area','AreaController@update_area')->name('update_area');
});
Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('localizaciones','LocalizacionController')->middleware('role:administrador|Jefe-Mantenimiento');
  Route::get('show_store_localizacion',['uses' => 'LocalizacionController@show_store_localizacion'])->middleware('role:administrador|Jefe-Mantenimiento')->name('show_store_localizacion');
  Route::post('store_localizacion','LocalizacionController@store_localizacion')->name('store_localizacion');
  Route::get('show_update_localizacion/{localizacion}',['uses' => 'LocalizacionController@show_update_localizacion'])->name('show_update_localizacion');
  Route::post('update_localizacion','LocalizacionController@update_localizacion')->name('update_localizacion');

  Route::get('select_area', 'LocalizacionController@select_area')->name('select_area');
});
Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('estados','EstadoController')->middleware('role:administrador|Jefe-Mantenimiento');
  Route::get('show_store_estado',['uses' => 'EstadoController@show_store_estado'])->middleware('role:administrador|Jefe-Mantenimiento')->name('show_store_estado');
  Route::post('store_estado','EstadoController@store_estado')->name('store_estado');
  Route::get('show_update_estado/{estado}',['uses' => 'EstadoController@show_update_estado'])->name('show_update_estado');
  Route::post('update_estado','EstadoController@update_estado')->name('update_estado');
});
Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('fallas','FallaController')->middleware('role:administrador|Jefe-Mantenimiento');
  Route::get('show_store_falla',['uses' => 'FallaController@show_store_falla'])->middleware('role:administrador|Jefe-Mantenimiento')->name('show_store_falla');
  Route::post('store_falla','FallaController@store_falla')->name('store_falla');
  Route::get('show_update_falla/{falla}',['uses' => 'FallaController@show_update_falla'])->name('show_update_falla');
  Route::post('update_falla','FallaController@update_falla')->name('update_falla');
});
Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('tipos_equipos','Tipo_EquipoController')->middleware('role:administrador|Jefe-Mantenimiento');
  Route::get('show_store_tipo_equipo',['uses' => 'Tipo_EquipoController@show_store_tipo_equipo'])->middleware('role:administrador|Jefe-Mantenimiento')->name('show_store_tipo_equipo');
  Route::post('store_tipo_equipo','Tipo_EquipoController@store_tipo_equipo')->name('store_tipo_equipo')->middleware('role:administrador|Jefe-Mantenimiento');
  Route::get('show_update_tipo_equipo/{tipo_equipo}',['uses' => 'Tipo_EquipoController@show_update_tipo_equipo'])->name('show_update_tipo_equipo')->middleware('role:administrador|Jefe-Mantenimiento');
  Route::post('update_tipo_equipo','Tipo_EquipoController@update_tipo_equipo')->name('update_tipo_equipo')->middleware('role:administrador|Jefe-Mantenimiento');
  Route::get('show_delete_falla_te/{falla}',['uses' => 'Tipo_EquipoController@show_delete_falla_te'])->name('show_delete_falla_te');
  Route::post('delete_falla_te','Tipo_EquipoController@delete_falla_te')->name('delete_falla_te')->middleware('role:administrador|Jefe-Mantenimiento');
  Route::get('show_assing_tipo_equipo/{tipo_equipo}',['uses' => 'Tipo_EquipoController@show_assing_tipo_equipo'])->middleware('role:administrador|Jefe-Mantenimiento')->name('show_assing_tipo_equipo');
  Route::post('assing_tipo_equipo','Tipo_EquipoController@assing_tipo_equipo')->middleware('role:administrador|Jefe-Mantenimiento')->name('assing_tipo_equipo');

  Route::get('select_fallas', 'Tipo_EquipoController@select_fallas')->name('select_fallas');
});
Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('tipos_solicitudes','Tipo_SolicitudController')->middleware('role:administrador|Jefe-Mantenimiento');
  Route::get('show_store_tipo_solicitud',['uses' => 'Tipo_SolicitudController@show_store_tipo_solicitud'])->middleware('role:administrador|Jefe-Mantenimiento')->name('show_store_tipo_solicitud');
  Route::post('store_tipo_solicitud','Tipo_SolicitudController@store_tipo_solicitud')->name('store_tipo_solicitud');
  Route::get('show_update_tipo_solicitud/{tipo_solicitud}',['uses' => 'Tipo_SolicitudController@show_update_tipo_solicitud'])->name('show_update_tipo_solicitud');
  Route::post('update_tipo_solicitud','Tipo_SolicitudController@update_tipo_solicitud')->name('update_tipo_solicitud');
  
});


Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('parametros_gen','ParametrosGenController')->middleware('role:administrador|Jefe-Mantenimiento');
  Route::get('/parametros_gen_sistemas', 'ParametrosGenController@indexSistemas')->name('parametros-gen-sistemas.index');
  Route::post('guardar-datos', 'ParametrosGenController@store')->name('guardar_datos');
  Route::put('parametros/{parametro}', 'ParametrosGenController@update')->name('parametros.update');
  Route::delete('/parametros/{parametro}', 'ParametrosGenController@destroy')->name('parametros.destroy');

  Route::get('obtener-megabytes-maximos', 'ParametrosGenController@obtenerMegabytesMaximos')->name('obtener_megabytes_maximos');
 
});

  //****************Capacitacion***********************

  Route::group(['middleware' => ['auth']], function () 
  {
    
    Route::get('/cursos', [CursoController::class, 'listAll'])->name('cursos.index');
    Route::post('/cursos/store', [CursoController::class, 'store'])->name('cursos.store');
    Route::delete('/cursos/destroy/{id}', [CursoController::class, 'destroy'])->name('cursos.destroy');
    Route::get('/cursos/{id}/edit', [CursoController::class, 'edit'])->name('cursos.edit');
    Route::put('/cursos/{id}', [CursoController::class, 'update'])->name('cursos.update');
    Route::get('/cursos/create', [CursoController::class, 'create'])->name('cursos.create');
    Route::post('cursos/{curso}/instancias', [CursoInstanciaController::class, 'store'])->name('cursos.instancias.store');
    Route::get('/cursos/{cursoId}/inscritos', [CursoController::class, 'getInscriptos'])->name('cursos.inscritos');


    Route::get('cursos/{curso}/instancias/create', [CursoInstanciaController::class, 'create'])->name('cursos.instancias.create');
    Route::get('/cursos/{cursoId}/instancias', [CursoInstanciaController::class, 'index'])->name('cursos.instancias.index');
    Route::get('/cursos/{cursoId}/{instanciaId}', [CursoInstanciaController::class, 'inscription'])->name('cursos.instancias.inscription');
    Route::delete('instancias/{instancia}', [CursoInstanciaController::class, 'destroy'])->name('cursos.instancias.destroy');
    
    Route::get('instancias/{instancia}/edit', [CursoInstanciaController::class, 'edit'])->name('cursos.instancias.edit');
    Route::put('instancias/{instancia}', [CursoInstanciaController::class, 'update'])->name('cursos.instancias.update');
    

    //Route::get('/capacitacion','HomeController@cursos');
    //Route::get('/capacitacion', [CursoController::class, 'listAll'])->name('parametros-gen-sistemas.index');
    
   
  });
  use App\Http\Controllers\EmpleadoController;

  Route::get('/empleado/{id}/cursos', [EmpleadoController::class, 'getCursos'])->name('empleado.cursos');


 


