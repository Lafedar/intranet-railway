@extends('layouts.app')
@push('styles')

    <link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="{{ asset('select2/dist/css/select2.min.css') }}" rel="stylesheet" />
@endpush

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> 

@section('content')

<!-- alertas -->
<div id="solicitudes-container">
  <div class="content">
    <div class="row" style="justify-content: center">
      <div id="alert" class="alert alert-success col-md-10 text-center" style="display: none"></div>
    </div>
  </div>

  @if(Session::has('message'))
    <div class="container" id="div.alert">
    <div class="row">
      <div class="col-1"></div>
      <div class="alert {{ Session::get('alert-class') }} col-10 text-center" role="alert">
      {!! Session::get('message') !!}
      </div>
    </div>
    </div>
  @endif

  @if(session('correo_enviado'))
    <div class="alert alert-success text-center" role="alert">
    ¡El correo fue enviado correctamente!
    </div>
  @endif
  <div id="solicitudes-btn">
    @can('reporte-solicitudes')
    <button  class="btn btn-info" onclick='Report()' title="report" id="btn-agregar">Reporte</button>
  @endcan

    <button class="btn btn-info" onclick='fnOpenModalStore()' data-toggle="modal" data-target="#agregar_solicitud" id="btn-agregar">
      Agregar solicitud</button>
  </div>

  <!-- barra para buscar solicitudes -->
  <div >
    <div class="form-group">
      <form method="GET">
        <div style="display: inline-block;">
          <label for="id_solicitud" style="display: block; margin-bottom: 5px;">
            <h6>ID:</h6>
          </label>
          <input type="text" class="form-control" name="id_solicitud" id="id_solicitud" autocomplete="off"
            value="{{$id_solicitud}}">
        </div>
        <div style="display: inline-block;">
          <label for="titulo" style="display: block; margin-bottom: 5px;">
            <h6>Titulo:</h6>
          </label>
          <input type="text" class="form-control" name="titulo" id="titulo" autocomplete="off" value="{{$titulo}}">
        </div>
        <div style="display: inline-block;">
          <label for="tipo" style="display: block; margin-bottom: 5px;">
            <h6>Tipo:</h6>
          </label>
          <select class="form-control" name="id_tipo_solicitud" id="id_tipo_solicitud">
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
          <label for="id_equipo" style="display: block; margin-bottom: 5px;">
            <h6>Equipo:</h6>
          </label>
          <input type="text" class="form-control" name="id_equipo" id="id_equipo" autocomplete="off"
            value="{{$id_equipo}}">
        </div>
        <div style="display: inline-block;">
          <label for="estado" style="display: block; margin-bottom: 5px;">
            <h6>Estado:</h6>
          </label>
          <select class="form-control" name="id_estado" id="id_estado">
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
          <label for="solicitante" style="display: block; margin-bottom: 5px;">
            <h6>Solicitante:</h6>
          </label>
          <select class="form-control" name="id_solicitante" id="id_solicitante">
            <option value="0">{{'Todos'}} </option>
            @foreach($usuarios as $usuario)
        @if($usuario->idPersona == $id_solicitante)
      <option value="{{$usuario->idPersona}}" selected>{{$usuario->name}} </option>
    @else
    <option value="{{$usuario->idPersona}}">{{$usuario->name}} </option>
  @endif
      @endforeach
          </select>
        </div>
        <div style="display: inline-block;">
          <label for="fecha" style="display: block; margin-bottom: 5px;">
            <h6>Fecha:</h6>
          </label>
          <input class="form-control" type="date" id="fecha" name="fecha">
        </div>

        <div style="display: inline-block;">
          <label for="encargado" style="display: block; margin-bottom: 5px;">
            <h6>Encargado:</h6>
          </label>
          <select class="form-control" name="id_encargado" id="id_encargado">
            <option value="0">{{'Todos'}} </option>
            @foreach($encargados as $encargado)
        <option value="{{$encargado->idPersona}}" @if($encargado->idPersona == $id_encargado) selected @endif>
          {{$encargado->name}}
        </option>
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
    <table class="table table-striped table-bordered ">
      <thead>
        @can('reporte-solicitudes')
      <th class="text-center"><input type="checkbox" id="checkAll" onclick="checkAll()"> Seleccionar</th>
    @endcan
        <th class="text-center">ID</th>
        <th class="text-center">Titulo</th>
        <th class="text-center">Tipo de solicitud</th>
        <th class="text-center">Equipo</th>
        <th class="text-center">Estado</th>
        <th class="text-center">Tipo de falla</th>
        <th class="text-center">Fecha de emision</th>
        <th class="text-center">Solicitante</th>
        <th class="text-center">Encargado</th>
        <th class="text-center">Acciones</th>
      </thead>
      <tbody>
        <?php //dd($solicitudes); ?>
        @foreach($solicitudes as $solicitud)
        <tr>
          @can('reporte-solicitudes')
        <td><label><input type="checkbox" id="cbox1" value="first_checkbox"></label><br></td>
      @endcan
          <td>{{$solicitud->id}}</td>
          <td>{{$solicitud->titulo}}</td>
          <td>{{$solicitud->tipo_solicitud}}</td>
          <td>
          @if($solicitud->id_equipo)
        <p>{{$solicitud->id_equipo}}</p>
      @else
      <p style="color:gainsboro">N/A</p>
    @endif
          </td>
          <td>{{$solicitud->estado}}</td>
          <td>
          @if($solicitud->falla)
        <p>{{$solicitud->falla}}</p>
      @else
      <p style="color:gainsboro">N/A</p>
    @endif
          </td>
          <td>{{ \Carbon\Carbon::parse($solicitud->fechaEmision)->format('d/m/Y') }}</td>
          @if($solicitud->fechaFinalizacion)
        <!--<td>{{ \Carbon\Carbon::parse($solicitud->fechaFinalizacion)->format('d/m/Y') }}</td>  -->
      @else
      <!--<td></td>  -->
    @endif
          <td>{{$solicitud->nombre_solicitante}} {{$solicitud->apellido_solicitante}}</td>
          <td style="display: none;">{{$solicitud->descripcion}}</td>
          <td>
          @if($solicitud->nombre_encargado)
        {{$solicitud->nombre_encargado}} {{$solicitud->apellido_encargado}}
      @else
      <p style="color:gainsboro">Sin asignar</p>
    @endif

          </td>
          <td>
          <div class="text-center">
            <div class="btn-group" style="display: flex; flex-wrap: wrap; justify-content: center;">

            @php 
        $estado_solicitud = \App\Solicitud::find($solicitud->id)->id_estado; // obtengo el id del estado de cada solicitud
      @endphp
            <div class="btn-container" style="margin-bottom: 5px; margin-right: 5px;">
              <button id="detalle" class="btn btn-info btn-sm" onclick='fnOpenModalShow({{$solicitud->id}})'
              title="show">Detalles</button>
            </div>
            @if($estado_solicitud != 8) <!--verifico que no este cancelada-->

        @can('actualizar-solicitud')
      <div class="btn-container" style="margin-bottom: 5px; margin-right: 5px;">
        <button id="actualizar" class="btn btn-info btn-sm" onclick='fnOpenModalUpdate({{$solicitud->id}})'
        title="update">Actualizar</button>
      </div>
    @endcan

        @can('asignar-solicitud')
      <div class="btn-container" style="margin-bottom: 5px; margin-right: 5px;">
        <button id="asignar" class="btn btn-info btn-sm" onclick='fnOpenModalAssing({{$solicitud->id}})'
        title="assing">Asignar</button>
      </div>
    @endcan

        @if($estado_solicitud == 5 && $solicitud->id_solicitante == $personaAutenticada->id_p)
      <div class="btn-container" style="margin-bottom: 5px; margin-right: 5px;">
        <a href="{{url('aprobar_solicitud', $solicitud->id)}}" class="btn btn-info btn-sm" title="aprobar"
        onclick="return confirm ('Está seguro que desea aprobar esta solicitud?')" data-position="top"
        data-delay="50" data-tooltip="aprobar">Aprobar</a>
      </div>
      <div class="btn-container" style="margin-bottom: 5px; margin-right: 5px;">
        <button id="reclamar" class="btn btn-info btn-sm" onclick='fnOpenModalReclaim({{$solicitud->id}})'
        title="reclaim">Reclamar</button>
      </div>
    @endif

        @if($estado_solicitud == 1 || $estado_solicitud == 2 || $estado_solicitud == 3 || $estado_solicitud == 6 || $estado_solicitud == 7)
      <form action="{{ route('enviar.recordatorio', ['id' => $solicitud->id]) }}" method="post"
        id="recordatorioForm{{$solicitud->id}}">
        @csrf
        <div class="btn-container" style="margin-bottom: 5px; margin-right: 5px;">
        <button type="button" class="btn btn-info btn-sm" onclick="confirmarEnvio({{$solicitud->id}})"
        id="recordatorioBtn{{$solicitud->id}}" data-verificacion="{{ $verificacion ? 'true' : 'false' }}"
        title="Enviar mail de recordatorio a Mantenimiento">Recordatorio</button>
        </div>
      </form>
    @endif

        @can('eliminar-solicitud')
      <div class="btn-container" style="margin-bottom: 5px; ">
        <a href="{{url('destroy_solicitud', $solicitud->id)}}" class="btn btn-danger btn-sm" title="Borrar"
        onclick="return confirm('Está seguro que desea cancelar esta solicitud?')" data-position="top"
        data-delay="50" data-tooltip="Borrar">X</a>
      </div>
    @endcan
      @endif

            </div>
          </div>
          </td>

        </tr>
    @endforeach
      </tbody>
    </table>

    <div class="modal fade" id="show2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
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

    <div class="modal fade" id="show4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog estilo" role="document">
        <div class="modal-content">
          <form id="myForm4" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            <div id="modalshow4" class="modal-body">
              <!-- Datos -->
            </div>
            <div id="modalfooter4" class="modal-footer">
              <!-- Footer -->
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="solicitudes-paginacion">
      {{ $solicitudes->links('pagination::bootstrap-4') }} <!--paginacion-->
    </div>

  </div>
</div>

@endsection
<!-- Incluir archivos CSS de Select2 -->
@push('scripts')
    
<script src="{{ asset('select2/dist/js/select2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script>
  // Obtén el campo de entrada de fecha por su ID
  var fechaInput = document.getElementById('fecha');

  // Verifica si hay un valor guardado en el almacenamiento local (localStorage)
  if (localStorage.getItem('fechaValue')) {
    // Restaura el valor guardado en el campo de entrada de fecha
    fechaInput.value = localStorage.getItem('fechaValue');
  }

  // Escucha el evento 'change' del campo de entrada de fecha
  fechaInput.addEventListener('change', function () {
    // Guarda el valor seleccionado en el almacenamiento local (localStorage)
    localStorage.setItem('fechaValue', fechaInput.value);
  });

</script>
<script>
  window.onload = function () {   //habilita o deshabilita los botones al recargar la pagina 
    var botones = document.querySelectorAll('[id^="recordatorioBtn"]');

    botones.forEach(function (boton) {
      var id = boton.id.replace('recordatorioBtn', '');

      fetch('/verificar-envio-permitido/' + id, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
        .then(response => response.json())
        .then(data => {
          if (!data.envio_permitido) {
            if (data.tiempo_restante > 0) {
              //convertir el tiempo restante en segundos a días, horas y minutos
              var segundos = data.tiempo_restante;
              var dias = Math.floor(segundos / (60 * 60 * 24));
              segundos -= dias * (60 * 60 * 24);
              var horas = Math.floor(segundos / (60 * 60));
              segundos -= horas * (60 * 60);
              var minutos = Math.floor(segundos / 60);

              boton.title = "Recordatorio ya enviado.\nTiempo restante para el proximo: " + dias + " días, " + horas + " horas y " + minutos + " minutos.";
            } else {
              boton.title = "No se pueden enviar correos hasta después de " + data.dias_desbloqueo + " días.";
            }
            boton.dataset.tiempoRestante = data.tiempo_restante;
          }
          boton.disabled = !data.envio_permitido; //deshabilito el botón
        })
        .catch(error => console.error('Error al verificar el envío:', error));
    });
  }
</script>

<script>
  function confirmarEnvio(id) {
    var boton = document.getElementById('recordatorioBtn' + id);

    fetch('/verificar-envio-permitido/' + id, { //solicitud para saber si el envio de mail esta permitido
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la solicitud AJAX: ' + response.statusText);
        }
        return response.json();
      })
      .then(data => {
        if (data.envio_permitido) {
          if (confirm('¿Estás seguro de enviar un recordatorio al encargado de mantenimiento?')) {

            fetch('/enviar-recordatorio/' + id, { //solicitud para enviar el mail
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              }
            })
              .then(response => {
                if (!response.ok) {
                  throw new Error('Error en la solicitud AJAX: ' + response.statusText);
                }
                return response.json();
              })
              .then(data => {

                if (data.success) {
                  boton.disabled = true;
                  document.getElementById('recordatorioForm' + id).submit(); //envio el formulario
                  window.location.href = window.location.pathname + window.location.search; //redirijo la pagina asi no muestra el json
                } else {
                  alert(data.message);
                }

              })
              .catch(error => {
                console.error('Error en la solicitud AJAX para enviar el recordatorio:', error);
                alert('Error en la solicitud AJAX para enviar el recordatorio: ' + error.message);
              });
          }
        }
        else {
          boton.disabled = true;
        }
      })
      .catch(error => {
        console.error('Error en la solicitud AJAX para verificar el envío:', error);
        alert('Error en la solicitud AJAX para verificar el envío: ' + error.message);
      });
  }
</script>

<script>

  function manejarSeleccion(idEquipo) {
    $('#equipo').val(idEquipo).trigger('change');
    $('#equipo1').val(idEquipo).trigger('change');
  }

  var ruta = '{{ route('mostrar_equipos_mant') }}';
  var ruta_create = '{{ route('store_solicitud') }}';
  var ruta_update = '{{ route('update_solicitud') }}';
  var ruta_edit = '{{ route('edit_solicitud') }}';
  var ruta_assing = '{{ route('assing_solicitud') }}';
  var ruta_reclaim = '{{ route('reclaim_solicitud') }}';
  var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cancelar</button> ');
  var saveButton = $('<button type="submit" class="btn btn-info"  onclick="fnSaveSolicitud()" id="asignar-btn">Guardar</button>');
  var saveButton2 = $('<button type="submit" class="btn btn-info" id="saveButton2" onclick="fnSaveSolicitud2()">Guardar</button>');

  function fnSaveSolicitud() {
    var form = document.getElementById('myForm');
    if (form.checkValidity()) {
      $('#saveButton').prop('disabled', true);
      $('#myForm').submit();
    } else {
      console.log('El formulario no es válido. Completar los campos requeridos antes de enviar.');
    }
  }
  function fnSaveSolicitud2() {
    var form = document.getElementById('myForm4');
    if (form.checkValidity()) {
      $('#saveButton2').prop('disabled', true);
      $('#myForm4').submit();
    } else {
      console.log('El formulario no es válido. Completar los campos requeridos antes de enviar.');
    }
  }

  function getSolicitud(idSolicitud) {
    return new Promise(function (resolve, reject) {
      $.ajax({
        url: window.location.protocol + '//' + window.location.host + "/getSolicitud/" + idSolicitud,
        method: 'GET',
        success: function (data) {
          resolve(data);
        },
        error: function (error) {
          reject(error);
        }
      });
    });
  }
  var solicitud;
  //modal edit
  async function fnOpenModalEdit(id) {
    var myModal = new bootstrap.Modal(document.getElementById('show4'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_edit_solicitud/" + id,
      type: 'GET',
      success: function (data) {
        // Borrar contenido anterior
        $("#modalshow4").empty();
        // Establecer el contenido del modal
        $("#modalshow4").html(data);
        // Borrar contenido anterior
        $("#modalfooter4").empty();
        // Agregar el botón "Cerrar y Guardar" al footer
        $("#modalfooter4").append(closeButton);
        $("#modalfooter4").append(saveButton2);
        // Cambiar la acción del formulario
        $('#myForm4').attr('action', ruta_edit);
        // Mostrar el modal
        myModal.show();
        // Cambiar el tamaño del modal a "modal-lg"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.remove('modal-lg');
      },
    });
    try {
      solicitud = await getSolicitud(id);
    } catch (error) {
      console.error('Error al obtener la solicitud:', error);
    }
  }
  $('#show4').on('show.bs.modal', function (event) {
    $.get('select_tablas/', function (data) {
      var divDescripcion = $('#div_descripcion1')
      divDescripcion.hide();
      var htmlSelectArea = '<option value="">Seleccione </option>'
      var htmlSelectLocalizacion = '<option value="">Seleccione </option>'
      var htmlSelectTipoSolicitud = '<option value="">Seleccione </option>'
      var htmlSelectEquipo = '<option value="">Seleccione </option>'
      var htmlSelectFalla = '<option value="">Seleccione </option>'
      var htmlDescripcionEquipo = ''
      // [0]=areas [1]=localizaciones [2]=tipo_solicitudes [3]=equipos_mant 
      // [4]=fallas [5]=tipos_equipos [6]=fallasxtipo
      var equipoPrecargado = false;
      var areaPrecargada = false;
      var tipoPrecargado = false;
      var tipoSolicitudSelected;
      var equipoSelected;
      var areaSelected;
      data[2].forEach(tipo_solicitud => {
        if (tipo_solicitud.nombre === solicitud[0].nombreTipoSolicitud) {
          htmlSelectTipoSolicitud += `<option value="${tipo_solicitud.id}" selected>${tipo_solicitud.nombre}</option>`;
          tipoPrecargado = true;
        } else {
          htmlSelectTipoSolicitud += `<option value="${tipo_solicitud.id}">${tipo_solicitud.nombre}</option>`;
        }
      });

      data[0].forEach(item => {
        if ((item.id_a === solicitud[0].idAreaProyecto) || (item.id_a === solicitud[0].idAreaEquipo) || (item.id_a === solicitud[0].idAreaEdilicio)) {
          htmlSelectArea += `<option value="${item.id_a}" selected>${item.nombre_a}</option>`;
          areaPrecargada = true;
        } else {
          htmlSelectArea += `<option value="${item.id_a}">${item.nombre_a}</option>`;
        }
      });
      data[3].forEach(equipo => {
        if (equipo.id === solicitud[0].idEquipo) {
          htmlSelectEquipo += `<option value="${equipo.id}" selected>${equipo.id}</option>`;
          equipoPrecargado = true;
        } else {
          htmlSelectEquipo += `<option value="${equipo.id}">${equipo.id}</option>`;
        }
      });
      $('#tipo_solicitud1').on('change', function () {
        tipoSolicitudSelected = $(this).val();
        const divEquipo = $('#div_equipo1');
        const divFalla = $('#div_falla1');
        if (!tipoSolicitudSelected) {
          divEquipo.show();
          divFalla.hide();
          document.getElementById("localizacion1").setAttribute("required", "required");
          document.getElementById("falla1").setAttribute("required", "required");
        }
        else if (tipoSolicitudSelected == 1) {
          divEquipo.show();
          divFalla.hide();
          document.getElementById("localizacion1").setAttribute("required", "required");
          document.getElementById("falla1").setAttribute("required", "required");
        }
        else if (tipoSolicitudSelected == 2) {
          divEquipo.hide();
          divFalla.show();
          divDescripcion.hide();
          let htmlSelectFalla = '<option value="">Seleccione </option>';
          data[6].forEach(falla => {
            if (falla.id_tipo_solicitud == 2) {
              data[4].forEach(falla2 => {
                if (falla2.id == falla.id_falla) {
                  if (solicitud[0].idFalla) {
                    if (falla.id_falla === solicitud[0].idFalla) {
                      htmlSelectFalla += `<option value="${falla.id_falla}" selected>${falla2.nombre}</option>`;
                    } else {
                      htmlSelectFalla += `<option value="${falla.id_falla}">${falla2.nombre}</option>`;
                    }
                  } else {
                    htmlSelectFalla += `<option value="${falla.id_falla}">${falla2.nombre}</option>`;
                  }
                }
              })
            }
          });
          $('#falla1').html(htmlSelectFalla);
          document.getElementById("localizacion1").setAttribute("required", "required");
          document.getElementById("falla1").setAttribute("required", "required");
        }
        else if (tipoSolicitudSelected == 3) {
          divEquipo.hide();
          divFalla.hide();
          divDescripcion.hide();
          $('#div_localizacion1').hide();
          document.getElementById("localizacion1").removeAttribute("required");
          document.getElementById("falla1").removeAttribute("required");
        }
        $('#area1').prop('disabled', false);
        $('#localizacion1').prop('disabled', false);
        $('#descripcion_equipo1').prop('disabled', false);
      });
      $('#equipo1').on('change', function () {
        var equipoSelected = $(this).val();
        if (!equipoSelected) {
          $('#div_falla1').hide();
          $('#div_descripcion1').hide();
          var htmlSelectFalla = '<option value="">Seleccione </option>'
          $('#area1').prop('disabled', false);
          $('#localizacion1').prop('disabled', false);
        } else {
          var htmlSelectFalla = '<option value="">Seleccione </option>'
          for (var k = 0; k < data[3].length; k++) {
            if (equipoSelected == data[3][k].id) {
              for (var j = 0; j < data[6].length; j++) {
                if (data[3][k].id_tipo == data[6][j].id_tipo_equipo) {
                  for (var i = 0; i < data[4].length; i++) {
                    if (data[6][j].id_falla == data[4][i].id) {
                      if (data[6][j].id_falla === solicitud[0].idFalla) {
                        htmlSelectFalla += '<option value ="' + data[6][j].id_falla + '" selected>' + data[4][i].nombre + '</option>';
                      } else {
                        htmlSelectFalla += '<option value ="' + data[6][j].id_falla + '">' + data[4][i].nombre + '</option>';
                      }
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
              $('#descripcion_equipo1').val(htmlDescripcionEquipo).trigger('change');
              $('#area1').val(idAreaEquipo).trigger('change');
              // Establecer el valor de id_localizacion en el select de localización, o seleccionar la opción vacía si es nulo
              if (idLocalizacionEquipo) {
                $('#localizacion1').val(idLocalizacionEquipo).trigger('change');
              } else {
                // Agregar la opción "No aplica" en el select de localización
                $('#localizacion1').append('<option value="0">No aplica</option>');
                $('#localizacion1').val('0').trigger('change');
              }
              $('#area1').prop('disabled', true);
              $('#localizacion1').prop('disabled', true);
              $('#descripcion_equipo1').prop('disabled', true);
            }
          }
          $('#falla1').html(htmlSelectFalla);
          $('#tipo_solicitud1').val('1');
          $('#div_localizacion1').show();
          $('#div_falla1').show();
          $('#div_descripcion1').show();
        }
      });
      $('#area1').on('change', function () {
        areaSelected = $(this).val();
        // Obtener las localizaciones correspondientes al área seleccionada y agregarlas al select correspondiente
        let htmlSelectLocalizacion = '<option value="">Seleccione</option>';
        data[1].forEach(localizacion => {
          if (localizacion.id_area == areaSelected) {
            if ((localizacion.id === solicitud[0].idLocalizacionEquipo) || (localizacion.id === solicitud[0].idLocalizacionEdilicio)) {
              htmlSelectLocalizacion += `<option value="${localizacion.id}" selected>${localizacion.nombre}</option>`;
            } else {
              htmlSelectLocalizacion += `<option value="${localizacion.id}">${localizacion.nombre}</option>`;
            }
          }
        });
        if (!areaSelected) {
          $('#div_localizacion1').hide();
        } else {
          if (tipoSolicitudSelected == 3) {
            $('#div_localizacion1').hide();
          }
          else {
            $('#div_localizacion1').show();
          }
          $('#localizacion1').html(htmlSelectLocalizacion);
        }
      });

      $('#idSolicitud1').val(solicitud[0].idSolicitud);
      $('#estado1').val(solicitud[0].estado);
      $('#titulo1').val(solicitud[0].titulo);
      $("#descripcion1").val(solicitud[0].descripcion);
      $('#tipo_solicitud1').html(htmlSelectTipoSolicitud);
      $('#equipo1').select2();
      $('#equipo1').html(htmlSelectEquipo);
      $('#area1').html(htmlSelectArea);
      $('#localizacion1').html(htmlSelectLocalizacion);
      if (tipoPrecargado) {
        $('#tipo_solicitud1').trigger('change');
      }
      if (equipoPrecargado) {
        $('#equipo1').trigger('change');
        $('#descripcion_equipo1').val(solicitud[0].descripcionEquipo);
      }
      if (areaPrecargada) {
        $('#area1').trigger('change');
      }
    });
    //para cerrar modales
    closeButton.on('click', function () {
      myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
    });
  });

  //modal store
  function fnOpenModalShowEquipos() {
    var myModal3 = new bootstrap.Modal(document.getElementById('show3'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_mostrar_equipos_mant/",
      type: 'GET',
      success: function (data) {
        // Borrar contenido anterior
        $("#modalshow3").empty();
        // Establecer el contenido del modal
        $("#modalshow3").html(data);

        // Borrar contenido anterior
        $("#modalfooter3").empty();

        // Agregar el botón "Cerrar" al footer del modal interno
        $("#modalfooter3").append(closeButton);

        // Agregar listener al botón "Cerrar" del modal secundario
        closeButton.click(function (event) {
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
    //para cerrar modales
    closeButton.on('click', function () {
      myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
    });
  }

  //modal store
  function fnOpenModalStore() {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    var url = window.location.origin + "/show_store_solicitud/";
    var closeButton2 = $('<button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cancelar</button>');
    $.get(url, function (data) {
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

      // Asegurar que el evento de cierre esté asociado correctamente
      closeButton2.on('click', function () {
        myModal.hide(); // Cerrar el modal cuando se hace clic en el botón Cerrar
      });

    });

    // Opcional: Si quieres también manejar el cierre con el botón de cierre de Bootstrap (si existe)
    $('#show2').on('hidden.bs.modal', function () {
      // Aquí puedes agregar lógica extra si es necesario
      console.log('El modal se cerró.');
    });

    $('#show2').on('show.bs.modal', function (event) {
      $.get('select_tablas/', function (data) {
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
            document.getElementById("localizacion").setAttribute("required", "required");
            document.getElementById("falla").setAttribute("required", "required");
          }
          else if (tipoSolicitudSelected == 1) {
            divEquipo.show();
            divFalla.hide();
            document.getElementById("localizacion").setAttribute("required", "required");
            document.getElementById("falla").setAttribute("required", "required");
          }
          else if (tipoSolicitudSelected == 2) {
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
            document.getElementById("localizacion").setAttribute("required", "required");
            document.getElementById("falla").setAttribute("required", "required");
          }
          else if (tipoSolicitudSelected == 3) {
            divEquipo.hide();
            divFalla.hide();
            divDescripcion.hide();
            $('#div_localizacion').hide();
            document.getElementById("localizacion").removeAttribute("required");
            document.getElementById("falla").removeAttribute("required");
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

          if (!areaSelected) {
            $('#div_localizacion').hide();
          } else {
            if (tipoSolicitudSelected == 3) {
              $('#div_localizacion').hide();
            }
            else {
              $('#div_localizacion').show();
            }
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
      checkboxes[i].addEventListener("change", function () {
        if (!this.checked) {
          checkAllCheckbox.checked = false;
        }
      });
    }

    checkAllCheckbox.addEventListener("change", function () {
      for (let i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = this.checked;
      }
    });

    for (let i = 0; i < checkboxes.length; i++) {
      checkboxes[i].addEventListener("change", function () {
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
  function getHistoricos(id) {
    return new Promise(function (resolve, reject) {
      $.ajax({
        url: window.location.protocol + '//' + window.location.host + "/getHistoricos/" + id,
        method: 'GET',
        success: function (data) {
          resolve(data);
        },
        error: function (error) {
          reject(error);
        }
      });
    });
  }

  async function Report() {
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
    doc.setFontSize(10);

    var content = [];

    for (var i = 0; i < checkboxes.length; i++) {
      var checkbox = checkboxes[i];
      var row = checkbox.closest('tr');
      var id = row.querySelector('td:nth-child(2)').textContent.trim();
      var titulo = row.querySelector('td:nth-child(3)').textContent.trim();
      var tipo = row.querySelector('td:nth-child(4)').textContent.trim();
      var equipo = row.querySelector('td:nth-child(5)').textContent.trim();
      var falla = row.querySelector('td:nth-child(7)').textContent.trim();

      // Ajustar el diseño del contenido del PDF
      content.push({ label: "ID: ", value: id, x: 10, y: y })
      content.push({ label: "Título: ", value: titulo, x: 50, y: y })

      if (tipo == "Especializado") {
        content.push({ label: "Equipo: ", value: equipo, x: 10, y: y + 5 });
        content.push({ label: "Falla: ", value: falla, x: 50, y: y + 5 });
      } else if (tipo == "Edilicio") {
        content.push({ label: "Falla: ", value: falla, x: 10, y: y + 5 });
      }

      try {
        // Obtener los históricos de la solicitud actual
        var historicos = await getHistoricos(id);
        // Agregar los históricos al contenido del PDF
        if (tipo == "Especializado" || tipo == "Edilicio") {
          var historicoOffset = 10;
        } else {
          var historicoOffset = 5;
        }

        for (var j = 0; j < historicos.length; j++) {
          var historico = historicos[j];
          var estado = historico.estado;
          var fecha = historico.fecha;
          var nombre = historico.nombre;
          var descripcion = historico.descripcion;
          var repuestos = historico.repuestos;

          var historicoContent = [
            { label: "Histórico " + (j + 1) + ": ", value: "", x: 10, y: y + historicoOffset },
            { label: "Fecha: ", value: fecha, x: 20, y: y + historicoOffset + 5 },
            { label: "Estado: ", value: estado, x: 95, y: y + historicoOffset + 5 },
            { label: "Nombre: ", value: nombre, x: 20, y: y + historicoOffset + 10 },
          ];

          if (repuestos) {
            si = "Si";
            historicoContent.push({ label: "Repuestos: ", value: si, x: 95, y: y + historicoOffset + 10 });
          } else {
            no = "No";
            historicoContent.push({ label: "Repuestos: ", value: no, x: 95, y: y + historicoOffset + 10 });
          }

          if (descripcion) {
            nada = "";
            historicoContent.push({ label: "Descripción: ", value: nada, x: 20, y: y + historicoOffset + 15 });
          }

          // Incrementar el desplazamiento para el próximo histórico
          if (descripcion) {
            historicoOffset += 20;
            var lines = doc.splitTextToSize(descripcion, 150); // Dividir la descripción en líneas de 150 unidades de ancho
            for (var k = 0; k < lines.length; k++) {
              historicoContent.push({ label: "", value: lines[k], x: 20, y: y + historicoOffset + (k * 5) }); // Añadir cada línea como una entrada separada
            }
            historicoOffset += lines.length * 5;
          } else {
            historicoOffset += 15;
          }
          content = content.concat(historicoContent);
        }

        y += historicoOffset;

        // Incrementar la posición vertical para la próxima solicitud
      } catch (error) {
        console.error('Error al obtener los históricos:', error);
      }
    }
    // Agregar el contenido al PDF
    var avance = 0;
    var contador = 1;
    var auxiliarY = 0;
    var idInserted = false;
    var equipoInserted = false;
    var fechaInserted = false;
    var nombreInserted = false;
    for (var k = 0; k < content.length; k++) {
      var item = content[k];
      if (auxiliarY >= (pageHeight - 20) && !idInserted && !equipoInserted && !fechaInserted && !nombreInserted) {
        doc.addPage();
        contador += 1;
        avance += 30;
      }
      if (contador > 1) {
        auxiliarY = (item.y - (pageHeight * (contador - 1)) + avance);
        doc.setFontStyle("bold"); // Establecer estilo de fuente en negrita para la etiqueta "ID: " 
        doc.text(item.label, item.x, auxiliarY);
        doc.setFontStyle("normal"); // Establecer estilo de fuente normal para el valor

        var labelWidth = doc.getTextWidth(item.label); // Obtener el ancho del label
        var valueX = item.x + labelWidth + 1; // Agregar un pequeño espacio después del label

        if (item.label.includes("ID")) {
          doc.setLineWidth(0.5);
          doc.line(10, auxiliarY - 4, 200, auxiliarY - 4);
          idInserted = true;
        } else { idInserted = false; }

        if (item.label.includes("Equipo")) {
          equipoInserted = true;
        } else { equipoInserted = false; }

        if (item.label.includes("Fecha")) {
          fechaInserted = true;
        } else { fechaInserted = false; }

        if (item.label.includes("Nombre")) {
          nombreInserted = true;
        } else { nombreInserted = false; }

        doc.text(item.value, valueX, auxiliarY);
      } else {
        doc.setFontStyle("bold"); // Establecer estilo de fuente en negrita para la etiqueta "ID: " 
        doc.text(item.label, item.x, item.y);
        doc.setFontStyle("normal"); // Establecer estilo de fuente normal para el valor

        var labelWidth = doc.getTextWidth(item.label); // Obtener el ancho del label
        var valueX = item.x + labelWidth + 1; // Agregar un pequeño espacio después del label

        if (item.label.includes("ID")) {
          if (auxiliarY != 0) {
            doc.setLineWidth(0.5);
            doc.line(10, auxiliarY + 1, 200, auxiliarY + 1);
          }
          idInserted = true;
        } else { idInserted = false; }

        if (item.label.includes("Equipo")) {
          equipoInserted = true;
        } else { equipoInserted = false; }

        if (item.label.includes("Fecha")) {
          fechaInserted = true;
        } else { fechaInserted = false; }

        if (item.label.includes("Nombre")) {
          nombreInserted = true;
        } else { nombreInserted = false; }

        doc.text(item.value, valueX, item.y);
        auxiliarY = item.y;
      }
    }
    // Guardar el documento PDF después de procesar todas las solicitudes
    doc.save('reporte.pdf');
  }

  $(document).ready(function () {
    $("#id").keyup(function () {
      _this = this;
      $.each($("#test tbody tr"), function () {
        if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
          $(this).hide();
        else
          $(this).show();
      });
    });
  });

  //Duracion de alerta (agregado, elimnado, editado)
  $("solicitud").ready(function () {
    setTimeout(function () {
      $("div.alert").fadeOut();
    }, 5000); // 5 secs

  });

  //modal show
  function fnOpenModalShow(id) {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_solicitud/" + id,
      type: 'GET',
      success: function (data) {
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

        //para cerrar modales
        closeButton.on('click', function () {
          myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
        });
      },

    });

  }

  //modal update
  function fnOpenModalUpdate(id) {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_update_solicitud/" + id,
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

        // Mostrar el modal
        myModal.show();

        // Cambiar el tamaño del modal a "modal-lg"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.add('modal-lg');

           //para cerrar modales
closeButton.on('click', function () {
      myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
    });
      },
    });
  }
  $('#show2').on('show.bs.modal', function (event) {
    $.get('select_estado/', function (data) {
      var html_select = '<option value="">Seleccione </option>'

      for (var i = 0; i < data.length; i++) {
        html_select += '<option value ="' + data[i].id + '">' + data[i].nombre + '</option>';
      }
      $('#estado').html(html_select);

      $('#rep').on('change', function () {
        if ($(this).is(':checked')) {
          $('#divDescRep').show();
        } else {
          $('#divDescRep').hide();
        }
      });
    });
  });

  //modal assing
  function fnOpenModalAssing(id) {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_assing_solicitud/" + id,
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
        $('#myForm').attr('action', ruta_assing);

        // Mostrar el modal
        myModal.show();

        // Cambiar el tamaño del modal a "modal-sm"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-lg');
        modalDialog.classList.add('modal-sm');
      },
    });
    $('#show2').on('show.bs.modal', function (event) {
      $.get('select_users/', function (data) {
        var html_select = '<option value="">Seleccione </option>'
        for (var i = 0; i < data[0].length; i++) {
          for (var k = 0; k < data[1].length; k++) {
            if ((data[0][i].id == data[1][k].model_id) && (data[1][k].role_id == 21 || data[1][k].role_id == 24 || data[1][k].role_id == 30)) {
              html_select += '<option value ="' + data[0][i].id + '">' + data[0][i].name + '</option>';
            }
          }
        }
        $('#user').html(html_select);
      });
      //para cerrar modales
      closeButton.on('click', function () {
        myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
      });
    });
  }

  function fnOpenModalReclaim(id) {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_reclamar_solicitud/" + id,
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
        $('#myForm').attr('action', ruta_reclaim);

        // Mostrar el modal
        myModal.show();

        // Cambiar el tamaño del modal a "modal-lg"
        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.add('modal-lg');
      },

    });
    //para cerrar modales
    closeButton.on('click', function () {
      myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
    });
  }

  // Obtener el valor del parámetro "idsolicitud" de la URL
  var urlParams = new URLSearchParams(window.location.search);
  var idSolicitud = urlParams.get('idsolicitud');

  // Obtener el valor del parámetro "source" de la URL
  var source = urlParams.get('source');

  // Verificar si el acceso proviene del correo electrónico
  if (source === 'email') {
    // Ejecutar la función correspondiente con el valor de "idSolicitud"
    fnOpenModalReclaim(idSolicitud);
  } else if (source === 'detalle') {
    fnOpenModalShow(idSolicitud);
  }

</script>
@endpush

