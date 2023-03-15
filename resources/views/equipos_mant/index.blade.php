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
          <td width="66">{{$equipo_mant->id_e}}</td>
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
            <td><button class="btn btn-info btn-sm" onclick='fnOpenModalUpdate({{$equipo_mant->id_e}})' title="update"
            data-tipo="{{$equipo_mant->id_tipo}}" data-area="{{$equipo_mant->id_area}}" data-localizacion="{{$equipo_mant->id_localizacion}}"
            id="edit-{{$equipo_mant->id}}">Editar</button></td>
        </tr>

      @endforeach
    </tbody>       
  </table>
  <form action="{{ route('update_equipo_mant') }}" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="editar_equipo_mant" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    var myModal = new bootstrap.Modal(document.getElementById('editar_equipo_mant'));
    var tipo = document.getElementById('edit-' + id).getAttribute('data-tipo');
    var area = document.getElementById('edit-' + id).getAttribute('data-area');
    var localizacion = document.getElementById('edit-' + id).getAttribute('data-localizacion');
    $('#editar_equipo_mant').on('show.bs.modal', function (event){
      $.get('select_tipo_equipo',function(data)
      {
        var html_select = '<option value="">Seleccione </option>'
        for(var i = 0; i<data.length; i ++)
        {
          if(data[i].id == tipo)
          {
            html_select += '<option value ="'+data[i].id+'"selected>'+data[i].nombre+'</option>';
          }
          else
          {
            html_select += '<option value ="'+data[i].id+'">'+data[i].nombre+'</option>';
          }
        }
        $('#tipo_equipo_mant_editar').html(html_select);
      });
    
      $.get('select_area_localizacion/',function(data)
      {
        var html_select = '<option value="">Seleccione </option>'
        var html_select2 = '<option value="">Seleccione </option>'
        for(var i = 0; i<data[0].length; i ++)
        {
          if(data[0][i].id_a == area)
          {
            html_select += '<option value ="'+data[0][i].id_a+'"selected>'+data[0][i].nombre_a+'</option>';
          }
          else
          {
            html_select += '<option value ="'+data[0][i].id_a+'">'+data[0][i].nombre_a+'</option>';
          }
        }
        for(var i = 0; i<data[1].length; i ++) 
        {
          if (data[1][i].id_area == area) 
          {
            if(data[1][i].id == localizacion)
            {
              html_select2 += '<option value ="'+data[1][i].id+'"selected>'+data[1][i].nombre+'</option>';
            }
            else
            {
              html_select2 += '<option value ="'+data[1][i].id+'">'+data[1][i].nombre+'</option>';
            }
          }
        }

        $('#area_editar').html(html_select);
        $('#localizacion_editar').html(html_select2);
        document.getElementById("area_editar").addEventListener("change", function() 
        {
          var selectedOption = this.value;
          var html_select2 = '<option value="">Seleccione </option>';
          for (var i = 0; i < data[1].length; i++) 
          {
            if (data[1][i].id_area == selectedOption) 
            {
              if(data[1][i].id == localizacion)
              {
                html_select2 += '<option value ="'+data[1][i].id+'">'+data[1][i].nombre+'</option>';
              }
              else
              {
                html_select2 += '<option value ="'+data[1][i].id+'">'+data[1][i].nombre+'</option>';
              }
            }
          }
          if(selectedOption == '')
          {
            document.getElementById("localizacion_editar").innerHTML = html_select2;
            document.getElementById("div_localizacion").style.display = "none";
          }
          else
          {
            document.getElementById("localizacion_editar").innerHTML = html_select2;
            document.getElementById("div_localizacion").style.display = "block";
          }
        });
      });
    });
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_update_equipo_mant/" + id,
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

        // Mostrar el modal
        myModal.show();
      },
    });
  }
</script> 
<script>
  $('#agregar_equipo_mant').on('show.bs.modal', function (event) {

    $.get('select_area_localizacion/',function(data)
    {
      var html_select = '<option value="">Seleccione </option>'
      var html_select2 = '<option value="">Seleccione </option>'

      for(var i = 0; i<data[0].length; i ++)
      {
        html_select += '<option value ="'+data[0][i].id_a+'">'+data[0][i].nombre_a+'</option>';
      }

      $('#area').html(html_select);
      $('#localizacion').html(html_select2);

      document.getElementById("area").addEventListener("change", function() 
      {
        var selectedOption = this.value;
        var html_select2 = '<option value="">Seleccione </option>';
        for (var i = 0; i < data[1].length; i++) 
        {
          if (data[1][i].id_area == selectedOption) 
          {
            html_select2 += '<option value="' + data[1][i].id + '">' + data[1][i].nombre + '</option>';
            document.getElementById("localizacion").innerHTML = html_select2;
          }
        }
        if(selectedOption == '')
        {
          document.getElementById("localizacion").innerHTML = html_select2;
          document.getElementById("div_localizacion").style.display = "none";
        }
        else
        {
          document.getElementById("localizacion").innerHTML = html_select2;
          document.getElementById("div_localizacion").style.display = "block";
        }
      });
    });

    $.get('select_tipo_equipo/',function(data)
    {
      var html_select = '<option value="">Seleccione </option>'
      for(var i = 1; i<data.length; i ++)
      {
        html_select += '<option value ="'+data[i].id+'">'+data[i].nombre+'</option>';
      }
      $('#tipo').html(html_select);
    });
  });
</script>
@stop