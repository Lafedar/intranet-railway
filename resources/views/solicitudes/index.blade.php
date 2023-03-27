@extends('solicitudes.layouts.layout')
@section('content')

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

<!-- barra para buscar solicitudes -->

<!-- tabla de datos -->

<div class="col-md-12">             
  <table class="table table-striped table-bordered ">
    <thead>
      <th class="text-center">ID</th>
      <th class="text-center">Titulo</th>
      <th class="text-center">Tipo de solicitud</th>
      <th class="text-center">Equipo</th>
      <th class="text-center">Estado</th>     
      <th class="text-center">Tipo de falla</th>    
      @can('ver_solicitante')  
        <th class="text-center">Solicitante</th>
      @endcan
      @can('ver_encargado')
        <th class="text-center">Encargado</th>  
      @endcan
      <th class="text-center">Acciones</th>        
    </thead>
    <tbody>
        @foreach($solicitudes as $solicitud)
            <tr>
              <td width="60">{{sprintf('%05d',$solicitud->id)}}</td>
              <td width="350">{{$solicitud->titulo}}</td>
              <td width="150">{{$solicitud->tipo_solicitud}}</td>
              <td width="107">{{$solicitud->id_equipo}}</td>
              <td >{{$solicitud->estado}}</td>
              <td >{{$solicitud->falla}}</td>
              @can('ver_solicitante')
                <td >{{$solicitud->nombre_solicitante}}</td>
              @endcan
              @can('ver_encargado')
                <td >{{$solicitud->nombre_encargado}}</td>
              @endcan
              <td class="text-center" width="350">
                <div>
                  <!-- Boton de ver solitud en detalle -->
                  <button id="detalle" class="btn btn-info btn-sm" onclick='fnOpenModalShow({{$solicitud->id}})' title="show">Detalles</button>
                  <!-- Boton de editar y eliminar -->
                  @can('actualizar-solicitud')
                    <button id="actualizar" class="btn btn-info btn-sm" onclick='fnOpenModalUpdate({{$solicitud->id}})' title="update">Actualizar</button>
                  @endcan
                  @can('asignar-solicitud')
                    <button id="asignar" class="btn btn-info btn-sm" onclick='fnOpenModalAssing({{$solicitud->id}})' title="assing">Asignar</button>
                  @endcan
                  @can('eliminar-solicitud')
                    <a href="{{url('destroy_solicitud', $solicitud->id)}}" class="btn btn-danger btn-sm" title="Borrar" onclick="return confirm ('Está seguro que desea eliminar esta solicitud?')"
                    data-position="top" data-delay="50" data-tooltip="Borrar">X</a>
                  @endcan
                </div>
              </td>
            </tr>
        @endforeach
    </tbody>       
  </table>   
  
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

  {{ $solicitudes->appends($_GET)->links() }}
</div>

<script> 
  //Duracion de alerta (agregado, elimnado, editado)
  $("solicitud").ready(function(){
    setTimeout(function(){
      $("div.alert").fadeOut();
    }, 5000 ); // 5 secs

  });
</script> 

<script>  
  var ruta_create = '{{ route('store_solicitud') }}';
  var ruta_update = '{{ route('update_solicitud') }}';
  var ruta_assing = '{{ route('assing_solicitud') }}';
  var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');
  var saveButton = $('<button type="submit" class="btn btn-info">Guardar</button>');
  //modal store
  function fnOpenModalStore() {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    var url = window.location.origin + "/show_store_solicitud/";
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
      $('#myForm').attr('action', ruta_create);

      // Mostrar el modal
      myModal.show();

      // Cambiar el tamaño del modal a "modal-lg"
      var modalDialog = myModal._element.querySelector('.modal-dialog');
      modalDialog.classList.remove('modal-sm');
      modalDialog.classList.remove('modal-lg');
    });

    $('#show2').on('show.bs.modal', function (event){
      $.get('select_create/',function(data){
        var htmlSelectArea = '<option value="">Seleccione </option>'
        var htmlSelectLocalizacion = '<option value="">Seleccione </option>'
        var htmlSelectTipoSolicitud = '<option value="">Seleccione </option>'
        var htmlSelectEquipo = '<option value="">Seleccione </option>'
        var htmlSelectFalla = '<option value="">Seleccione </option>'

        // [0]=areas [1]=localizaciones [2]=tipo_solicitudes [3]=equipos_mant 
        // [4]=fallas [5]=tipos_equipos [6]=fallasxtipo
        for(var i = 0; i<data[0].length; i ++){
          htmlSelectArea += '<option value ="'+data[0][i].id_a+'">'+data[0][i].nombre_a+'</option>';
        }

        $('#area').html(htmlSelectArea);
        $('#localizacion').html(htmlSelectLocalizacion);
        //toma cambio de seleccion de area
        $('#area').on('change', function () {
          const selectedOption = $(this).val();

          // Obtener las localizaciones correspondientes al área seleccionada y agregarlas al select correspondiente
          let htmlSelectLocalizacion = '<option value="">Seleccione</option>';
          data[1].forEach(localizacion => {
            if (localizacion.id_area == selectedOption) {
              htmlSelectLocalizacion += `<option value="${localizacion.id}">${localizacion.nombre}</option>`;
            }
          });
          $('#localizacion').html(htmlSelectLocalizacion);

          // Mostrar o ocultar los campos según la selección
          if (!selectedOption) {
            $('#div_localizacion, #div_tipo_solicitud, #div_equipo, #div_falla').hide();
          } 
          else {
            $('#div_localizacion').show();
            $('#div_tipo_solicitud, #div_equipo, #div_falla').hide();
          }
        });
      
        var aux_localizacion;

        $('#localizacion').on('change', function() {
          var htmlSelectTipoSolicitud = '<option value="">Seleccione</option>';
          var selectedOption = $(this).val();
          aux_localizacion = selectedOption;
          if (selectedOption == '') {
            $('#div_tipo_solicitud, #div_equipo, #div_falla').hide();
          } 
          else {
            $('#div_tipo_solicitud').show();
            $('#div_equipo, #div_falla').hide();
          }
          $.each(data[2], function(i, tipo_solicitud) {
            htmlSelectTipoSolicitud += '<option value="' + tipo_solicitud.id + '">' + tipo_solicitud.nombre + '</option>';
          });
          $('#tipo_solicitud').html(htmlSelectTipoSolicitud);
        });

        $('#tipo_solicitud').on('change', function () {
          const selectedOption = $(this).val();
          const divEquipo = $('#div_equipo');
          const divFalla = $('#div_falla');

          if (!selectedOption) {
            divEquipo.hide();
            divFalla.hide();
          } 
          else if (selectedOption == 1) {
            divEquipo.show();
            divFalla.hide();

            let htmlSelectEquipo = '<option value="">Seleccione </option>';
            data[3].forEach(equipo => {
              if (aux_localizacion == equipo.id_localizacion) {
                htmlSelectEquipo += `<option value="${equipo.id}">${equipo.id}</option>`;
              }
            });
            $('#equipo').html(htmlSelectEquipo);
          } 
          else {
            divEquipo.hide();
            divFalla.show();

            let htmlSelectFalla = '<option value="">Seleccione </option>';
            data[6].forEach(solicitud => {
              if (solicitud.id_tipo_solicitud == 2) {
                const falla = data[4].find(falla => falla.id === solicitud.id_falla);
                if (falla) {
                  htmlSelectFalla += `<option value="${solicitud.id_falla}">${falla.nombre}</option>`;
                }
              }
            });
            $('#falla').html(htmlSelectFalla);
          }
        });
      
        $('#equipo').on('change', function () {
          var htmlSelectFalla = '<option value="">Seleccione </option>'
          var selectedOption = $(this).val();
          var aux_tipo_equipo;
          if(selectedOption == ''){
            $('#div_falla').hide();
          }
          else{
            $('#div_falla').show();
            for(var k = 0; k<data[3].length; k ++){
              if(selectedOption == data[3][k].id){
                aux_tipo_equipo = data[3][k].id_tipo;
              }
            }
            for(var j = 0; j<data[6].length; j ++){
              if(data[6][j].id_tipo_equipo == selectedOption){
                for(var i = 0; i<data[4].length; i ++){ 
                  if(data[6][j].id_falla == data[4][i].id){
                    htmlSelectFalla += '<option value ="'+data[6][j].id_falla+'">'+data[4][i].nombre+'</option>';
                  }
                }
              }
            }
            $('#falla').html(htmlSelectFalla);
          }
        });
      });
    });
  }

  //modal show
  function fnOpenModalShow(id) 
  {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_solicitud/" + id,
      type: 'GET',
      success: function(data) {
        // Borrar contenido anterior
        $("#modalshow").empty();
        // Establecer el contenido del modal
        $("#modalshow").html(data);

        // Borrar contenido anterior
        $("#modalfooter").empty();

        // Agregar el botón "Cerrar" al footer
        $("#modalfooter").append(closeButton);

        // Mostrar el modal
        myModal.show();

        // Cambiar el tamaño del modal a "modal-lg"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.add('modal-lg');
      },
    });
  }

  //modal update
  function fnOpenModalUpdate(id)
  {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_update_solicitud/" + id,
      type: 'GET',
      success: function(data) {
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
        $('#myForm').attr('action', ruta_update);

        // Mostrar el modal
        myModal.show();

        // Cambiar el tamaño del modal a "modal-lg"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.add('modal-lg');
      },
    });
  }
  $('#show2').on('show.bs.modal', function (event) {
    $.get('select_estado/',function(data){
      var html_select = '<option value="">Seleccione </option>'

      for(var i = 0; i<data.length; i ++){
        html_select += '<option value ="'+data[i].id+'">'+data[i].nombre+'</option>';
      }
      $('#estado').html(html_select);
    });
  });

  //modal assing
  function fnOpenModalAssing(id)
  {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_assing_solicitud/" + id,
      type: 'GET',
      success: function(data) {
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
        $('#myForm').attr('action', ruta_assing);

        // Mostrar el modal
        myModal.show();

        // Cambiar el tamaño del modal a "modal-sm"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-lg');
        modalDialog.classList.add('modal-sm');
      },
    });
  }
  $('#show2').on('show.bs.modal', function (event) {
    $.get('select_users/',function(data){
      var html_select = '<option value="">Seleccione </option>'

      for(var i = 0; i<data[0].length; i ++){
        for(var k = 0; k<data[1].length; k ++){
          if((data[0][i].id == data[1][k].model_id) && (data[1][k].role_id == 22)){
            html_select += '<option value ="'+data[0][i].id+'">'+data[0][i].name+'</option>';
          }
        }
      }
      $('#user').html(html_select);
    });
  });
</script>

@stop