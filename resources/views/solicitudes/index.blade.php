@extends('solicitudes.layouts.layout')
@section('content')

<!-- alertas -->

<div class="content">
  <div class="row" style="justify-content: center">
    <div id="alert" class="alert alert-success col-md-10 text-center" style="display: none"></div>
  </div>
</div>

@if(Session::has('message'))
  <div class="container" id="div.alert">
    <div class="row">
      <div class="col-1"></div>
      <div class="alert {{Session::get('alert-class')}} col-10 text-center" role="alert">
        {{Session::get('message')}}
      </div>
    </div>
  </div>
@endif

<!-- barra para buscar solicitudes -->

<!-- tabla de datos -->

<div class="col-md-12">             
  <table class="table table-striped table-bordered ">
    <thead>
      <th class="text-center">ID</th>
      <th class="text-center">Titulo</th>
      <th class="text-center">Tipo de solicitud</th>
      <th class="text-center">Equipo</th>
      <th class="text-center">Estado</th>     
      <th class="text-center">Tipo de falla</th>    
      @can('ver_solicitante')  
        <th class="text-center">Solicitante</th>
      @endcan
      @can('ver_encargado')
        <th class="text-center">Encargado</th>  
      @endcan
      <th class="text-center">Acciones</th>        
    </thead>
    <tbody>
        @foreach($solicitudes as $solicitud)
            <tr>
              <td width="60">{{sprintf('%05d',$solicitud->id)}}</td>
              <td width="500">{{$solicitud->titulo}}</td>
              <td width="150">{{$solicitud->tipo_solicitud}}</td>
              <td width="107">{{$solicitud->id_equipo}}</td>
              <td >{{$solicitud->estado}}</td>
              <td >{{$solicitud->falla}}</td>
              @can('ver_solicitante')
                <td >{{$solicitud->nombre_solicitante}}</td>
              @endcan
              @can('ver_encargado')
                <td >{{$solicitud->nombre_encargado}}</td>
              @endcan
              <td class="text-center" width="206">
                <div>
                  
                    <!-- Boton de ver solitud en detalle -->
                    <button class="btn btn-info btn-sm" data-id="{{$solicitud->id}}" data-titulo="{{$solicitud->titulo}}" 
                    data-tipo_solicitud="{{$solicitud->tipo_solicitud}}" data-id_equipo="{{$solicitud->id_equipo}}" 
                    data-falla="{{$solicitud->falla}}" data-nombre_solicitante="{{$solicitud->nombre_solicitante}}" 
                    data-nombre_encargado="{{$solicitud->nombre_encargado}}"
                    data-toggle="modal" data-target="#mostrar">Detalles</button>
                    <!-- Boton de editar y eliminar -->

                  @can('editar-solicitud')
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editar">Actualizar</button>
                  @endcan
                  @can('eliminar-solicitud')
                    <a href="{{url('destroy_solicitud', $solicitud->id)}}" class="btn btn-danger btn-sm" title="Borrar" onclick="return confirm ('Está seguro que desea eliminar esta solicitud?')"
                    data-position="top" data-delay="50" data-tooltip="Borrar">X</a>
                  @endcan
                </div>
              </td>
            </tr>
        @endforeach

    </tbody>       
  </table>   
  
  @include('solicitudes.edit')
  @include('solicitudes.show')

  {{ $solicitudes->appends($_GET)->links() }}
</div>
<!-- Duracion de alerta (agregado, elimnado, editado) -->
<script> 
  $("solicitud").ready(function()
  {
    setTimeout(function()
    {
      $("div.alert").fadeOut();
    }, 5000 ); // 5 secs

  });
</script> 

<script>
  $('#mostrar').on('show.bs.modal', function (event) 
  {
    var button = $(event.relatedTarget) 
    var id = button.data('id')
    var titulo = button.data('titulo')
    var tipo_solicitud = button.data('tipo_solicitud')
    var id_equipo = button.data('id_equipo')
    var falla = button.data('falla')
    var nombre_solicitante = button.data('nombre_solicitante')
    var nombre_encargado = button.data('nombre_encargado')
    var modal = $(this)

    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #titulo').val(titulo);
    modal.find('.modal-body #tipo_solicitud').val(tipo_solicitud);
    modal.find('.modal-body #id_equipo').val(id_equipo);
    modal.find('.modal-body #falla').val(falla);
    modal.find('.modal-body #nombre_solicitante').val(nombre_solicitante);
    modal.find('.modal-body #nombre_encargado').val(nombre_encargado);
  })
</script>

<script>
  $('#editar').on('show.bs.modal', function (event) 
  {
    var button = $(event.relatedTarget) 
    var id = button.data('id')
    var titulo = button.data('titulo')
    var fecha = button.data('fecha') 
    var obs = button.data('obs')
    var frecuencia = button.data('frecuencia')
    var pbix = button.data('pbix')
    var modal = $(this)

    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #titulo').val(titulo);
    modal.find('.modal-body #fecha').val(fecha);
    modal.find('.modal-body #obs').val(obs);
    modal.find('.modal-body #frecuencia').val(frecuencia);

  })
</script>

@stop