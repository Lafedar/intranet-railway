@extends('localizaciones.layouts.layout')
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
      <th class="text-center">Area</th>
      <th class="text-center">Nombre</th>
      <th class="text-center">Acciones</th>   
    </thead>
    <tbody>
      @foreach($localizaciones as $localizacion)
        <tr class="text-center">
        <td width="80">{{$localizacion->id}}</td>
        <td>{{$localizacion->nombre_a}}</td>
        <td>{{$localizacion->nombre}}</td>
        <td width="90"><button class="btn btn-info btn-sm" onclick='fnOpenModalUpdate("{{$localizacion->id}}")' title="update"
          data-nombre="{{$localizacion->nombre}}" id="edit">Editar</button></td>
        </tr>
      @endforeach
    </tbody>       
  </table>
  <form action="{{ route('update_localizacion') }}" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="show2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          {{csrf_field()}}
          <div id="modalshow" class="modal-body">
            <!-- Datos -->
          </div>
          <div id="modalfooter" class="modal-footer">
            <!-- Footer -->
          </div>
        
        </div>
      </div>
    </div>
  </form>
  {{ $localizaciones->appends($_GET)->links() }}
</div>
<script> 
  //Duracion de alerta (agregado, elimnado, editado)
  $("localizacion").ready(function()
  {
    setTimeout(function()
    {
      $("div.alert").fadeOut();
    }, 5000 ); // 5 secs
  });
  </script> 

<script> 
  //modal update
  function fnOpenModalUpdate(id) 
  {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_update_localizacion/" + id,
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

        /* Cambiar la acción del formulario
        $('#myForm').attr('action', ruta_update);*/

        // Mostrar el modal
        myModal.show();
      },
    });
  }
      $('#show2').on('show.bs.modal', function (event) 
    {
      $.get('select_area/',function(data)
      {
        var html_select = '<option value="">Seleccione </option>'

        for(var i = 0; i<data.length; i ++)
        {
          console.log("for ");
          html_select += '<option value ="'+data[i].id_a+'">'+data[i].nombre_a+'</option>';
        }
        $('#area').html(html_select);
      });
    });
</script> 
@stop