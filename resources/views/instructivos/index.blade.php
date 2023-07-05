@extends('instructivos.layouts.layout')
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

<!-- tabla de datos -->

<div class="col-md-12">             
  <table class="table table-striped table-bordered ">
    <thead>
      <th class="text-center">ID</th>
      <th class="text-center">Título</th>
      <th class="text-center">Fecha</th>   
      <th class="text-center">Acciones</th>        
    </thead>
    <tbody>
      @if(count($instructivos))
        @foreach($instructivos as $instructivo)
          <tr>
            <td width="60">{{sprintf('%05d',$instructivo->id)}}</td>
            <td width="500">{{$instructivo->titulo}}</td>
            <td class="text-center" width="107">{!! \Carbon\Carbon::parse($instructivo->fecha)->format("d-m-Y") !!}</td>
            <td width="100">
              <div class="text-center">
                <!-- Boton de descargar archivo -->
                @if($instructivo->archivo != null)
                  <a href="{{ Storage::url($instructivo->archivo) }}" class="btn btn-primary btn-sm" title="Descargar Archivo" data-position="top" data-delay="50" data-tooltip="Descargar Archivo" download>Descargar</a>
                @else
                  <a class="btn btn-secondary btn-sm" data-position="top" data-delay="50" download >Descargar</a>
                @endif
                <!-- Boton de editar archivo -->
                @can('editar-instructivo')
                  <button class="btn btn-info btn-sm" data-id="{{$instructivo->id}}" data-titulo="{{$instructivo->titulo}}" data-fecha="{{$instructivo->fecha}}" data-pdf="{{$instructivo->pdf}}" data-toggle="modal" data-target="#editar"> Editar</button>
                @endcan
                <!-- Boton de eliminar archivo -->
                @can('eliminar-instructivo')
                  <a href="{{url('destroy_instructivo', $instructivo->id)}}" class="btn btn-danger btn-sm" title="Borrar" onclick="return confirm ('Está seguro que desea eliminar el archivo?')"data-position="top" data-delay="50" data-tooltip="Borrar">X</a>
                @endcan
              </div>
            </td>
          </tr>
        @endforeach
      @endif  
    </tbody>       
  </table>   
  @include('instructivos.edit')
  {{ $instructivos->appends($_GET)->links() }}
</div>
<!-- Duracion de alerta (agregado, elimnado, editado) -->
<script> 
  $("instructivos").ready(function()
  {
    setTimeout(function()
    {
      $("div.alert").fadeOut();
    }, 5000 ); // 5 secs

  });
</script> 

<script>
  $('#editar').on('show.bs.modal', function (event) 
  {
    var button = $(event.relatedTarget) 
    var id = button.data('id')
    var titulo = button.data('titulo')
    var fecha = button.data('fecha') 
    var pdf = button.data('pdf')
    var modal = $(this)

    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #titulo').val(titulo);
    modal.find('.modal-body #fecha').val(fecha);

    console.log(pdf.length);
    if(pdf.length == 0)
    {
      $("div.elim_pdf").hide()
    }
    else
    {
      $("div.elim_pdf").show()   
    }
  })
</script>

@stop