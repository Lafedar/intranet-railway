@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid">
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

  <div id="permisos-nav">
    <a href="#" class="btn btn-info" data-toggle="modal" data-target="#agregar_permiso" type="submit"
      id="permisos-crear">Nuevo permiso</a>
    <h1>
      <div>
        <form method="GET" class="form-inline" action="{{ route('permisos.index') }}">
          <div class="form-group mr-2">
            <label for="search" style="font-size: 20px; margin-right: -5px;">Empleado:</label>
            <input type="text" class="form-control mx-2" style="width:60%" id="search" name="empleado"
              placeholder="Buscar">
          </div>
          <div class="form-group mr-2">
            <label for="search" style="font-size: 20px; margin-right: 2px">Motivo:</label>
            <select class="form-control" name="motivo" id="motivo">
              <option value="0">Sin motivo</option>
              @foreach($tipo_permisos as $tipo_permiso)
          <option value="{{ $tipo_permiso->id_tip }}">{{ $tipo_permiso->desc }}</option>
        @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-default" id="permisos-buscar">Buscar</button>


        </form>

    </h1>
  </div>
  <div>
    <table id="permisos-table">
      <thead>
        <th class="text-center">Empleado</th>
        <th class="text-center">Area</th>
        <th class="text-center">Fecha solicitud</th>
        <th class="text-center">Fecha desde</th>
        <th class="text-center">Fecha hasta</th>
        <th class="text-center">Horario</th>
        <th class="text-center">Motivo</th>
        <th class="text-center">Acciones</th>
      </thead>
      <tbody>
        @if(count($permisos))
      @foreach($permisos as $permiso) 
      <tr>
      <td> {{$permiso->nombre_autorizado . ' ' . $permiso->apellido_autorizado}}</td>
      <td> {{$permiso->area}}</td>
      <td class="text-center"> {!! \Carbon\Carbon::parse($permiso->fecha_permiso)->format("d-m-Y") !!}</td>
      <td class="text-center">{!! \Carbon\Carbon::parse($permiso->fecha_desde)->format("d-m-Y") !!}</td>
      @if($permiso->fecha_hasta != null)
      <td class="text-center">{!! \Carbon\Carbon::parse($permiso->fecha_hasta)->format("d-m-Y") !!}</td>
    @else
      <td></td>
    @endif
      <td class="text-center" width="100"> {{$permiso->hora_desde . ' a ' . $permiso->hora_hasta}}</td>
      <td class="text-center">{{$permiso->motivo}}</td>
      <td align="center" width="95">
      <form action="{{route('destroy_permiso', $permiso->id)}}" method="put">
        <a href="#" data-fecha_soli="{!! \Carbon\Carbon::parse($permiso->fecha_permiso)->format('d-m-Y') !!}"
        data-fecha_desde="{!! \Carbon\Carbon::parse($permiso->fecha_desde)->format('d-m-Y') !!}"
        data-fecha_hasta="{!! \Carbon\Carbon::parse($permiso->fecha_hasta)->format('d-m-Y') !!}"
        data-horario="{{'de ' . $permiso->hora_desde . ' a ' . $permiso->hora_hasta}}"
        data-motivo="{{$permiso->motivo}}" data-descripcion="{{$permiso->descripcion}}"
        data-solicitante="{{$permiso->nombre_autorizado . ' ' . $permiso->apellido_autorizado}}"
        data-area="{{$permiso->area}}"
        data-autorizante="{{$permiso->nombre_autorizante . ' ' . $permiso->apellido_autorizante}}"
        data-toggle="modal" data-target="#ver" title="Ver"><img src="{{ asset('storage/cursos/ver.png') }}"
        alt="Editar" id="img-icono">
        </a>
        <button type="submit" class="btn btn-danger btn-sm btn-borrar" data-tooltip="Borrar" id="icono"
        title="Eliminar">
        <img src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar" id="img-icono">
        </button>

      </form>
      </td>
      </tr>
    @endforeach
    @endif
      </tbody>
    </table>
    {{ $permisos->links('pagination::bootstrap-4') }} <!--paginacion-->
  </div>

</div>

@include('permisos.show') 
@include('permisos.create')   


<script>
  function fnSaveSolicitud() {
    var form = document.getElementById('myForm');
    if (form.checkValidity()) {
      $('#saveButton').prop('disabled', true);
      $('#myForm').submit();
    } else {
      console.log('El formulario no es válido. Completar los campos requeridos antes de enviar.');
    }
  }

  $('#ver').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var fecha_soli = button.data('fecha_soli')
    var fecha_desde = button.data('fecha_desde')
    var fecha_hasta = button.data('fecha_hasta')
    var horario = button.data('horario')
    var motivo = button.data('motivo')
    var descripcion = button.data('descripcion')
    var solicitante = button.data('solicitante')
    var autorizante = button.data('autorizante')
    var area = button.data('area')
    var modal = $(this)

    modal.find('.modal-body #fecha_soli').val(fecha_soli);
    modal.find('.modal-body #fecha_desde').val(fecha_desde);
    modal.find('.modal-body #fecha_hasta').val(fecha_hasta);
    modal.find('.modal-body #horario').val(horario);
    modal.find('.modal-body #motivo').val(motivo);
    modal.find('.modal-body #descripcion').val(descripcion);
    modal.find('.modal-body #solicitante').val(solicitante);
    modal.find('.modal-body #autorizante').val(autorizante);
    modal.find('.modal-body #area').val(area);
  })

  $(document).ready(function () {
    $('#alert').hide();
    $('.btn-borrar').click(function (e) {
      e.preventDefault();
      if (!confirm("¿Está seguro de eliminar?")) {
        return false;
      }
      var row = $(this).parents('tr');
      var form = $(this).parents('form');
      var url = form.attr('action');

      $.get(url, form.serialize(), function (result) {
        row.fadeOut();
        $('#alert').show();
        $('#alert').html(result.message)
        setTimeout(function () { $('#alert').fadeOut(); }, 5000);
      }).fail(function () {
        $('#alert').show();
        $('#alert').html("Algo salió mal");
      });
    });
  });

  $("document").ready(function () {
    setTimeout(function () {
      $("div.alert").fadeOut();
    }, 3000); // 5 secs

  });

</script>
<script>
  $('#agregar_permiso').on('show.bs.modal', function (event) {

    $.get('select_autorizado/', function (data) {
      var html_select = '<option value="">Seleccione </option>'
      for (var i = 0; i < data.length; i++) {
        html_select += '<option value ="' + data[i].id_p + '">' + data[i].apellido + ' ' + data[i].nombre_p + '</option>';
      }
      $('#select').html(html_select);
    });

    $.get('select_tipo_permiso/', function (data) {
      var html_select = '<option value=""> Seleccione </option>'
      for (var i = 0; i < data.length; i++) {
        html_select += '<option value ="' + data[i].id_tip + '">' + data[i].desc + '</option>';
      }
      $('#select_motivo').html(html_select);
    });


  })



</script>