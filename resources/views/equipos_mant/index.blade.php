@extends('equipos_mant.layouts.layout')
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
      <th class="text-center">Tipo</th>
      <th class="text-center">Marca</th>
      <th class="text-center">Modelo</th>
      <th class="text-center">Descripcion</th>
      <th class="text-center">Nro de Serie</th>
      <th class="text-center">Area</th>     
      <th class="text-center">Localizacion</th>
      <th class="text-center">Uso</th>
      <th class="text-center">Acciones</th>        
    </thead>
    <tbody>
      @foreach($equipos_mant as $equipo_mant)
        <tr class="text-center">
          <td width="60">{{$equipo_mant->id}}</td>
          <td width="200">{{$equipo_mant->nombre_tipo}}</td>
          <td width="160">{{$equipo_mant->marca}}</td>
          <td width="160">{{$equipo_mant->modelo}}</td>
          <td>{{$equipo_mant->descripcion}}</td>
          <td>{{$equipo_mant->num_serie}}</td>
          <td>{{$equipo_mant->area}}</td>
          <td>{{$equipo_mant->localizacion}}</td>
          @if($equipo_mant->uso == 1)
            <td width="60"><div class="circle_green"></div></td>
          @else
            <td width="60"><div class="circle_grey"></div></td>
          @endif
          <td><button id="edit" class="btn btn-info btn-sm" onclick='fnOpenModalUpdate({{$equipo_mant->id}})' title="update">Editar</button></td>
        </tr>
      @endforeach
    </tbody>       
  </table>
  <form action="{{ route('update_equipo_mant') }}" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="show2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
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
  {{ $equipos_mant->appends($_GET)->links() }}
</div>
<script> 
  //Duracion de alerta (agregado, elimnado, editado)
  $("equipo_mant").ready(function()
  {
    setTimeout(function()
    {
      $("div.alert").fadeOut();
    }, 5000 ); // 5 secs
  });
  </script> 

  <script> 
  //modal update
  function fnOpenModalUpdate(id) {
  var myModal = new bootstrap.Modal(document.getElementById('show2'));
    console.log("antes de .ajax");
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_update_equipo_mant/" + id,
      type: 'GET',
      success: function(data) {
         console.log("dentro de .ajax");
        // Borrar contenido anterior
        $("#modalshow").empty();
        // Establecer el contenido del modal
        $("#modalshow").html(data);

        // Borrar contenido anterior
        $("#modalfooter").empty();
        // Agregar el botón "Cerrar" al footer
        var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button> <button type="submit" class="btn btn-info">Guardar</button>');
        $("#modalfooter").append(closeButton);

        // Mostrar el modal
        console.log("antes de myModal.show();");
        myModal.show();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log("Error en la petición AJAX: " + textStatus + " - " + errorThrown);
      }
    });
  }
</script> 

@stop