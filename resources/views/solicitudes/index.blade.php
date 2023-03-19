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
  $("solicitud").ready(function()
  {
    setTimeout(function()
    {
      $("div.alert").fadeOut();
    }, 5000 ); // 5 secs

  });
</script> 

<script>  
  //modal show
  function fnOpenModalShow(id) 
  {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    // Cambiar el tamaño del modal al mostrarlo
    $('#show2').on('show.bs.modal', function (event) {
      var modal = $(this);
      var modalDialog = modal.find('.modal-dialog');
      // Cambiar la clase "modal-dialog" para hacer que el modal sea más grande
      modalDialog.removeClass('estilo').addClass('modal-lg');
    });
    $.ajax
    ({
      url: window.location.protocol + '//' + window.location.host + "/show_solicitud/" + id,
      type: 'GET',
      success: function(data) 
      {
        // Borrar contenido anterior
        $("#modalshow").empty();
        // Establecer el contenido del modal
        $("#modalshow").html(data);

        // Borrar contenido anterior
        $("#modalfooter").empty();
        // Agregar el botón "Cerrar" al footer
        var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');
        $("#modalfooter").append(closeButton);

        // Mostrar el modal
        myModal.show();
      },
    });
  }
</script>
<script> 
  //modal update
  var ruta = '{{ route('update_solicitud') }}';
  function fnOpenModalUpdate(id)
  {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    // Cambiar el tamaño del modal al mostrarlo
    $('#show2').on('show.bs.modal', function (event) {
      var modal = $(this);
      var modalDialog = modal.find('.modal-dialog');
      // Cambiar la clase "modal-dialog" para hacer que el modal sea más grande
      modalDialog.removeClass('estilo').addClass('modal-lg');
    });
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
        // Agregar el botón "Cerrar" al footer
        var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button> <button type="submit" class="btn btn-info">Guardar</button>');
        $("#modalfooter").append(closeButton);

        // Cambiar la acción del formulario
        $('#myForm').attr('action', ruta);

        // Mostrar el modal
        myModal.show();
      },
    });
  }
  $('#show2').on('show.bs.modal', function (event) 
  {
    $.get('select_estado/',function(data)
    {
      var html_select = '<option value="">Seleccione </option>'

      for(var i = 0; i<data.length; i ++)
      {
       html_select += '<option value ="'+data[i].id+'">'+data[i].nombre+'</option>';
      }
      $('#estado').html(html_select);
    });
  });
</script>
<script>
  //modal assing
  var ruta = '{{ route('assing_solicitud') }}';
  function fnOpenModalAssing(id)
  {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));

    // Cambiar el tamaño del modal al mostrarlo
    $('#show2').on('show.bs.modal', function (event) {
      var modal = $(this);
      var modalDialog = modal.find('.modal-dialog');
      // Cambiar la clase "modal-dialog" para hacer que el modal sea más pequeño
      modalDialog.removeClass('estilo').addClass('modal-sm');
    });

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
        // Agregar el botón "Cerrar" al footer
        var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button> <button type="submit" class="btn btn-info">Guardar</button>');
        $("#modalfooter").append(closeButton);

        // Cambiar la acción del formulario
        $('#myForm').attr('action', ruta);

        // Mostrar el modal
        myModal.show();
      },
    });
  }
  $('#show2').on('show.bs.modal', function (event) 
  {
    $.get('select_users/',function(data)
    {
      var html_select = '<option value="">Seleccione </option>'

      for(var i = 0; i<data[0].length; i ++)
      {
        for(var k = 0; k<data[1].length; k ++)
        {
          if((data[0][i].id == data[1][k].model_id) && (data[1][k].role_id == 22))
          {
            html_select += '<option value ="'+data[0][i].id+'">'+data[0][i].name+'</option>';
          }
        }
      }
      $('#user').html(html_select);
    });
  });
</script>

@stop