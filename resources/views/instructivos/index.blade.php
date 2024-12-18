@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- alertas -->
<div id="documentos-instructivos-container" class="container-fluid">
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
  <button class="btn btn-info" onclick='fnOpenModalStore()' data-toggle="modal" data-target="#agregar"
    id="btn-agregar"> Agregar</button>

  <!-- barra para buscar instructivos -->
  <div>
    <div class="form-group">
      <form method="GET">
        <div style="display: inline-block;">
          <label for="id" style="display: block; margin-bottom: 5px;">
            <h6><b>ID:</b></h6>
          </label>
          <input type="text" class="form-control" name="id_instructivo" id="id_instructivo" autocomplete="off"
            value="{{$id_instructivo}}">
        </div>
        <div style="display: inline-block;">
          <label for="titulo" style="display: block; margin-bottom: 5px;">
            <h6><b>Titulo:</b></h6>
          </label>
          <input type="text" class="form-control" name="titulo" id="titulo" autocomplete="off" value="{{$titulo}}">
        </div>
        <div style="display: inline-block;">
          <label for="tipo" style="display: block; margin-bottom: 5px;">
            <h6><b>Tipo:</b></h6>
          </label>
          <select class="form-control" name="id_tipo_instructivo" id="id_tipo_instructivo">
            <option value="0">{{'Todos'}} </option>
            @foreach($tiposInstructivos as $tipoInstructivo)
        @if($tipoInstructivo->id == $id_tipo_instructivo)
      <option value="{{$tipoInstructivo->id}}" selected>{{$tipoInstructivo->nombre}} </option>
    @else
    <option value="{{$tipoInstructivo->id}}">{{$tipoInstructivo->nombre}} </option>
  @endif
      @endforeach
          </select>
        </div>
        &nbsp
        <div style="display: inline-block;">
          <button type="submit" class="btn btn-default" id="asignar-btn"> Buscar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- tabla de datos -->
  <div>
    <table>
      <thead>
        <th class="text-center">ID</th>
        <th class="text-center">Título</th>
        <th class="text-center">Tipo</th>
        <th class="text-center">Acciones</th>
      </thead>
      <tbody>
        @if(count($instructivos))
      @foreach($instructivos as $instructivo)
      <tr>
      <td width="60">{{$instructivo->id}}</td>
      <td width="500">{{$instructivo->titulo}}</td>
      <td width="60">{{$instructivo->tipo}}</td>
      <td width="100">
      <div class="text-center">
        <!-- Boton de descargar archivo -->
        @if($instructivo->archivo != null)
      <a href="{{ Storage::url($instructivo->archivo)}}" title="Descargar Archivo" data-position="top"
      data-delay="50" data-tooltip="Descargar Archivo" download><img
      src="{{ asset('storage/cursos/descargar.png') }}" alt="Descargar" id="img-icono"></a>
    @else
    <a data-position="top" data-delay="50" download title="Descargar"><img
    src="{{ asset('storage/cursos/descargar.png') }}" alt="Descargar" id="img-icono"></a>
  @endif
        <!-- Boton de editar archivo -->
        @can('editar-instructivo')
      <button id="edit-{{$instructivo->id}}" onclick='fnOpenModalUpdate({{$instructivo->id}})'
      data-tipo="{{$instructivo->tipo}}" data-titulo="{{$instructivo->titulo}}" data-id="{{$instructivo->id}}"
      title="Editar" style="  border: none;background: none;padding: 0; cursor: pointer;">
      <img src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono"></button>
    @endcan
        <!-- Boton de eliminar archivo -->
        @can('eliminar-instructivo')
      <a href="{{url('destroy_instructivo', $instructivo->id)}}" class="btn btn-danger btn-sm" title="Eliminar"
      onclick="return confirm ('Está seguro que desea eliminar el archivo?')" data-position="top"
      data-delay="50" data-tooltip="Borrar" id="icono"><img src="{{ asset('storage/cursos/eliminar.png') }}"
      alt="Eliminar" id="img-icono"></a>
    @endcan
      </div>
      </td>
      </tr>
    @endforeach
    @endif
      </tbody>
    </table>
    {{ $instructivos->appends($_GET)->links() }}
  </div>

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

</div>



<!-- Duracion de alerta (agregado, elimnado, editado) -->
<script>
  $("instructivos").ready(function () {
    setTimeout(function () {
      $("div.alert").fadeOut();
    }, 3000); // 3 secs

  });
</script>

<script>
  var ruta_update = '{{ route('update_instructivo') }}';
  var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cerrar</button> ');
  var saveButton = $('<button type="submit" class="btn btn-info" id="asignar-btn">Guardar</button>');

  //modal update
  function fnOpenModalUpdate(id) {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    var ip = document.getElementById('edit-' + id).getAttribute('data-id');
    var tipo = document.getElementById('edit-' + id).getAttribute('data-tipo');
    var titulo = document.getElementById('edit-' + id).getAttribute('data-titulo');

    // Ocultar el modal temporalmente
    myModal.hide();

    // Mostrar mensaje de carga o indicador de progreso
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_update_instructivo/" + id,
      type: 'GET',
      success: function (data) {
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

        $('#tituloUpdate').val(titulo);
        $('#id').val(id);

        // Construir el select
        $.get('select_tipo_instructivos/', function (data) {
          var html_select = '<option value="">Seleccione</option>';

          for (var i = 0; i < data.length; i++) {
            if (data[i].nombre == tipo) {
              html_select += '<option value="' + data[i].id + '" selected>' + data[i].nombre + '</option>';
            } else {
              html_select += '<option value="' + data[i].id + '">' + data[i].nombre + '</option>';
            }
          }

          // Establecer el select completo en el modal
          $('#tipo_instructivo').html(html_select);

          // Mostrar el modal una vez que el select esté listo
          myModal.show();

          // Cambiar el tamaño del modal a "modal-lg"
          var modalDialog = myModal._element.querySelector('.modal-dialog');
          modalDialog.classList.remove('modal-sm');
          modalDialog.classList.add('modal-lg');

          //para cerrar modales
          closeButton.on('click', function () {
            myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
          });
        });
      },
    });
  }
</script>
<script>
  var ruta_store = '{{ route('store_instructivo') }}';
  var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cerrar</button>');
  var saveButton = $('<button type="submit" class="btn btn-info" id="asignar-btn">Guardar</button>');

  //modal store
  function fnOpenModalStore() {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    var url = window.location.origin + "/show_store_instructivo/";
    $.get(url, function (data) {
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

      // Construir el select
      $.get('select_tipo_instructivos/', function (data) {
        var html_select = '<option value="">Seleccione</option>';

        for (var i = 0; i < data.length; i++) {
          html_select += '<option value="' + data[i].id + '">' + data[i].nombre + '</option>';
        }

        // Establecer el select completo en el modal
        $('#tipo_instructivo').html(html_select);

        // Mostrar el modal
        myModal.show();

        // Cambiar el tamaño del modal a "modal-lg"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.remove('modal-lg');

        //para cerrar modales
        closeButton.on('click', function () {
          myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
        });
      });
    });
  }


</script>