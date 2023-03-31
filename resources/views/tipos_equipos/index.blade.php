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
      <th class="text-center">Fallas</th>
      <th class="text-center">Acciones</th>   
    </thead>
    <tbody>
      @foreach($tipos_equipos as $tipo_equipo)
        <tr class="text-center">
          <td width="80">{{$tipo_equipo->id}}</td>
          <td>{{$tipo_equipo->nombre}}</td>
          <td>
            @foreach ($fallas as $falla)
              @if ($falla->id_tipo_equipo == $tipo_equipo->id)
                -{{$falla->nom_falla}}
              @endif
            @endforeach
          </td>
          <td width="300">
            <button class="btn btn-info btn-sm" onclick='fnOpenModalUpdate("{{$tipo_equipo->id}}")' title="update" id="edit">Editar</button>
            <button class="btn btn-info btn-sm" onclick='fnOpenModalAssing("{{$tipo_equipo->id}}")' title="assing" id="edit">Asignar</button>
            <button class="btn btn-danger btn-sm" onclick='fnOpenModalDeleteFalla("{{$tipo_equipo->id}}")' title="assing" id="edit">Eliminar falla</button>
          </td>
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
  var ruta_assing = '{{ route('assing_tipo_equipo') }}';
  var ruta_delete = '{{ route('delete_falla_te') }}';
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
  function fnOpenModalUpdate(id) {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    var url = "{{ url('show_update_tipo_equipo') }}/" + id;
    $.get(url, function(data) {
      // Borrar contenido anterior
      $("#modalshow").empty();
      // Establecer el contenido del modal
      $("#modalshow").html(data);

      // Borrar contenido anterior
      $("#modalfooter").empty();

      // Agregar el botón "Cerrar" y "Guardar" al footer
      $("#modalfooter").append(closeButton);
      $("#modalfooter").append(saveButton);

      // Cambiar la acción del formulario
      $('#myForm').attr('action', ruta_update);

      // Mostrar el modal
      myModal.show();

      // Cambiar el tamaño del modal a "modal-lg"
      var modalDialog = myModal._element.querySelector('.modal-dialog');
      modalDialog.classList.remove('modal-sm');
      modalDialog.classList.remove('modal-lg');
    });
  }
  //modal assing
  var aux;
  function fnOpenModalAssing(id)
  {
    aux=id;
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    var url = "{{ url('show_assing_tipo_equipo') }}/" + id;
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
      $('#myForm').attr('action', ruta_assing);

      // Mostrar el modal
      myModal.show();

      // Cambiar el tamaño del modal a "modal-sm"
      var modalDialog = myModal._element.querySelector('.modal-dialog');
      modalDialog.classList.remove('modal-lg');
      modalDialog.classList.add('modal-sm');
    });
    $('#show2').on('show.bs.modal', function (event) {
      $.get('select_fallas/',function(data){
        var html_select = '<option value="">Seleccione </option>'
        for(var j = 0; j < data[0].length; j++) {
          let found = false; // variable para indicar si se encontró la pareja (id_falla, id_tipo_equipo)
          for(var i = 0; i < data[1].length; i++) {
            if(data[1][i].id_tipo_equipo == aux && data[1][i].id_falla == data[0][j].id) {
              found = true; // se encontró la pareja, no se agrega
              break;
            }
          }
          if (!found) {
            html_select += '<option value ="'+data[0][j].id+'">'+data[0][j].nombre+'</option>'; // no se encontró la pareja, se agrega
          }
        }
        console.log(html_select);
        $('#fallasSinAsingar').html(html_select);
      });
    });
  }
  function fnOpenModalDeleteFalla(id){
    aux=id;
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    var url = "{{ url('show_delete_falla_te') }}/" + id;
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
      $('#myForm').attr('action', ruta_delete);

      // Mostrar el modal
      myModal.show();

      // Cambiar el tamaño del modal a "modal-sm"
      var modalDialog = myModal._element.querySelector('.modal-dialog');
      modalDialog.classList.remove('modal-lg');
      modalDialog.classList.add('modal-sm');
    });
    $('#show2').on('show.bs.modal', function (event) {
    $.get('select_fallas/',function(data){
      var html_select = '<option value="">Seleccione </option>'
      for(var j = 0; j < data[0].length; j++) {
        for(var i = 0; i < data[1].length; i++) {
          if(data[1][i].id_tipo_equipo == aux && data[1][i].id_falla == data[0][j].id) {
            console.log(html_select);
            html_select += '<option value ="'+data[0][j].id+'">'+data[0][j].nombre+'</option>';
          }
        }
      }
      console.log(html_select);
      $('#fallasAsignadas').html(html_select);
    });
  });
  }
</script> 
@stop