@extends('tipos_equipos.layouts.layout')
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

<!-- barra para buscar equipos -->

<!-- tabla de datos -->
<div class="col-md-12">             
  <table class="table table-striped table-bordered ">
    <thead>
      <th class="text-center">ID</th>
      <th class="text-center">Nombre</th>
      <th class="text-center">Acciones</th>   
    </thead>
    <tbody>
      @foreach($tipos_equipos as $tipo_equipo)
        <tr class="text-center">
        <td width="80">{{$tipo_equipo->id}}</td>
        <td>{{$tipo_equipo->nombre}}</td>
        <td width="90"><button class="btn btn-info btn-sm" onclick='fnOpenModalUpdate("{{$tipo_equipo->id}}")' title="update"
          data-nombre="{{$tipo_equipo->nombre}}" id="edit">Editar</button></td>
        </tr>
      @endforeach
    </tbody>       
  </table>
  <div class="modal fade" id="show2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
  {{ $tipos_equipos->appends($_GET)->links() }}
</div>
<script> 
  //Duracion de alerta (agregado, elimnado, editado)
  $("tipo_equipo").ready(function(){
    setTimeout(function(){
      $("div.alert").fadeOut();
    }, 5000 ); // 5 secs
  });
  </script> 

<script> 
  var ruta_create = '{{ route('store_tipo_equipo') }}'; 
  var ruta_update = '{{ route('update_tipo_equipo') }}';
  var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');
  var saveButton = $('<button type="submit" class="btn btn-info">Guardar</button>');
  //modal store
  function fnOpenModalStore() {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    var url = window.location.origin + "/show_store_tipo_equipo/";
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
  }
  //modal update
  function fnOpenModalUpdate(id) 
  {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_update_tipo_equipo/" + id,
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
        $("#modalfooter").append(saveButton);

        //Cambiar la acción del formulario
        $('#myForm').attr('action', ruta_update);

        // Mostrar el modal
        myModal.show();

        // Cambiar el tamaño del modal a "modal-lg"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.remove('modal-lg');
      },
    });
  }
</script> 
@stop