<!DOCTYPE html>
<html lang="es">

<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">

<script type="text/javascript" src="{{ URL::asset('/js/modal-jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-popper.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-bootstrap.min.js') }}"></script>

<head>
  <meta charset="UTF-8">
  <title>Intranet Lafedar</title>
  <link  rel="icon"   href="img/ico.png" type="image/png" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script language="JavaScript" src="{{ URL::asset('/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="documentos"> <img class="logo" src="{{ URL::to('/img/logo.png') }}" height="40"> </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar1">
      <ul class="navbar-nav ml-auto">
        @if(!Auth::check())
          <a href="{{ url('/login') }}" class="btn btn-info">Iniciar sesión</a>
        @endif
        &nbsp
        @can('agregar-instructivo')
          <button class="btn btn-info" onclick='fnOpenModalStore()' data-toggle="modal" data-target="#agregar"> Agregar</button>
        @endcan
        &nbsp
        <form action="{{ url('/logout') }}" method="POST" >
          {{ csrf_field() }}
          <button type="submit" class="btn btn-danger" style="display:inline;cursor:pointer">Cerrar sesión</button>
        </form>
      </ul>
    </div>
  </nav>
  <p></p>
</head>

<div class="modal fade" id="show2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog estilo" role="document">
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

<script type="text/javascript" src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>
<script>
  var ruta_store = '{{ route('store_instructivo') }}';
  var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button> id="asignar-btn"');
  var saveButton = $('<button type="submit" class="btn btn-info">Guardar</button>');

  //modal store
  function fnOpenModalStore() {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    var url = window.location.origin + "/show_store_instructivo/";
    $.get(url, function(data) {
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
      $('#myForm').attr('action', ruta_store);

      // Construir el select
      $.get('select_tipo_instructivos/', function(data) {
        var html_select = '<option value="">Seleccione</option>';

        for (var i = 0; i < data.length; i++) {
          html_select += '<option value="' + data[i].id + '">' + data[i].nombre + '</option>';
        }

        // Establecer el select completo en el modal
        $('#tipo_instructivo').html(html_select);

        // Mostrar el modal
        myModal.show();

        // Cambiar el tamaño del modal a "modal-lg"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.remove('modal-lg');
      });
    });
  }


</script>
<body>
  @yield('content')
</body>

</html>