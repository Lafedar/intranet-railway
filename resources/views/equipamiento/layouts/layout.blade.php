<!DOCTYPE html>
<html lang="es">

<script src="https://kit.fontawesome.com/b36ad16a06.js" crossorigin="anonymous"></script>
<link href="{{ asset('css/acciones.css') }}" rel="stylesheet">
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
    <a class="navbar-brand" href="/sistemas"> <img class="logo" src="{{ URL::to('/img/logo.png') }}" height="40"> </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar1">
      <ul class="navbar-nav ml-auto"> 
        @hasrole('administrador')
        <a href="#" class="btn btn-info"  data-toggle="modal" data-target="#agregar_equipamiento" type="submit"> Nuevo equipamiento</a>
        &nbsp
        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#agregar_puesto" type="submit"> Nuevo puesto</a>
        &nbsp
        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#agregar_software" type="submit">Nuevo Software</a>
        &nbsp
        @endhasrole
        <form action="{{ url('/logout') }}" method="POST" >
          {{ csrf_field() }}
          <button type="submit" class="btn btn-danger" style="display:inline;cursor:pointer">
            Cerrar sesión
          </button>
        </form>
      </ul>
    </div>
  </nav>
  <p></p>			
</head>

<script type="text/javascript" src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>

@include('equipamiento.create')
@include('software.create')

<script>
  $('#agregar_equipamiento').on('show.bs.modal', function (event) {
    $.get('select_tipo_equipamiento/',function(data){
      var html_select = '<option value="">Seleccione </option>'
      for(var i = 0; i<data.length; i ++){
        html_select += '<option value ="'+data[i].id+'">'+data[i].equipamiento+'</option>';
      } 
      $('#tipo_equipamiento').html(html_select);
    });
    
    $.get('select_ips/',function(data)
    {
      var html_select = '<option value="">Seleccione </option>'
      var html_select2 = '<option value="">Seleccione </option>'
      for(var i = 0; i<data.length; i ++){
        let ip = data[i].puerta_enlace.split('.');
        html_select += '<option value ="'+data[i].id+'">'+data[i].nombre+'</option>';
        html_select2 += '<option value ="'+data[i].id+'">'+ip[0]+'.'+ip[1]+'.'+ip[2]+'.'+'</option>';
      }

      //al cambiar un dato de un select se cambia en el otro 
      $("#ips").on("change", () => {
        $("#id_red").val($("#ips").val());
      });

      $("#id_red").on("change", () => {
        $("#ips").val($("#id_red").val());
      });

      //envia opciones de select a la vista create.blade.php
      $('#ips').html(html_select);
      $('#id_red').html(html_select2);
    });
  });
</script>

<script>
  $('#agregar_puesto').on('show.bs.modal', function (event) {
    $.get('select_area/',function(data){
      var html_select = '<option value="">Seleccione</option>'
      for(var i = 0; i<data.length; i ++)
        html_select += '<option value ="'+data[i].id_a+'">'+data[i].nombre_a+'</option>';
      $('#area').html(html_select);
    });
    $.get('select_persona/',function(data){
      var html_select = '<option value="">Seleccione</option>'
      for(var i = 0; i<data.length; i ++)
        html_select += '<option value ="'+data[i].id_p+'">'+data[i].apellido+' '+data[i].nombre_p+'</option>';
      $('#persona').html(html_select);
    });
    $.get('select_localizaciones/',function(data){
      var html_select = '<option value="">Seleccione</option>'
      /*for(var i = 0; i<data.length; i ++)
        html_select += '<option value ="'+data[i].id+'">'+data[i].nombre+'</option>';*/
      $('#localizacion').html(html_select);
    });

     // Variable para almacenar el valor seleccionado de localizacion
    var selectedLocalizacion = $('#localizacion').val();

    // Al seleccionar un área, cargar las localizaciones correspondientes
    $('#area').on('change', function() {
      var areaId = $(this).val();
      $.get('select_localizaciones_by_area/' + areaId, function(data) {
        var html_select = '<option value="">Seleccione</option>';
        for (var i = 0; i < data.length; i++) {
          html_select += '<option value ="' + data[i].id + '">' + data[i].nombre + '</option>';
        }
        $('#localizacion').html(html_select);

        // Restaurar el valor seleccionado de localizacion después de cargar las opciones
        $('#localizacion').val(selectedLocalizacion);
      });
    });

    // Al seleccionar una localización, cargar el área correspondiente
    $('#localizacion').on('change', function() {
      var localizacionId = $(this).val();
      $.get('select_area_by_localizacion/' + localizacionId, function(data) {
        // Primero, deseleccionamos el área actualmente seleccionada
        $('#area').val('');

        // Luego, seleccionamos el área correspondiente a la localización
        if (data.id_a) {
          $('#area').val(data.id_a);
        }
      });
    });
  });
</script>


<script>
  $('#agregar_software').on('show.bs.modal', function (event) {

   });
</script>
<body>

  @yield('content')

</body>

</html>