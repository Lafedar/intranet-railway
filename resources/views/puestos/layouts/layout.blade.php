<!DOCTYPE html>
<html lang="es">

  <link href="{{ asset('css/acciones.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
  <script src="https://kit.fontawesome.com/b36ad16a06.js" crossorigin="anonymous"></script>
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
          <button class="btn btn-info" onclick='fnOpenModalStore()' data-toggle="modal"> Nuevo puesto</button>
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

  <div class="modal fade" id="show3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog estilo" role="document">
      <div class="modal-content">
        <form id="myForm3" method="POST" enctype="multipart/form-data">
          {{csrf_field()}}
          <div id="modalshow3" class="modal-body">
            <!-- Datos -->
          </div>
          <div id="modalfooter3" class="modal-footer">
            <!-- Footer -->
          </div>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>

  <script>
    var ruta_update = '{{ route('update_puesto') }}';
    var ruta_store = '{{ route('store_puesto') }}';
    var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');
    var saveButton = $('<button type="submit" class="btn btn-info">Guardar</button>');

    function fnOpenModalStore(){
      var myModal = new bootstrap.Modal(document.getElementById('show2'));
      var url = window.location.origin + "/show_store_puesto/";
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

        // Mostrar el modal
        myModal.show();

        // Cambiar el tamaño del modal a "modal-lg"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.remove('modal-lg');
      });
      $('#show2').on('show.bs.modal', function (event){
        $.get('select_area/',function(data){
          var html_select = '<option value="">Seleccione</option>'
          for(var i = 0; i<data.length; i ++)
            html_select += '<option value ="'+data[i].id_a+'">'+data[i].nombre_a+'</option>';
          $('#area').html(html_select);
        });
        $.get('select_persona/',function(data){
          var html_select = '<option value="">Seleccione</option>'
          for(var i = 0; i<data.length; i ++){
            if(data[i].activo == 1){
              if(data[i].apellido == null){
                html_select += '<option value ="'+data[i].id_p+'">'+data[i].nombre_p+'</option>';
              }else{
                html_select += '<option value ="'+data[i].id_p+'">'+data[i].nombre_p+' '+data[i].apellido+'</option>';
              }
            }
          }
          $('#persona').html(html_select);
        });
        $.get('select_localizaciones/',function(data){
          var html_select = '<option value="">Seleccione</option>'
          $('#localizacion').html(html_select);
        });
        
        // Variable para almacenar el valor seleccionado de localizacion
        var selectedLocalizacion = $('#localizacion').val();

        // Al seleccionar un área, cargar las localizaciones correspondientes
        $('#area').on('change', function() {
          var areaId = $(this).val();
          if (areaId === "") {
            var html_select = '<option value="">Seleccione</option>';
            $('#localizacion').html(html_select);
            $('#localizacion').val("");
          } else {
            $.get('select_localizaciones_by_area/' + areaId, function(data) {
              var html_select = '<option value="">Seleccione</option>';
              for (var i = 0; i < data.length; i++) {
                html_select += '<option value="' + data[i].id + '">' + data[i].nombre + '</option>';
              }
              $('#localizacion').html(html_select);
            });
          }
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
    }
    function getPuesto(idPuesto) {
      return new Promise(function(resolve, reject) {
        $.ajax({
          url: window.location.protocol + '//' + window.location.host + "/getPuesto/" + idPuesto,
          method: 'GET',
          success: function(data) {
            resolve(data);
          },
          error: function(error) {
            reject(error);
          }
        });
      });
    }
    
    let puesto;
    async function fnOpenModalUpdate(id){
      try {
        puesto = await getPuesto(id);
        console.log(puesto);

        var myModal = new bootstrap.Modal(document.getElementById('show3'));
        var url = window.location.origin + "/show_update_puesto/" + id;
        var data = await $.get(url); // Esperar a que los datos se obtengan

        // Borrar contenido anterior
        $("#modalshow3").empty();

        // Establecer el contenido del modal
        $("#modalshow3").html(data);
        // Borrar contenido anterior
        $("#modalfooter3").empty();

        // Agregar el botón "Cerrar y Guardar" al footer
        $("#modalfooter3").append(closeButton);
        $("#modalfooter3").append(saveButton);

        // Cambiar la acción del formulario
        $('#myForm3').attr('action', ruta_update);

        // Mostrar el modal
        myModal.show();

        // Cambiar el tamaño del modal a "modal-lg"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.remove('modal-lg');

        
        // Aquí puedes colocar el código que depende de los datos de puesto,
        // por ejemplo, el código para actualizar los campos del modal.
        // ...
      } catch (error) {
        console.error('Error al obtener los datos o al mostrar el modal:', error);
      } 
    }
    $('#show3').on('show.bs.modal', function (event){
      $.get('select_area/',function(data){
        var areaSeleccionada = null;
        var html_select = '<option value="">Seleccione</option>'
        for(var i = 0; i<data.length; i ++){
          if(puesto.idArea == data[i].id_a){         
            console.log("INGRESA area: ", puesto.idArea, data[i].id_a);
            html_select += '<option value ="'+data[i].id_a+'" selected>'+data[i].nombre_a+'</option>';
            areaSeleccionada = data[i].id_a;
          }else{
            html_select += '<option value ="'+data[i].id_a+'">'+data[i].nombre_a+'</option>';
          }
        }
        if(areaSeleccionada){
          $.get('select_localizaciones_by_area/' + areaSeleccionada, function(data) {
            var html_select = '<option value="">Seleccione</option>';
            for (var i = 0; i < data.length; i++) {
              if(puesto.idLocalizacion == data[i].id){
                html_select += '<option value="' + data[i].id + '" selected>' + data[i].nombre + '</option>';
              } else{
                html_select += '<option value="' + data[i].id + '">' + data[i].nombre + '</option>';
              }
            }
            $('#localizacion1').html(html_select);
          });
        }
        $('#area1').html(html_select);
      });
      $.get('select_persona/',function(data){
        var html_select = '<option value="">Seleccione</option>'
        for(var i = 0; i<data.length; i ++){
          if(data[i].activo == 1){
            if(puesto.idPersona == data[i].id_p){
              if(data[i].apellido == null){
                html_select += '<option value ="'+data[i].id_p+'" selected>'+data[i].nombre_p+'</option>';
              }else{
                html_select += '<option value ="'+data[i].id_p+'" selected>'+data[i].nombre_p+' '+data[i].apellido+'</option>';
              }
            } else{
              if(data[i].apellido == null){
                html_select += '<option value ="'+data[i].id_p+'">'+data[i].nombre_p+'</option>';
              }else{
                html_select += '<option value ="'+data[i].id_p+'">'+data[i].nombre_p+' '+data[i].apellido+'</option>';
              }
            }
          }
        }
        $('#persona1').html(html_select);
      });

      // Variable para almacenar el valor seleccionado de localizacion
      var selectedLocalizacion = $('#localizacion').val();

      // Al seleccionar un área, cargar las localizaciones correspondientes
      $('#area1').on('change', function() {
        console.log("ingresa change area");
        var areaId = $(this).val();
        if (areaId === "") {
          var html_select = '<option value="">Seleccione</option>';
          $('#localizacion1').html(html_select);
          $('#localizacion1').val("");
        } else {
          $.get('select_localizaciones_by_area/' + areaId, function(data) {
            var html_select = '<option value="">Seleccione</option>';
            for (var i = 0; i < data.length; i++) {
              if(puesto.idLocalizacion == data[i].id){
                html_select += '<option value="' + data[i].id + '" selected>' + data[i].nombre + '</option>';
              } else{
                html_select += '<option value="' + data[i].id + '">' + data[i].nombre + '</option>';
              }
            }
            $('#localizacion1').html(html_select);
          });
        }
      });

      // Al seleccionar una localización, cargar el área correspondiente
      $('#localizacion1').on('change', function() {
        var localizacionId = $(this).val();
        $.get('select_area_by_localizacion/' + localizacionId, function(data) {
          // Primero, deseleccionamos el área actualmente seleccionada
          $('#area1').val('');

          // Luego, seleccionamos el área correspondiente a la localización
          if (data.id_a) {
            $('#area1').val(data.id_a);
          }
        });
      });
      $('#desc_puesto1').val(puesto.nombrePuesto);
      if(puesto.observaciones != null){
        $('#obs1').val(puesto.observaciones);
      }
    });
  </script>

  <script>
    $('#agregar_software').on('show.bs.modal', function (event) {});
  </script>
  <body>
    @yield('content')
  </body>

</html>