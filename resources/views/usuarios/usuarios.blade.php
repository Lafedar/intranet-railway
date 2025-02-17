@extends('layouts.app')

@push('styles')

<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

@section('content')
<div id="usuarios-container">
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

  <div class="content">
    <div class="row" style="justify-content: center">
      <div id="alert" class="alert alert-success col-md-10 text-center" style="display: none"></div>
    </div>
  </div>

  <div id="usuarios-btn">
    

    <button class="btn btn-info" data-toggle="modal" data-target="#agregar_rol" id="btn-agregar">Nuevo rol</button>

    <button class="btn btn-info" data-toggle="modal" data-target="#agregar_permiso" id="btn-agregar">Nuevo permiso</button>
  </div>
  <div>
    <div class="form-group">
      <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar">
    </div>
  </div>

  <div>
    <table id="test" class="table table-striped table-bordered table-condensed" role="grid" cellspacing="0"
      cellpadding="2" border="10">
      <thead>
        
        <th class="text-center">Nombre</th>
        <th class="text-center">Correo</th>
        <th class="text-center">Rol</th>
        <th class="text-center">Activo</th>
        <th class="text-center">Acciones</th>
      </thead>

      <tbody>
        @if(count($usuarios))
      @foreach($usuarios as $usuario) 
      <tr>
      
      <td align="center">{{$usuario->nombre_usuario}}</td>
      <td align="center">{{$usuario->email_usuario}}</td>
      <td align="center">
      @foreach($roles as $rol)
      @if($rol->id_usuario == $usuario->id)
      {{$rol->nombre_rol}}&nbsp
    @endif
    @endforeach
      </td>
      <td>
    @if($usuario->activo == 1)
        Si
    @else
        No
    @endif
</td>

      <td align="center" width="240">
      <form action="{{route('destroy_usuario', $usuario->id)}}" method="put">

        <a href=# data-toggle="modal" data-target="#asignar_rol" data-id="{{$usuario->id}}"
        data-nombre="{{$usuario->nombre_usuario}}" class="btn btn-info btn-sm" type="submit"> Asignar Rol</a>
        <a href=# data-toggle="modal" data-target="#revocar_rol" data-id="{{$usuario->id}}"
        data-nombre="{{$usuario->nombre_usuario}}" class="btn btn-warning btn-sm" type="submit"> Revocar Rol</a>
        <button type="submit" class="btn btn-danger btn-sm btn-borrar" data-tooltip="Borrar"> X</button>
      </form>
      </td>
      </tr>
    @endforeach
    @endif
      </tbody>
    </table>
  </div>

  @include('usuarios.modal_asignar')

  @include('usuarios.modal_revocar')
  @include('permisos.create_permiso')
  @include('roles.create_rol')
  @include('usuarios.create_usuario')
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  $("document").ready(function () {
    setTimeout(function () {
      $("div.alert").fadeOut();
    }, 5000); // 5 secs

  });
</script>

<script>
  $(document).ready(function () {
    $("#search").keyup(function () {
      _this = this;
      $.each($("#test tbody tr"), function () {
        if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
          $(this).hide();
        else
          $(this).show();
      });
    });
  });
</script>

<script>
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
</script>

<script>
  $('#asignar_rol').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var nombre = button.data('nombre')
    var modal = $(this)

    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #nombre').val(nombre);

    $.get('select_roles/' + id, function (data) {
      var html_select = '<option value="">Seleccione rol </option>'
      for (var i = 0; i < data.length; i++)
        html_select += '<option value ="' + data[i].id + '">' + data[i].name + '</option>';
      $('#select_rol').html(html_select);
    });

  });
</script>

<script>
  $('#revocar_rol').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var nombre = button.data('nombre')
    var modal = $(this)

    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #nombre').val(nombre);

    $.get('select_revocar_roles/' + id, function (data) {
      var html_select = '<option value="">Seleccione rol </option>'
      for (var i = 0; i < data.length; i++)
        html_select += '<option value ="' + data[i].id + '">' + data[i].name + '</option>';
      $('#select_revocar_rol').html(html_select);
    });

  });
</script>

<script>
  $('#agregar_usuario').on('show.bs.modal', function (event) {
    $.get('select_personas/', function (data) {
      var html_select = '<option value="">Seleccione </option>'
      var html_select2 = '<option value="">Seleccione </option>'
      for (var i = 0; i < data.length; i++) {
        html_select += '<option value ="' + data[i].id_p + '"selected">' + data[i].nombre_p + ' ' + data[i].apellido + '</option>';
        html_select2 += '<option value ="' + data[i].id_p + '"selected">' + data[i].correo + '</option>';
      }
      $("#nombre_p").on("change", () => {
        $("#correo").val($("#nombre_p").val());
      });
      $("#correo").on("change", () => {
        $("#nombre_p").val($("#correo").val());
      });
      $('#nombre_p').html(html_select);
      $('#correo').html(html_select2);
    });
  });
</script>
@endpush
