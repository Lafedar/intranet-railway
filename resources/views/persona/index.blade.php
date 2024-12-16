@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>



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
<div class="container-fluid">
  <div id="recepcion-nav">
    <div id="recepcion-botones">
      <a class="btn btn-secondary" href="/internos">Internos</a>&nbsp
      <button class="btn btn-info" data-toggle="modal" data-target="#agregar"> Agregar</button>
    </div>

    <h1>
      <div class="form-inline pull-right">
        <form method="GET">
          <div class="form-group">
            <div class="form-group">
              <h6><b>Nombre:</b></h6>
              <input type="text" name="nombre" class="form-control" id="nombre" value="{{$nombre}}">
            </div>
            &nbsp
            <div class="form-group">
              <h6><b>Empresa:</b></h6>
              <input type="text" name="empresa" class="form-control" id="empresa" value="{{$empresa}}">
            </div>
            &nbsp
            <button type="submit" class="btn btn-default"> Buscar</button>
        </form>
      </div>
    </h1>
  </div>

  <div id="recepcion-tabla">
    <table class="table table-striped table-bordered">
      <thead>
        <th class="text-center">Nombre</th>
        <th class="text-center">Apellido</th>
        <th class="text-center">Empresa</th>
        <th class="text-center">Telefono</th>
        <th class="text-center">Celular</th>
        <th class="text-center">Acciones</th>
      </thead>

      <tbody>
        @if(count($personas))
          @foreach($personas as $persona)
        <tr>
        <td>{{$persona->nombre}}</td>
        <td>{{$persona->apellido}}</td>
        <td>{{$persona->empresa}}</td>
        <td>{{$persona->telefono}}</td>
        <td>{{$persona->celular}}</td>
        <td width="153">
        <form action="{{route('destroy_contacto', $persona->id)}}" method="put">
          <a href="#" data-nombre="{{$persona->nombre . ' ' . $persona->apellido}}"
          data-empresa="{{$persona->empresa}}" data-direccion="{{$persona->direccion}}"
          data-celular="{{$persona->celular}}" data-telefono="{{$persona->telefono}}"
          data-correo="{{$persona->correo}}" data-toggle="modal" data-target="#ver" title="Ver"><img
          src="{{ asset('storage/cursos/ver.png') }}" alt="Ver" id="img-icono"></a>


          <a href="#" data-id="{{$persona->id}}" data-nombre="{{$persona->nombre}}"
          data-apellido="{{$persona->apellido}}" data-direccion="{{$persona->direccion}}"
          data-empresa="{{$persona->empresa}}" data-interno="{{$persona->interno}}"
          data-celular="{{$persona->celular}}" data-telefono="{{$persona->telefono}}"
          data-correo="{{$persona->correo}}" data-toggle="modal" data-target="#editar" title="Editar"><img
          src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono"></a>


          <button type="submit" class="btn-borrar" data-tooltip="Borrar" id="icono" title="Eliminar"><img
          src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar" id="img-icono"></button>
        </form>
      </div>
      </td>
      </tr>
    @endforeach
      @endif
  </tbody>
  </table>

  @include('persona.show')    

  @include('persona.edit')
  @include('persona.create')


</div>
{{ $personas->links('pagination::bootstrap-4') }} <!--paginacion-->
</div>



<script>
  $("document").ready(function () {
    setTimeout(function () {
      $("div.alert").fadeOut();
    }, 3000); // 5 secs

  });
</script>

<script>
  $('#ver').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget)
    var nombre = button.data('nombre')
    var empresa = button.data('empresa')
    var direccion = button.data('direccion')
    var telefono = button.data('telefono')
    var celular = button.data('celular')
    var correo = button.data('correo')
    var modal = $(this)

    modal.find('.modal-body #nombre').val(nombre);
    modal.find('.modal-body #empresa').val(empresa);
    modal.find('.modal-body #direccion').val(direccion);
    modal.find('.modal-body #telefono').val(telefono);
    modal.find('.modal-body #celular').val(celular);
    modal.find('.modal-body #correo').val(correo);
  })
</script>


<script>
  $('#editar').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget)
    var id = button.data('id')
    var nombre = button.data('nombre')
    var apellido = button.data('apellido')
    var empresa = button.data('empresa')
    var direccion = button.data('direccion')
    var telefono = button.data('telefono')
    var interno = button.data('interno')
    var celular = button.data('celular')
    var correo = button.data('correo')
    var modal = $(this)

    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #nombre').val(nombre);
    modal.find('.modal-body #apellido').val(apellido);
    modal.find('.modal-body #empresa').val(empresa);
    modal.find('.modal-body #direccion').val(direccion);
    modal.find('.modal-body #telefono').val(telefono);
    modal.find('.modal-body #interno').val(interno);
    modal.find('.modal-body #celular').val(celular);
    modal.find('.modal-body #correo').val(correo);
  })
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
        setTimeout(function () { $('#alert').fadeOut(); }, 3000);
      }).fail(function () {
        $('#alert').show();
        $('#alert').html("Algo salió mal");
      });
    });
  });
</script>