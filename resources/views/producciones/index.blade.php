@extends('producciones.layouts.layout')
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

<!-- barra para buscar archivos -->

<div class="col-md-12 ml-auto">
  <h1>
    <div class="form-inline pull-right">
      <form  method="GET">
        <div class="form-group">
          <div class="form-group"><h6>ID:</h6>
            <input type="text" name="id_produccion" class="form-control" id="id_produccion" value="{{$id_produccion}}">
          </div>
          &nbsp
          <div class="form-group"><h6>Título:</h6>
            <input type="text" name="titulo_produccion" class="form-control" id="titulo_produccion" value="{{$titulo_produccion}}" >
          </div>
          &nbsp
          <div class="form-group"><h6>Fecha:</h6>
            <input type="date" name="fecha_produccion" class="form-control" id="fecha_produccion" value="{{$fecha_produccion}}" >
          </div>
          &nbsp
          <label class="form-group"><h6>Observación:</h6></label>
          <input type="text" name="obs_produccion" class="form-control" id="obs_produccion" value="{{$obs_produccion}}">
          &nbsp
          <button type="submit" class="btn btn-default"> Buscar</button>
        </div>
      </form>
    </div>
  </h1>            
</div>

<!-- tabla de datos -->

<div class="col-md-12">             
  <table class="table table-striped table-bordered ">
    <thead>
      <th class="text-center">ID</th>
      <th class="text-center">Título</th>
      <th class="text-center">Fecha</th>   
      <th class="text-center">Observación</th>  
      <th class="text-center">Frecuencia de actualización</th> 
      <th class="text-center">Acciones</th>        
    </thead>
    <tbody>
      @if(count($producciones))
        @foreach($producciones as $produccion)
          @if($produccion->categoria == 6)
            <tr>
              <td width="60">{{sprintf('%05d',$produccion->id)}}</td>
              <td width="500">{{$produccion->titulo}}</td>
              <td width="107">{!! \Carbon\Carbon::parse($produccion->fecha)->format("d-m-Y") !!}</td>
              <td >{{$produccion->obs}}</td>
              @foreach($frecuencias as $frecuencia)
                @if($produccion->frecuencia == $frecuencia['id'])
                  <td width="270" value="{{ $frecuencia['id'] }}">{{$frecuencia['frecuencia']}}</td>
                @endif
              @endforeach
              <td width="110">
                <div>
                  <!-- Boton de descargar archivo -->
                  @if($produccion->pbix != null)
                    <a href="{{ Storage::url($produccion->pbix) }}" class="btn btn-primary btn-sm" title="Descargar Archivo" data-position="top" data-delay="50" data-tooltip="Descargar Archivo" download>Descargar</a>
                  @else
                    <a class="btn btn-secondary btn-sm" data-position="top" data-delay="50" download >Descargar</a>
                  @endif
                </div>
                <h6></h6>
                <!-- Boton de editar y eliminar -->
                <div aling="center">
                  @can('editar-produccion')
                    <button class="btn btn-info btn-sm" data-id="{{$produccion->id}}" data-titulo="{{$produccion->titulo}}" data-fecha="{{$produccion->fecha}}" data-obs="{{$produccion->obs}}" data-pbix="{{$produccion->pbix}}" data-frecuencia="{{$produccion->frecuencia}}" data-toggle="modal" data-target="#editar"> Editar</button>
                  @endcan
                  @can('eliminar-produccion')
                    <a href="{{url('destroy_produccion', $produccion->id)}}" class="btn btn-danger btn-sm" title="Borrar" onclick="return confirm ('Está seguro que desea eliminar el archivo?')"data-position="top" data-delay="50" data-tooltip="Borrar">X</a>
                  @endcan
                </div>
              </td>
            </tr>
          @endif
        @endforeach
      @endif  
    </tbody>       
  </table>   

  @include('producciones.edit')

  {{ $producciones->appends($_GET)->links() }}
</div>
<!-- Duracion de alerta (agregado, elimnado, editado) -->
<script> 
  $("producciones").ready(function()
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
    var obs = button.data('obs')
    var frecuencia = button.data('frecuencia')
    var pbix = button.data('pbix')
    var modal = $(this)

    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #titulo').val(titulo);
    modal.find('.modal-body #fecha').val(fecha);
    modal.find('.modal-body #obs').val(obs);
    modal.find('.modal-body #frecuencia').val(frecuencia);

    console.log(pbix.length);
    if(pbix.length == 0)
    {
      $("div.elim_pbix").hide()
    }
    else
    {
      $("div.elim_pbix").show()   
    }
  })
</script> 

@stop