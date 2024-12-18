@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>
<script language="JavaScript" src="{{ URL::asset('/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-popper.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-bootstrap.min.js') }}"></script>

<div class="container-fluid" id="localizaciones-container">
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

  <button class="btn btn-info" onclick='fnOpenModalStore()' data-toggle="modal" data-target="#show2"
  id="btn-agregar">Agregar Localización</button>

  <!-- tabla de datos -->
  <div id="localizaciones-table">
    <table>
      <thead>
        <th class="text-center">ID</th>
        <th class="text-center">Área</th>
        <th class="text-center">Nombre</th>
        <th class="text-center">Interno</th>
        <th class="text-center">Acciones</th>
      </thead>
      <tbody>
        @foreach($localizaciones as $localizacion)
      <tr class="text-center">
        <td width="80">{{ $localizacion->id }}</td>
        <td>{{ $localizacion->nombre_a }}</td>
        <td>{{ $localizacion->nombre }}</td>
        <td>{{ $localizacion->interno }}</td>
        <td width="90">
        <button onclick='fnOpenModalUpdate("{{ $localizacion->id }}")' title="Editar"
          data-nombre="{{ $localizacion->nombre }}" data-interno="{{ $localizacion->interno }}" id="icono"
          title="Editar"><img src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono"></button>
        </td>
      </tr>
    @endforeach
      </tbody>
    </table>

    <div class="modal fade" id="show2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="myForm" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            <div id="modalshow" class="modal-body">
              <!-- Aquí se cargará el contenido del modal -->
            </div>
            <div id="modalfooter" class="modal-footer">
              <!-- Footer con botones -->
            </div>
          </form>
        </div>
      </div>
    </div>

    {{ $localizaciones->links('pagination::bootstrap-4') }} <!--paginacion-->
  </div>
</div>

<script>
  // Duración de alerta (agregado, eliminado, editado)
  $("localizacion").ready(function () {
    setTimeout(function () {
      $("div.alert").fadeOut();
    }, 5000); // 5 segundos
  });

  var ruta_create = '{{ route('store_localizacion') }}';
  var ruta_update = '{{ route('update_localizacion') }}';
  var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cerrar</button>');
  var saveButton = $('<button type="submit" class="btn btn-info" id="asignar-btn">Guardar</button>');

  // Modal para crear una nueva localización
  function fnOpenModalStore() {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    var url = window.location.origin + "/show_store_localizacion/";

    $.get(url, function (data) {
      // Borrar contenido anterior
      $("#modalshow").empty();

      // Establecer el contenido del modal
      $("#modalshow").html(data);

      // Borrar contenido anterior en el pie del modal
      $("#modalfooter").empty();

      // Agregar el botón "Cerrar" y "Guardar" al footer
      $("#modalfooter").append(closeButton);
      $("#modalfooter").append(saveButton);

      // Cambiar la acción del formulario a "store"
      $('#myForm').attr('action', ruta_create);

      // Mostrar el modal
      myModal.show();

      // Cambiar el tamaño del modal a "modal-lg"
      var modalDialog = myModal._element.querySelector('.modal-dialog');
      modalDialog.classList.remove('modal-sm');
      modalDialog.classList.add('modal-lg');

      //para cerrar modales
      closeButton.on('click', function () {
        myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
      });
    });
  }

  // Modal para actualizar una localización existente
  function fnOpenModalUpdate(id) {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));

    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_update_localizacion/" + id,
      type: 'GET',
      success: function (data) {
        // Borrar contenido anterior
        $("#modalshow").empty();

        // Establecer el contenido del modal
        $("#modalshow").html(data);

        // Borrar contenido anterior en el pie del modal
        $("#modalfooter").empty();

        // Agregar los botones "Cerrar" y "Guardar"
        $("#modalfooter").append(closeButton);
        $("#modalfooter").append(saveButton);

        // Cambiar la acción del formulario a "update"
        $('#myForm').attr('action', ruta_update);

        // Mostrar el modal
        myModal.show();

        // Cambiar el tamaño del modal a "modal-lg"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.add('modal-lg');

        //para cerrar modales
        closeButton.on('click', function () {
          myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
        });
      },
    });
  }

  // Al abrir el modal, cargamos las áreas dinámicamente
  $('#show2').on('show.bs.modal', function (event) {
    $.get('select_area/', function (data) {
      var html_select = '<option value="">Seleccione </option>';

      // Llenar el select con las áreas
      for (var i = 0; i < data.length; i++) {
        html_select += '<option value ="' + data[i].id_a + '">' + data[i].nombre_a + '</option>';
      }

      // Asignar las opciones al select
      $('#area').html(html_select);
    });
  });
</script>