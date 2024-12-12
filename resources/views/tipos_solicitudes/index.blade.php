@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<div class="container-fluid" id="tipos-sol-container">
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

  <!-- barra para buscar equipos -->
  <button class="btn btn-info" onclick='fnOpenModalStore()' data-toggle="modal" data-target="#agregar_tipo_solicitud"
    id="tipos-sol-btn">Agregar tipo de solicitud</button>
  <!-- tabla de datos -->
  <div>
    <table>
      <thead>
        <th class="text-center">ID</th>
        <th class="text-center">Nombre</th>
        <th class="text-center">Acciones</th>
      </thead>
      <tbody>
        @foreach($tipos_solicitudes as $tipo_solicitud)
      <tr class="text-center">
        <td width="80">{{$tipo_solicitud->id}}</td>
        <td>{{$tipo_solicitud->nombre}}</td>
        <td width="90"><button onclick='fnOpenModalUpdate("{{$tipo_solicitud->id}}")' title="Editar"
          data-nombre="{{$tipo_solicitud->nombre}}" id="icono"><img src="{{ asset('storage/cursos/editar.png') }}"
          alt="Editar" id="img-icono"></button></td>
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
              <!-- Datos -->
            </div>
            <div id="modalfooter" class="modal-footer">
              <!-- Footer -->
            </div>
          </form>
        </div>
      </div>
    </div>
    {{ $tipos_solicitudes->appends($_GET)->links() }}
  </div>
</div>

<script>
  //Duracion de alerta (agregado, elimnado, editado)
  $("tipo_solicitud").ready(function () {
    setTimeout(function () {
      $("div.alert").fadeOut();
    }, 5000); // 5 secs
  });
</script>

<script>
  var ruta_create = '{{ route('store_tipo_solicitud') }}';
  var ruta_update = '{{ route('update_tipo_solicitud') }}';
  var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cerrar</button>');
  var saveButton = $('<button type="submit" class="btn btn-info" id="asignar-btn">Guardar</button>');
  //modal store
  function fnOpenModalStore() {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    var url = window.location.origin + "/show_store_tipo_solicitud/";
    $.get(url, function (data) {
      // Borrar contenido anterior
      $("#modalshow").empty();

      // Establecer el contenido del modal
      $("#modalshow").html(data);

      // Borrar contenido anterior
      $("#modalfooter").empty();

      // Agregar el botón "Cerrar y Guardar" al footer
      $("#modalfooter").append(closeButton);
      $("#modalfooter").append(saveButton);

      // Cambiar la acción del formulario
      $('#myForm').attr('action', ruta_create);

      // Mostrar el modal
      myModal.show();

      // Cambiar el tamaño del modal a "modal-lg"
      var modalDialog = myModal._element.querySelector('.modal-dialog');
      modalDialog.classList.remove('modal-sm');
      modalDialog.classList.remove('modal-lg');

      //para cerrar modales
      closeButton.on('click', function () {
        myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
      });
    });
  }
  //modal update
  function fnOpenModalUpdate(id) {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_update_tipo_solicitud/" + id,
      type: 'GET',
      success: function (data) {
        // Borrar contenido anterior
        $("#modalshow").empty();
        // Establecer el contenido del modal
        $("#modalshow").html(data);

        // Borrar contenido anterior
        $("#modalfooter").empty();

        // Agregar el botón "Cerrar" al footer
        $("#modalfooter").append(closeButton);
        $("#modalfooter").append(saveButton);

        //Cambiar la acción del formulario
        $('#myForm').attr('action', ruta_update);

        // Mostrar el modal
        myModal.show();

        // Cambiar el tamaño del modal a "modal-lg"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.remove('modal-lg');

        //para cerrar modales
        closeButton.on('click', function () {
          myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
        });
      },
    });
  }
</script>