@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>

<div id="listado-container" class="container-fluid">
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

  <div>
    <div class="form-group">
      <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar">
    </div>
  </div>

  <div>
    <table id="test" role="grid" cellspacing="0" cellpadding="2" border="10">
      <thead>
        <th class="text-center">Apellido y nombre</th>
        <th class="text-center">DNI</th>
        <th class="text-center">Teléfono</th>
        <th class="text-center">Empresa</th>
        <th class="text-center">Acciones</th>
      </thead>

      <tbody>
        @if(count($listado))
      @foreach($listado as $lista) 
      <tr>
      <td> {{$lista->apellido_ext . ' ' . $lista->nombre_ext}}</td>
      <td align="center">{{$lista->dni}}</td>
      <td align="center">{{$lista->telefono_ext}}</td>
      <td align="center">{{$lista->razon_social}}</td>
      <td align="center" width="200">
      <form action="{{route('destroy_externo', $lista->dni)}}" method="put">

        <a href="#" data-toggle="modal" data-dni="{{$lista->dni}}" data-nombre="{{$lista->nombre_ext}}"
        data-apellido="{{$lista->apellido_ext}}" data-telefono="{{$lista->telefono_ext}}"
        data-target="#editar_externo" type="submit" title="Editar"><img
        src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono"></a>

        <a href="#" data-toggle="modal" data-dni="{{$lista->dni}}" data-target="#foto_externo" type="submit"
        title="Ver Foto"><img src="{{ asset('storage/cursos/foto.png') }}" alt="Ver Foto" id="img-icono"></a>

        <button type="submit" data-tooltip="Borrar" class="btn-borrar" id="icono" title="Eliminar"><img
        src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar" id="img-icono"></button>
      </form>
      </td>
      </tr>
      </tr>
    @endforeach
    @endif
      </tbody>
    </table>
  </div>

  @include('visita.editar_externo')
  @include('visita.modal_foto_externo')
</div>



<script>
  $('#editar_externo').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget)
    var dni = button.data('dni')
    var nombre = button.data('nombre')
    var apellido = button.data('apellido')
    var telefono = button.data('telefono')
    var modal = $(this)

    modal.find('.modal-body #dni').val(dni);
    modal.find('.modal-body #nombre_ext').val(nombre);
    modal.find('.modal-body #apellido_ext').val(apellido);
    modal.find('.modal-body #telefono_ext').val(telefono);
  })
</script>

<script>
  $('#foto_externo').on('show.bs.modal', function (event) {
    document.getElementById("ver_foto").src = " ";
    var button = $(event.relatedTarget)
    var dni = button.data('dni')
    var modal = $(this)

    $.get('fotoExterno/' + dni, function (data) {
      var storage = "{{Storage::url(':fotito_reemplaza')}}";
      storage = storage.replace(':fotito_reemplaza', data[0].foto);
      foto = document.getElementById("ver_foto").src = storage;
    });
  })
</script>

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