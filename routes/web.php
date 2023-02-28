<?php

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

  Route::get('/novedades','HomeController@novedades')->middleware('role:administrador|rrhh');

  Route::post('/store_novedades','HomeController@store_novedades')->middleware('role:administrador|rrhh');

  Route::get('destroy_empleado/{id}', 'EmpleadoController@destroy_empleado')->name('destroy_empleado');

});

   //****************PUESTOS**********************
Route::group(['middleware' => ['auth']], function () {

  Route::get('/puestos', 'PuestoController@puestos')->middleware('role:administrador|ingenieria');

  Route::post('/store','PuestoController@store')->middleware('role:administrador');

  Route::get('edit_puesto/{id_puesto}', 'PuestoController@edit_puesto')->middleware('role:administrador');

  Route::get('destroy_puesto/{id_puesto}', 'PuestoController@destroy_puesto')->middleware('role:administrador');

  Route::post('/update_puesto','PuestoController@update_puesto')->middleware('role:administrador');

  Route::get('select_area','PuestoController@select_area')->name('select_area');

  Route::get('select_persona','PuestoController@select_persona')->name('select_persona');
});

   //****************EQUIPAMIENTO**********************
Route::group(['middleware' => ['auth']], function () {

  Route::get('/sistemas','HomeController@sistemas')->middleware('role:administrador|ingenieria');

  Route::resource('equipamiento', 'EquipamientoController')->middleware('role:administrador|ingenieria');

  Route::get('listado_ip', 'EquipamientoController@listado_ip')->middleware('role:administrador|ingenieria');

  Route::get('select_puesto','EquipamientoController@select_puesto')->name('select_puesto');

  Route::post('/store_relacion','EquipamientoController@store_relacion')->middleware('role:administrador');

  Route::get('destroy_relacion/{relacion}', ['uses' => 'EquipamientoController@destroy_relacion'])->middleware('role:administrador');

  Route::get('select_tipo_equipamiento', 'EquipamientoController@select_tipo_equipamiento')->name('select_tipo_equipamiento');

  Route::get('select_ips', 'EquipamientoController@select_ips')->name('select_ips');

  Route::get('modal_editar_equipamiento/{id}','EquipamientoController@modal_editar_equipamiento')->name('modal_editar_equipamiento')->middleware('role:administrador');
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

Route::group(['middleware' => ['auth']], function () 
{
  Route::get('planos','PlanoController@planos')->middleware('role:administrador|planos|ingenieria');
  Route::post('store_planos','PlanoController@store_planos')->middleware('role:administrador|planos|ingenieria');
  Route::get('destroy_plano/{plano}', ['uses' => 'PlanoController@destroy_planos'])->middleware('role:administrador|ingenieria');
  Route::post('update_planos','PlanoController@update_planos')->middleware('role:administrador|ingenieria|planos')->name('update_planos');
});

  //****************PROYECTOS**********************
Route::group(['middleware' => ['auth']], function ()  
{
  Route::get('proyectos','ProyectoController@proyectos')->middleware('role:administrador|ingenieria|proyectos');
  Route::post('store_proyectos','ProyectoController@store_proyectos')->middleware('role:administrador|ingenieria');
  Route::get('destroy_proyecto/{proyecto}', ['uses' => 'ProyectoController@destroy_proyecto'])->middleware('role:administrador|ingenieria')->name('destroy_proyecto');
  Route::post('update_proyectos','ProyectoController@update_proyectos')->middleware('role:administrador|ingenieria')->name('update_proyectos');
});

  //****************POLITICAS**********************
  Route::get('politicas','PoliticaController@index');
  Route::post('store_politica','PoliticaController@store_politica')->name('agregar-politica');
  Route::get('destroy_politica/{politica}', ['uses' => 'PoliticaController@destroy_politica']);
  Route::post('update_politica','PoliticaController@update_politica')->name('update_politicas');


//******************QAD-Controller**********************
Route::group(['middleware' => ['auth']], function () 
{
  Route::get('qad','QADController@planos')->middleware('role:administrador|ingenieria|planos');
  Route::get('qad','QADController@index')->middleware('role:administrador|ingenieria|planos');
  Route::get('ot','QADController@ot'); 
  Route::get('oc','QADController@oc');
});

//***************Evento-Calendario-reserva*********************
Route::get('Calendario/event','CalendarioController@index');
Route::get('Calendario/event/{mes}','CalendarioController@index_month');

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

 Route::resource('eventos','EventosController');

 Route::get('/eventos','EventosController@index');

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
 Route::get('powerbis','HomeController@powerbis');

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
  Route::post('store_solicitud','SolicitudController@store_solicitud')->name('agregar-solicitud');
  Route::get('destroy_solicitud/{solicitud}', ['uses' => 'SolicitudController@destroy_solicitud'])->middleware('role:administrador|mantenimiento');
  Route::post('update_solicitud','SolicitudController@update_solicitud')->middleware('role:administrador|mantenimiento')->name('update_solicitudes');
  Route::get('show_solicitud/{solicitud}', ['uses' => 'SolicitudController@show_solicitud'])->name('show_solicitud');

  Route::get('select_tipo_solicitud', 'SolicitudController@select_tipo_solicitud')->name('select_tipo_solicitud');
  Route::get('select_area_localizacion', 'SolicitudController@select_area_localizacion')->name('select_area_localizacion');
  Route::get('select_equipo', 'SolicitudController@select_equipo')->name('select_equipo');
  Route::get('select_falla', 'SolicitudController@select_falla')->name('select_falla');
});

Route::group(['middleware' => ['auth']], function () 
{
  Route::resource('equipos_mant','Equipo_mantController')->middleware('role:administrador|mantenimiento');
  Route::post('store_equipo_mant','Equipo_mantController@store_equipo_mant')->name('agregar-equipo_mant');
  Route::get('select_tipo_equipo', 'Equipo_mantController@select_tipo_equipo')->name('select_tipo_equipo');
  Route::get('select_area_localizacion', 'Equipo_mantController@select_area_localizacion')->name('select_area_localizacion');

});