@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- alertas -->
<div class="container-fluid" id="rrhh-container">
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
              <input type="text" name="id_rrhh" class="form-control" id="id_rrhh" value="{{$id_rrhh}}">
            </div>
            &nbsp
            <div class="form-group">
              <h6>Título:</h6>
              <input type="text" name="titulo_rrhh" class="form-control" id="titulo_rrhh" value="{{$titulo_rrhh}}">
            </div>
            &nbsp
            <div class="form-group">
              <h6>Fecha:</h6>
              <input type="date" name="fecha_rrhh" class="form-control" id="fecha_rrhh" value="{{$fecha_rrhh}}">
            </div>
            &nbsp
            <label class="form-group">
              <h6>Observación:</h6>
            </label>
            <input type="text" name="obs_rrhh" class="form-control" id="obs_rrhh" value="{{$obs_rrhh}}">
            &nbsp
            <button type="submit" class="btn btn-default" id="asignar-btn"> Buscar</button>
          </div>
        </form>
      </div>
    </h1>
  </div>

  <!-- tabla de datos -->

  <div id="rrhh-table">
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
        @if(count($rrhhs))
      @foreach($rrhhs as $rrhh)
      @if($rrhh->categoria == 5)
      <tr>
      <td width="60">{{sprintf('%05d', $rrhh->id)}}</td>
      <td width="500">{{$rrhh->titulo}}</td>
      <td width="107">{!! \Carbon\Carbon::parse($rrhh->fecha)->format("d-m-Y") !!}</td>
      <td>{{$rrhh->obs}}</td>
      @foreach($frecuencias as $frecuencia)
      @if($rrhh->frecuencia == $frecuencia['id'])
      <td width="270" value="{{ $frecuencia['id'] }}">{{$frecuencia['frecuencia']}}</td>
    @endif
    @endforeach
      <td width="110">
      <div>
      <!-- Boton de descargar archivo -->
      @if($rrhh->pbix != null)
      <a href="{{ Storage::url($rrhh->pbix) }}" title="Descargar Archivo"
      data-position="top" data-delay="50" data-tooltip="Descargar Archivo" download> <img src="{{ asset('storage/cursos/descargar.png') }}" alt="Descargar" id="img-icono"></a>
    @else
      <a class="btn btn-secondary btn-sm" data-position="top" data-delay="50" download><img src="{{ asset('storage/cursos/descargar.png') }}" alt="Descargar" id="img-icono"></a>
    @endif
      </div>
      <h6></h6>
      <!-- Boton de editar y eliminar -->
      <div aling="center">
      @can('editar-rrhh')
      <button class="btn btn-info btn-sm" data-id="{{$rrhh->id}}" data-titulo="{{$rrhh->titulo}}"
      data-fecha="{{$rrhh->fecha}}" data-obs="{{$rrhh->obs}}" data-pbix="{{$rrhh->pbix}}"
      data-frecuencia="{{$rrhh->frecuencia}}" data-toggle="modal" data-target="#editar" id="icono" title="Editar"> <img src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono"></button>
    @endcan
      @can('eliminar-rrhh')
      <a href="{{url('destroy_rrhh', $rrhh->id)}}" class="btn btn-danger btn-sm" title="Eliminar"
      onclick="return confirm ('Está seguro que desea eliminar el archivo?')" data-position="top"
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

    @include('rrhhs.edit')
    @include('rrhhs.create')

    {{ $rrhhs->appends($_GET)->links() }}
  </div>
</div>

<!-- Duracion de alerta (agregado, elimnado, editado) -->
<script>
  $("rrhhs").ready(function () {
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