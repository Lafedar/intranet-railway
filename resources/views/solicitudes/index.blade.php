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
<div class="col">
  <p></p>
  <div class="form-group">
    <form  method="GET">
      <div style="display: inline-block;">
        <label for="id_solicitud" style="display: block; margin-bottom: 5px;"><h6>ID:</h6></label>
        <input type="text" class="form-control" name="id_solicitud" id="id_solicitud" autocomplete="off" value="{{$id_solicitud}}">
      </div>
      <div style="display: inline-block;">
        <label for="titulo" style="display: block; margin-bottom: 5px;"><h6>Titulo:</h6></label>
        <input type="text" class="form-control" name="titulo" id="titulo" autocomplete="off" value="{{$titulo}}">
      </div>
      <div style="display: inline-block;">
        <label for="tipo" style="display: block; margin-bottom: 5px;"><h6>Tipo:</h6></label>
        <select class="form-control" name="id_tipo_solicitud"  id="id_tipo_solicitud">
          <option value="0">{{'Todos'}} </option>
          @foreach($tiposSolicitudes as $tipoSolicitud)
            @if($tipoSolicitud->id == $id_tipo_solicitud)
              <option value="{{$tipoSolicitud->id}}" selected>{{$tipoSolicitud->nombre}} </option>
            @else
              <option value="{{$tipoSolicitud->id}}">{{$tipoSolicitud->nombre}} </option>
            @endif
          @endforeach
        </select>
      </div>
      <div style="display: inline-block;">
        <label for="id_equipo" style="display: block; margin-bottom: 5px;"><h6>Equipo:</h6></label>
        <input type="text" class="form-control" name="id_equipo" id="id_equipo" autocomplete="off" value="{{$id_equipo}}">
      </div>
      <div style="display: inline-block;">
        <label for="estado" style="display: block; margin-bottom: 5px;"><h6>Estado:</h6></label>
        <select class="form-control" name="id_estado"  id="id_estado">
          <option value="0">{{'Todos'}} </option>
          @foreach($estados as $estado)
            @if($estado->id == $id_estado)
              <option value="{{$estado->id}}" selected>{{$estado->nombre}} </option>
            @else
              <option value="{{$estado->id}}">{{$estado->nombre}} </option>
            @endif
          @endforeach
        </select>
      </div>
      <div style="display: inline-block;">
        <label for="solicitante" style="display: block; margin-bottom: 5px;"><h6>Solicitante:</h6></label>
        <select class="form-control" name="id_solicitante"  id="id_solicitante">
          <option value="0">{{'Todos'}} </option>
          @foreach($usuarios as $usuario)
            @if($usuario->id == $id_solicitante)
              <option value="{{$usuario->id}}" selected>{{$usuario->name}} </option>
            @else
              <option value="{{$usuario->id}}">{{$usuario->name}} </option>
            @endif
          @endforeach
        </select>
      </div>
      <div style="display: inline-block;">
        <label for="encargado" style="display: block; margin-bottom: 5px;"><h6>Encargado:</h6></label>
        <select class="form-control" name="id_encargado"  id="id_encargado">
          <option value="0">{{'Todos'}} </option>
          @foreach($usuarios as $usuario)
            @foreach($model_as_roles as $model_as_rol)
              @if($model_as_rol->role_id == 22 and $usuario->id == $model_as_rol->model_id)
                @if($usuario->id == $id_encargado)
                  <option value="{{$usuario->id}}" selected>{{$usuario->name}} </option>
                @else
                  <option value="{{$usuario->id}}">{{$usuario->name}} </option>
                @endif
              @endif
            @endforeach
          @endforeach
        </select>
      </div>
      &nbsp
      <div style="display: inline-block;">
        <button type="submit" class="btn btn-default"> Buscar</button>
      </div>
    </form>          
  </div>
</div>
<!-- tabla de datos -->
<div class="col-md-12">             
  <table class="table table-striped table-bordered ">
    <thead>
      <th class="text-center"><input type="checkbox" id="checkAll" onclick="checkAll()"> Seleccionar</th>
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
              <td><label><input type="checkbox" id="cbox1" value="first_checkbox"></label><br></td>
              <td width="60">{{$solicitud->id}}</td>
              <td width="350">{{$solicitud->titulo}}</td>
              <td width="150">{{$solicitud->tipo_solicitud}}</td>
              <td width="107">{{$solicitud->id_equipo}}</td>
              <td >{{$solicitud->estado}}</td>
              <td >{{$solicitud->falla}}</td>
              <td hidden>{{$solicitud->descripcion}}</td>             
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


  {{ $solicitudes->appends($_GET)->links() }}
</div>
<!-- Incluir archivos CSS de Select2 -->
<link href="{{ asset('select2/dist/css/select2.min.css') }}" rel="stylesheet" />
<script src="{{ asset('select2/dist/js/select2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script>


  function manejarSeleccion(idEquipo) {
    $('#equipo').val(idEquipo).trigger('change');
  }

  var ruta = '{{ route('mostrar_equipos_mant') }}';
  var closeButton3 = $('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');
  //modal store
  function fnOpenModalShowEquipos() {
    var myModal3 = new bootstrap.Modal(document.getElementById('show3'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_mostrar_equipos_mant/",
      type: 'GET',
      success: function(data) {
        // Borrar contenido anterior
        $("#modalshow3").empty();
        // Establecer el contenido del modal
        $("#modalshow3").html(data);

        // Borrar contenido anterior
        $("#modalfooter3").empty();

        // Agregar el botón "Cerrar" al footer del modal interno
        $("#modalfooter3").append(closeButton3);

        // Agregar listener al botón "Cerrar" del modal secundario
        closeButton.click(function(event) {
          event.stopPropagation();
          myModal3.hide();
        });

        // Mostrar el modal
        myModal3.show();

        var modalDialog = myModal3._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.add('modal-lg');
        modalDialog.style.width = '100%'; // Añade esta línea
        modalDialog.style.maxWidth = '100%'; // Añade esta línea
      },
    });
  }
  
  var ruta_create = '{{ route('store_solicitud') }}';
  var ruta_update = '{{ route('update_solicitud') }}';
  var ruta_assing = '{{ route('assing_solicitud') }}';
  var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');
  var saveButton = $('<button type="submit" class="btn btn-info">Guardar</button>');
  //modal store
  function fnOpenModalStore() {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    var url = window.location.origin + "/show_store_solicitud/";
    var closeButton2 = $('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');
    $.get(url, function(data) {
      // Borrar contenido anterior
      $("#modalshow").empty();

      // Establecer el contenido del modal
      $("#modalshow").html(data);

      // Borrar contenido anterior
      $("#modalfooter").empty();

      // Agregar el botón "Cerrar y Guardar" al footer
      $("#modalfooter").append(closeButton2);
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
        var divDescripcion = $('#div_descripcion')
        divDescripcion.hide();
        var htmlSelectArea = '<option value="">Seleccione </option>'
        var htmlSelectLocalizacion = '<option value="">Seleccione </option>'
        var htmlSelectTipoSolicitud = '<option value="">Seleccione </option>'
        var htmlSelectEquipo = '<option value="">Seleccione </option>'
        var htmlSelectFalla = '<option value="">Seleccione </option>'
        var htmlDescripcionEquipo = ''
        // [0]=areas [1]=localizaciones [2]=tipo_solicitudes [3]=equipos_mant 
        // [4]=fallas [5]=tipos_equipos [6]=fallasxtipo

        htmlSelectArea += data[0].map(item => `<option value="${item.id_a}">${item.nombre_a}</option>`).join('');
        htmlSelectTipoSolicitud += data[2].map(tipo_solicitud => `<option value="${tipo_solicitud.id}">${tipo_solicitud.nombre}</option>`).join('');
        htmlSelectEquipo += data[3].map(equipo => `<option value="${equipo.id}">${equipo.id}</option>`).join('');

        var tipoSolicitudSelected;
        var equipoSelected;
        var areaSelected;

        $("#equipo").select2();
        $('#equipo').html(htmlSelectEquipo);
        $('#tipo_solicitud').html(htmlSelectTipoSolicitud);
        $('#area').html(htmlSelectArea);
        $('#localizacion').html(htmlSelectLocalizacion);

        $('#tipo_solicitud').on('change', function () {
          tipoSolicitudSelected = $(this).val();
          const divEquipo = $('#div_equipo');
          const divFalla = $('#div_falla');

          if (!tipoSolicitudSelected) {
            divEquipo.show();
            divFalla.hide();
          } 
          else if (tipoSolicitudSelected == 1) {
            divEquipo.show();
            divFalla.hide();
          } 
          else {
            divEquipo.hide();
            divFalla.show();
            divDescripcion.hide();
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
          $('#area').prop('disabled', false);
          $('#localizacion').prop('disabled', false);
          $('#descripcion_equipo').prop('disabled', false);
        }); 

        $('#equipo').on('change', function () {
          var equipoSelected = $(this).val();
          if (!equipoSelected) {
            $('#div_falla').hide();
            $('#div_descripcion').hide();
            var htmlSelectFalla = '<option value="">Seleccione </option>'
            $('#area').prop('disabled', false);
            $('#localizacion').prop('disabled', false);
          } else {
            var htmlSelectFalla = '<option value="">Seleccione </option>'
            for (var k = 0; k < data[3].length; k++) {
              if (equipoSelected == data[3][k].id) {
                for (var j = 0; j < data[6].length; j++) {
                  if (data[3][k].id_tipo == data[6][j].id_tipo_equipo) {
                    for (var i = 0; i < data[4].length; i++) {
                      if (data[6][j].id_falla == data[4][i].id) {
                        htmlSelectFalla += '<option value ="' + data[6][j].id_falla + '">' + data[4][i].nombre + '</option>';
                      }
                    }
                  }
                }
                var aux_tipo_equipo = data[3][k].id_tipo;
                // Obtener el id_area y id_localizacion del equipo seleccionado
                var idAreaEquipo = data[3][k].id_area;
                var idLocalizacionEquipo = data[3][k].id_localizacion;
                var htmlDescripcionEquipo = data[3][k].descripcion;
                // Establecer el valor de id_area en el select de área
                $('#descripcion_equipo').val(htmlDescripcionEquipo).trigger('change');
                $('#area').val(idAreaEquipo).trigger('change');
                // Establecer el valor de id_localizacion en el select de localización, o seleccionar la opción vacía si es nulo
                if (idLocalizacionEquipo) {
                  $('#localizacion').val(idLocalizacionEquipo).trigger('change');
                } else {
                  // Agregar la opción "No aplica" en el select de localización
                  $('#localizacion').append('<option value="0">No aplica</option>');
                  $('#localizacion').val('0').trigger('change');
                }
                $('#area').prop('disabled', true);
                $('#localizacion').prop('disabled', true);
                $('#descripcion_equipo').prop('disabled', true);
              }
            }
            $('#falla').html(htmlSelectFalla);
            $('#tipo_solicitud').val('1');
            $('#div_localizacion').show();
            $('#div_falla').show();
            $('#div_descripcion').show();
          }
        });

        $('#area').on('change', function () {
          areaSelected = $(this).val();

          // Obtener las localizaciones correspondientes al área seleccionada y agregarlas al select correspondiente
          let htmlSelectLocalizacion = '<option value="">Seleccione</option>';
          data[1].forEach(localizacion => {
            if (localizacion.id_area == areaSelected) {
              htmlSelectLocalizacion += `<option value="${localizacion.id}">${localizacion.nombre}</option>`;
            }
          });

          if(!areaSelected){
            $('#div_localizacion').hide();
          } else{
            $('#div_localizacion').show();
            $('#localizacion').html(htmlSelectLocalizacion);
          }
        });
      });
    });
  }

  function checkAll() {
    // Obtén el estado actual del checkbox "checkAll"
    var checkAllCheckbox = document.getElementById("checkAll");
    var isChecked = checkAllCheckbox.checked;

    // Obtén todos los checkboxes generados por el bucle
    var checkboxes = document.querySelectorAll("input[type='checkbox'][id^='cbox1']");

    // Marca o desmarca todos los checkboxes según el estado del checkbox "checkAll"
    for (let i = 0; i < checkboxes.length; i++) {
      checkboxes[i].checked = isChecked;
    }

    // Si alguno de los checkboxes generados se desmarca, desmarca también el checkbox "checkAll"
    for (let i = 0; i < checkboxes.length; i++) {
      checkboxes[i].addEventListener("change", function() {
        if (!this.checked) {
          checkAllCheckbox.checked = false;
        }
      });
    }

    checkAllCheckbox.addEventListener("change", function() {
      for (let i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = this.checked;
      } 
    });

    for (let i = 0; i < checkboxes.length; i++) {
      checkboxes[i].addEventListener("change", function() {
        var allChecked = true;
        for (let j = 0; j < checkboxes.length; j++) {
          if (!checkboxes[j].checked) {
            allChecked = false;
            break;
          }
        }
        checkAllCheckbox.checked = allChecked;
      });
    }
  }
  function Report() {
    // Obtener todos los checkboxes seleccionados
    var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked:not(#checkAll)');

    // Si no hay ningún checkbox seleccionado, mostrar un mensaje y salir de la función
    if (checkboxes.length === 0) {
      alert("Por favor, seleccione al menos una solicitud.");
      return;
    }

    // Crear un nuevo documento PDF
    var doc = new jsPDF('p', 'mm', 'a4');
    // Definir la variable pageHeight
    var pageHeight = doc.internal.pageSize.height;

    // Agregar el título al PDF 
    doc.setFontSize(14);
    doc.setFontStyle("bold");
    doc.text("Solicitudes seleccionadas", 10, 10);
    doc.setLineWidth(0.5); // Establecer el grosor del subrayado
    doc.line(10, 12, 72, 12); // Dibujar una línea debajo del texto

    // Agregar las solicitudes seleccionadas al PDF
    var y = 20;
    for (var i = 0; i < checkboxes.length; i++) {
      var checkbox = checkboxes[i];
      var row = checkbox.closest('tr');
      var id = row.querySelector('td:nth-child(2)').textContent.trim();
      var titulo = row.querySelector('td:nth-child(3)').textContent.trim();
      var equipo = row.querySelector('td:nth-child(5)').textContent.trim();
      var estado = row.querySelector('td:nth-child(6)').textContent.trim();
      var falla = row.querySelector('td:nth-child(7)').textContent.trim();
      var descripcion = row.querySelector('td:nth-child(8)').textContent.trim();

      // Ajustar el diseño del contenido del PDF
      var content = [
        { label: "ID: ", value: id, x: 10, y: y },
        { label: "Título: ", value: titulo, x: 50, y: y },
        { label: "Equipo: ", value: equipo, x: 10, y: y + 5 },
        { label: "Estado: ", value: estado, x: 50, y: y + 5 },
        { label: "Falla: ", value: falla, x: 110, y: y + 5 },
        { label: "Descripción: ", value: descripcion, x: 10, y: y + 10 }
      ];

      doc.setFontSize(10);
      doc.setFontStyle("normal");
      var lineHeight = 5; // Altura de línea
      var totalHeight = 0;
      content.forEach(function(item) {
        doc.setFontStyle("bold"); // Establecer el estilo en negrita
        doc.text(item.label, item.x, item.y);
        // Obtener la longitud del label en unidades del PDF
        var labelWidth = doc.getStringUnitWidth(item.label) * doc.internal.getFontSize() / doc.internal.scaleFactor; 
        // Verificar si el valor es null o undefined y asignar "N/A" en su lugar
        var value = (item.value !== null && item.value !== undefined && item.value.trim() !== '') ? item.value : "N/A";

        doc.setFontStyle("normal"); // Restaurar el estilo a normal para el valor

        // Dividir el valor de descripción en varias líneas si es necesario
        var lines = doc.splitTextToSize(value, 160); // 160 es el ancho máximo de la descripción

        // Mostrar la descripción en la posición correcta
        lines.forEach(function(line) {
          doc.text(line, item.x + labelWidth, item.y);
          item.y += 3; // Incrementar la posición Y para la próxima línea
          y += 3;
        });

        // Calcular la altura total de la descripción en unidades del PDF
        totalHeight = lines.length * lineHeight; 

      });
      // Verificar si es necesario hacer un salto de página
      if (y + totalHeight + lineHeight > pageHeight) {
        doc.addPage(); // Agregar una nueva página al PDF
        y = 10; // Establecer la posición Y al inicio de la página
        totalHeight = 0; // Reiniciar la altura total de la descripción en la nueva página
      }
      // Agregar una línea divisoria al final de la solicitud
      doc.setLineWidth(0.5);
      doc.setDrawColor(0, 0, 0);
      doc.line(10, y - 5, 200, y - 5);
    }
    // Guardar el PDF
    doc.save('reporte.pdf');
  }

  $(document).ready(function(){
    $("#id").keyup(function(){
      _this = this;
      $.each($("#test tbody tr"), function() {
        if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
          $(this).hide();
        else
          $(this).show();
      });
    });
  });

  //Duracion de alerta (agregado, elimnado, editado)
  $("solicitud").ready(function(){
    setTimeout(function(){
      $("div.alert").fadeOut();
    }, 5000 ); // 5 secs

  });

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