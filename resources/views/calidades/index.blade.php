@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- alertas -->
<div class="container-fluid" id="calidad-container">
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
  <button class="btn btn-info"  data-toggle="modal" data-target="#agregar" id="btn-agregar"> Agregar</button>
  <div>
    <h1>
      <div class="form-inline pull-right">
        <form method="GET">
          <div class="form-group">
            <div class="form-group">
              <h6>ID:</h6>
              <input type="text" name="id_calidad" class="form-control" id="id_calidad" value="{{$id_calidad}}">
            </div>
            &nbsp
            <div class="form-group">
              <h6>Título:</h6>
              <input type="text" name="titulo_calidad" class="form-control" id="titulo_calidad"
                value="{{$titulo_calidad}}">
            </div>
            &nbsp
            <div class="form-group">
              <h6>Fecha:</h6>
              <input type="date" name="fecha_calidad" class="form-control" id="fecha_calidad"
                value="{{$fecha_calidad}}">
            </div>
            &nbsp
            <label class="form-group">
              <h6>Observación:</h6>
            </label>
            <input type="text" name="obs_calidad" class="form-control" id="obs_calidad" value="{{$obs_calidad}}">
            &nbsp
            <button type="submit" class="btn btn-default" id="asignar-btn"> Buscar</button>
          </div>
        </form>
      </div>
    </h1>
  </div>

  <!-- tabla de datos -->

  <div id="calidad-table">
    <table>
      <thead>
        <th class="text-center">ID</th>
        <th class="text-center">Título</th>
        <th class="text-center">Fecha</th>
        <th class="text-center">Observación</th>
        <th class="text-center">Frecuencia de actualización</th>
        <th class="text-center">Acciones</th>
      </thead>
      <tbody>
        @if(count($calidades))
      @foreach($calidades as $calidad)
      @if($calidad->categoria == 4)
      <tr>
      <td width="60">{{sprintf('%05d', $calidad->id)}}</td>
      <td width="500">{{$calidad->titulo}}</td>
      <td width="107">{!! \Carbon\Carbon::parse($calidad->fecha)->format("d-m-Y") !!}</td>
      <td>{{$calidad->obs}}</td>
      @foreach($frecuencias as $frecuencia)
      @if($calidad->frecuencia == $frecuencia['id'])
      <td width="270" value="{{ $frecuencia['id'] }}">{{$frecuencia['frecuencia']}}</td>
    @endif
    @endforeach
      <td width="110">
      <div>
      <!-- Boton de descargar archivo -->
      @if($calidad->pbix != null)
      <a href="{{ Storage::url($calidad->pbix) }}" title="Descargar Archivo"
      data-position="top" data-delay="50" data-tooltip="Descargar Archivo" download><img src="{{ asset('storage/cursos/descargar.png') }}" alt="Descargar" id="img-icono">
      </a>
    @else
      <a  data-position="top" data-delay="50" download title="Descargar Archivo"><img src="{{ asset('storage/cursos/descargar.png') }}" alt="Descargar" id="img-icono"></a>
    @endif
      </div>
      <h6></h6>
      <!-- Boton de editar y eliminar -->
      <div aling="center">
      @can('editar-calidad')
      <button  data-id="{{$calidad->id}}" data-titulo="{{$calidad->titulo}}"
      data-fecha="{{$calidad->fecha}}" data-obs="{{$calidad->obs}}" data-pbix="{{$calidad->pbix}}"
      data-frecuencia="{{$calidad->frecuencia}}" data-toggle="modal" data-target="#editar" id="icono" title="Editar"><img src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono"></button>
    @endcan
      @can('eliminar-calidad')
      <a href="{{url('destroy_calidad', $calidad->id)}}" class="btn btn-danger btn-sm" title="Eliminar"
      onclick="return confirm ('Está seguro que desea eliminar el calidad?')" data-position="top"
      data-delay="50" data-tooltip="Borrar" id="icono"><img src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar" id="img-icono"></a>
    @endcan
      </div>
      </td>
      </tr>
    @endif
    @endforeach
    @endif
      </tbody>
    </table>

    @include('calidades.edit')
    @include('calidades.create')

    {{ $calidades->appends($_GET)->links() }}
  </div>
</div>

<!-- Duracion de alerta (agregado, elimnado, editado) -->
<script>
  $("calidades").ready(function () {
    setTimeout(function () {
      $("div.alert").fadeOut();
    }, 5000); // 5 secs

  });
</script>

<script>
  $('#editar').on('show.bs.modal', function (event) {
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
    if (pbix.length == 0) {
      $("div.elim_pbix").hide()
    }
    else {
      $("div.elim_pbix").show()
    }
  })
</script>