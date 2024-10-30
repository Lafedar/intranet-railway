@extends('empleado.layouts.layout')
@section('content')

<div id="alert" class="alert alert-info" style="display: none"></div>

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

<form method="GET" action="{{ route('empleado.index') }}">
    <div class="col-md-12 ml-auto">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control col-md-2" id="search" name="search" value="{{ request('search') }}" placeholder="Buscar">
                <div class="input-group-append">
                    <div class="form-check form-check-inline" style="margin-left: 15px">
                        <input class="form-check-input" type="checkbox" id="filtroJefe" name="filtroJefe" {{ request('filtroJefe') ? 'checked' : '' }}>
                        <label class="form-check-label" for="filtroJefe" style="font-size: 1.25em; font-weight: bold;">Jefe</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="filtroActividad" name="filtroActividad" {{ request('filtroActividad') ? 'checked' : '' }}>
                        <label class="form-check-label" for="filtroActividad" style="font-size: 1.25em; font-weight: bold;">Solo en actividad</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </div>
    </div>
</form>



<div class="col-sm-12">             
  <table id="test" class="table table-striped table-bordered table-condensed" role="grid" cellspacing="0" cellpadding="2" border="10">
    <thead>
      <th class="text-center">Apellido y nombre</th>
      <th class="text-center">DNI</th>
      <th class="text-center">Fecha de ingreso</th>
      <th class="text-center">Fecha de nacimiento</th>
      <th class="text-center">Area</th>
      <th class="text-center">Turno</th>
      <th class="text-center">Jefe</th>
      <th class="text-center">En actividad</th>
      <th class="text-center">Acciones</th>
    </thead>        
    
    <tbody>
      @if($empleados->count())
        @foreach($empleados as $empleado) 
          <tr>
            @if ($empleado->dni != 9999999)
              <td> {{$empleado->apellido . ' '. $empleado->nombre_p}}</td>
              <td align="center">{{$empleado->dni}}</td>
              @if ($empleado->fe_ing != '')
                <td align="center">{!! \Carbon\Carbon::parse($empleado->fe_ing)->format("d-m-Y") !!}</td>
              @else
                <td align="center"></td>
              @endif
              @if ($empleado->fe_nac != '')
                <td align="center">{!! \Carbon\Carbon::parse($empleado->fe_nac)->format("d-m-Y") !!}</td>
              @else
                <td align="center"></td>
              @endif
              <td>{{$empleado->nombre_a}}</td>
              <td>{{$empleado->nombreTurno}}</td>
              @if($empleado->jefe == 1)
                <td width="60" style="text-align: center;"><div class="circle_green"></div></td>
              @else
                <td width="60" style="text-align: center;"><div class="circle_grey"></div></td>
              @endif
              @if($empleado->activo == 1)
                <td width="60" style="text-align: center;"><div class="circle_green"></div></td>
              @else
                <td width="60" style="text-align: center;"><div class="circle_grey"></div></td>
              @endif
              <td align="center" width="175">
                <div class="d-inline-flex">
                  <a href="#" class="btn btn-info btn-sm mr-1" data-toggle="modal" data-id="{{$empleado->id_p}}" data-nombre="{{$empleado->nombre_p}}" 
                    data-apellido="{{$empleado->apellido}}" data-area="{{$empleado->area}}" data-dni="{{$empleado->dni}}" data-fe_nac="{{$empleado->fe_nac}}" 
                    data-fe_ing="{{$empleado->fe_ing}}" data-interno="{{$empleado->interno}}" data-correo="{{$empleado->correo}}" data-activo="{{$empleado->activo}}" 
                    data-turno="{{$empleado->idTurno}}" data-jefe="{{$empleado->jefe}}" data-target="#editar_empleado">Editar
                  </a>
                  @if($empleado->jefe == 1)
                    <button id="jefeArea" class="btn btn-info btn-sm mr-1" onclick='fnOpenModalJefeArea({{$empleado->id_p}})' title="jefeArea">Areas</button>
                  @endif
                  <a href="{{ route('empleado.cursos', $empleado->id_p) }}" class="btn btn-info btn-sm mr-1">Ver Cursos</a>


                  <form id="formDelete" action="{{ route('destroy_empleado', $empleado->id_p) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm btn-borrar" data-tooltip="Borrar"> X</button>
                  </form>
                </div>
              </td>
            @endif
          </tr>
        @endforeach  
      @endif  
    </tbody>
  </table>

  <!-- Agregar enlaces de paginación -->
  <div class="pagination-wrapper">
  {{ $empleados->links('pagination::bootstrap-4') }}
  </div>
</div>

@include('empleado.edit')

<div class="modal fade" id="show2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog estilo" role="document">
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

<div class="modal fade" id="show3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog estilo" role="document">
    <div class="modal-content">
      {{csrf_field()}}
      <div id="modalshow3" class="modal-body">
        <!-- Datos -->
      </div>
      <div id="modalfooter3" class="modal-footer">
        <!-- Footer -->
      </div>
    </div>
  </div>
</div>

<script>
  var closeButton = $('<button type="button" class="btn btn-secondary" id="closeButton" data-dismiss="modal">Cerrar</button>');
  var closeButton2 = $('<button type="button" class="btn btn-secondary" id="closeButton2" data-dismiss="modal">Cerrar</button>');
  var saveButton = $('<button type="submit" class="btn btn-info" id="saveButton" onclick="fnSaveSolicitud()">Guardar</button>');
  var idJefe;
  function fnOpenModalJefeArea(id){
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    idJefe = id;
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/showUpdateAreaXJefe/" + id,
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

  $(document).ready(function () {
    // Controlador para el checkbox de actividad
    $('#actividadCreate').on('change', function () {
      if ($(this).prop('checked')) {
        // Si se marca el checkbox de actividad, desactiva el checkbox de jefe
        $('#esJefeCreate').prop('checked', false);
        $('#esJefeCreate').prop('disabled', false);
      } else {
        // Si se desmarca el checkbox de actividad, habilita el checkbox de jefe
        $('#esJefeCreate').prop('disabled', true);
        $('#esJefeCreate').prop('checked', false);
      }
    });
    // Controlador para el modal
    $('#show2').on('show.bs.modal', function (event) {
      // Actualizar opciones de selección
      updateSelectOptions();
    });
  });

  function updateSelectOptions() {
    $.get('selectAreasTurnos/',function(data){
      var html_select = '<option value="">Seleccione </option>'
      for(var i = 0; i<data[0].length; i ++){
        for(var k = 0; k<data[1].length; k ++){
          var bandera = false;
          for(var j = 0; j<data[2].length; j ++){
            if(data[0][i].id_a == data[2][j].area && data[1][k].id == data[2][j].turno && idJefe == data[2][j].jefe){
              bandera = true;
            }
          }
          if(!bandera){
            var ids = idJefe+"-"+data[0][i].id_a+"-"+data[1][k].id;
            html_select += '<option value="'+ids+'">' + data[0][i].nombre_a + ' - ' + data[1][k].nombre + '</option>';          
          }
        }
      }
      $('#nuevoPermiso').html(html_select);
    });
  } 

  function fnEliminarJefeXArea(idJA, idJefe) {
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/deleteAreaXJefe/" + idJA,
      type: 'GET',
      success: function (data) {
        // Llamar a la función que actualiza el contenido del modal
        actualizarContenidoModal(idJefe);
      },
    });
  }

  function fnAgregarJefeXArea() {
    var selectedValue = document.getElementById('nuevoPermiso').value;
    if (selectedValue === "") {
      return; // Salir de la función si no hay una opción seleccionada
    }

    $('#saveButton').prop('disabled', true);

    var parts = selectedValue.split('-');
    var jefeId = parts[0];
    var areaId = parts[1];
    var turnoId = parts[2];

    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/storeRelacionJefeXArea/" + jefeId + "/" + areaId + "/" + turnoId,
      type: 'GET',
      success: function (data) {
        // Llamar a la función que actualiza el contenido del modal
        actualizarContenidoModal(jefeId);
      },
    });
  }

  function actualizarContenidoModal(idJefe) {
    // Realizar una nueva solicitud AJAX para obtener el contenido actualizado de la tabla
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/obtenerNuevoListadoAreaXJefe/" + idJefe, 
      type: 'GET',
      success: function (data) {
        // Actualizar el contenido del modal con los nuevos datos
        $("#modalshow").html(data);
        updateSelectOptions();
      },
    });
  }
  /*function fnOpenModalAgregarRelacion(id) {
    var myModal3 = new bootstrap.Modal(document.getElementById('show3'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/showStoreAreaXJefe/" + id,
      type: 'GET',
      success: function(data) {
        // Borrar contenido anterior
        $("#modalshow3").empty();
        // Establecer el contenido del modal
        $("#modalshow3").html(data);

        // Borrar contenido anterior
        $("#modalfooter3").empty();

        // Agregar el botón "Cerrar y Guardar" al footer
        $("#modalfooter3").append(saveButton);
        $("#modalfooter3").append(closeButton2);

        // Mostrar el modal
        myModal3.show();

        // Cambiar el tamaño del modal a "modal-lg"
        var modalDialog = myModal3._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.add('modal-lg');

      },
    });
  }*/

  $('#editar_empleado').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var id = button.data('id')
    var nombre = button.data('nombre')
    var apellido = button.data('apellido')
    var area = button.data('area')
    var idTurno = button.data('turno')
    var dni = button.data('dni')
    var interno = button.data('interno')
    var fe_nac = button.data('fe_nac')
    var fe_ing = button.data('fe_ing')
    var correo = button.data('correo')
    var activo = button.data('activo')
    var jefe = button.data('jefe')
    var actividad = $('#actividad');
    var esJefe = $('#esJefe');
    var modal = $(this)
    
    actividad.prop('checked', activo == 1);
    esJefe.prop('checked', jefe == 1);
    modal.find('.modal-body #id_p').val(id);
    modal.find('.modal-body #nombre_p').val(nombre);
    modal.find('.modal-body #apellido').val(apellido);
    modal.find('.modal-body #dni').val(dni);
    modal.find('.modal-body #interno').val(interno);
    modal.find('.modal-body #fe_nac').val(fe_nac);
    modal.find('.modal-body #fe_ing').val(fe_ing);
    modal.find('.modal-body #correo').val(correo);

    var actividadCheckbox = $('#actividad');
    var jefeCheckbox = $('#esJefe');

    var actividadChecked = actividadCheckbox.prop('checked');
    var jefeChecked = jefeCheckbox.prop('checked');

    if (actividadChecked && jefeChecked) {
      $('#actividad').prop('checked', true);
      $('#esJefe').prop('checked', true);
      $('#esJefe').prop('disabled', false);
    } else if(actividadChecked && !jefeChecked) {
      $('#actividad').prop('checked', true);
      $('#esJefe').prop('checked', false);
      $('#esJefe').prop('disabled', false);
    }else if(!actividadChecked && jefeChecked) {
      console.log("jefe");
      $('#actividad').prop('checked', false);
      $('#esJefe').prop('checked', false);
      $('#esJefe').prop('disabled', true);
    }else if(!actividadChecked && !jefeChecked) {
      console.log("ninguno");
      $('#actividad').prop('checked', false);
      $('#esJefe').prop('checked', false);
      $('#esJefe').prop('disabled', true);
    }

    $.get('selectAreaEmpleados/',function(data){
      var html_select = '<option value="">Seleccione </option>'
      for(var i = 0; i<data.length; i ++){
        if(data[i].id_a == area){
          html_select += '<option value ="'+data[i].id_a+'"selected>'+data[i].nombre_a+'</option>';
        }else{
          html_select += '<option value ="'+data[i].id_a+'">'+data[i].nombre_a+'</option>';
        }
      }
      $('#select_area').html(html_select);
    });
    $.get('selectTurnosEmpleados/',function(data){
      var html_select = '<option value="">Seleccione </option>'
      for(var i = 0; i<data.length; i ++){
        if(data[i].id == idTurno){
          html_select += '<option value ="'+data[i].id+'"selected>'+data[i].nombre+'</option>';
        }else{
          html_select += '<option value ="'+data[i].id+'">'+data[i].nombre+'</option>';
        }
      }
      $('#turnoEdit').html(html_select);
    });
    
    // Asigna el evento change al checkbox de actividad
    $('#actividad').on('change', function(event) {
      handleActividadChange(event);
    });
    function handleActividadChange(event) {
      // Si event está definido, obten el checkbox de actividad
      var actividadCheckbox = event ? $(event.target) : $('#actividad');
      if (actividadCheckbox.prop('checked')) {
        // Si se marca el checkbox de actividad, desactiva el checkbox de jefe
        $('#esJefe').prop('checked', false);
        $('#esJefe').prop('disabled', false);
      } else {
        // Si se desmarca el checkbox de actividad, habilita el checkbox de jefe
        $('#esJefe').prop('disabled', true);
        $('#esJefe').prop('checked', false);
      }
    }
  })
</script>

<script> 
  $("document").ready(function(){
    setTimeout(function(){
      $("div.alert").fadeOut();
    }, 5000 ); // 5 secs
  });
</script>

<!--<script>
 $(document).ready(function(){
   $("#search").keyup(function(){
     _this = this;
     $.each($("#test tbody tr"), function() {
       if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
         $(this).hide();
       else
         $(this).show();
     });
   });
 });
</script>-->


<script>
  $(document).ready(function(){
    $('#alert').hide();
    $('.btn-borrar').click(function(e){
        e.preventDefault();
        if(! confirm("¿Está seguro de eliminar?")){
            return false;
        }
        var row = $(this).parents('tr');
        var form = $(this).parents('form');
        var url  = form.attr('action');       
        
        $.get(url, form.serialize(),function(result){
            row.fadeOut();
            $('#alert').show();
            $('#alert').html(result.message)
            setTimeout(function(){ $('#alert').fadeOut();}, 5000 );
        }).fail(function(){
            $('#alert').show();
            $('#alert').html("Algo salió mal");
        });
    });
  });
</script>

<!--<script> //filtro check boxs
$(document).ready(function () { 
  $("#search").keyup(function () {
    filterTable();
  });

  $("#filtroJefe, #filtroActividad").change(function () {
    filterTable();
  });
});

function filterTable() {
  var searchText = $("#search").val().toLowerCase();
  var filtroJefe = $("#filtroJefe").prop("checked");
  var filtroActividad = $("#filtroActividad").prop("checked");

  $("#test tbody tr").each(function () {
    var nombre = $(this).find("td:eq(0)").text().toLowerCase();
    var esJefe = $(this).find("td:eq(6) .circle_green").length > 0;
    var enActividad = $(this).find("td:eq(7) .circle_green").length > 0;

    var mostrar = true;

    if (filtroJefe && !esJefe) {
      mostrar = false;
    }

    if (filtroActividad && !enActividad) {
      mostrar = false;
    }

    if (mostrar && nombre.indexOf(searchText) === -1) {
      mostrar = false;
    }

    if (mostrar) {
      $(this).show();
    } else {
      $(this).hide();
    }
  });
}

</script>-->
<script>
  $(document).ready(function(){
    // Filtros y búsqueda
    
    function filterTable() {
      var searchText = $("#search").val().toLowerCase();
      var filtroJefe = $("#filtroJefe").prop("checked");
      var filtroActividad = $("#filtroActividad").prop("checked");

      $("#test tbody tr").each(function () {
        var nombre = $(this).find("td:eq(0)").text().toLowerCase();
        var esJefe = $(this).find("td:eq(6) .circle_green").length > 0;
        var enActividad = $(this).find("td:eq(7) .circle_green").length > 0;

        var mostrar = true;

        if (filtroJefe && !esJefe) {
          mostrar = false;
        }

        if (filtroActividad && !enActividad) {
          mostrar = false;
        }

        if (mostrar && nombre.indexOf(searchText) === -1) {
          mostrar = false;
        }

        if (mostrar) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    }
  });
</script>

@endsection

