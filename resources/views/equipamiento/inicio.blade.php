@extends('layouts.app')

<!-- Vinculación de archivos CSS -->
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> 
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<!-- Vinculación de archivos JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid" id="equipamiento-container">
  <!-- Mensajes de sesión -->
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

  <!-- Barra de búsqueda -->
  <div>
    <div id="equipamiento-btn">
      <a href="#" class="btn btn-info" data-toggle="modal" data-target="#agregar_equipamiento">Nuevo equipamiento</a>
      <a href="#" class="btn btn-info" data-toggle="modal" data-target="#agregar_software">Nuevo Software</a>
    </div>

    <div class="form-group">
      <form method="GET" action="{{ route('equipamiento.index') }}">
        <div style="display: inline-block;">
          <label for="equipo" style="display: block; margin-bottom: 5px;">
            <h6>ID:</h6>
          </label>
          <input type="text" name="equipo" class="form-control" id="equipo" autocomplete="off"
            value="{{ request('equipo') }}">
        </div>
        <div style="display: inline-block;">
          <label for="usuario" style="display: block; margin-bottom: 5px;">
            <h6>Usuario:</h6>
          </label>
          <input type="text" name="usuario" class="form-control" id="usuario" autocomplete="off"
            value="{{ request('usuario') }}">
        </div>
        <div style="display: inline-block;">
          <label for="puesto" style="display: block; margin-bottom: 5px;">
            <h6>Puesto:</h6>
          </label>
          <input type="text" name="puesto" class="form-control" id="puesto" autocomplete="off"
            value="{{ request('puesto') }}">
        </div>
        <div style="display: inline-block;">
          <label for="area" style="display: block; margin-bottom: 5px;">
            <h6>Area:</h6>
          </label>
          <input type="text" name="area" class="form-control" id="area" autocomplete="off"
            value="{{ request('area') }}">
        </div>
        <div style="display: inline-block;">
          <label for="ip" style="display: block; margin-bottom: 5px;">
            <h6>IP:</h6>
          </label>
          <input type="text" name="ip" class="form-control" id="ip" autocomplete="off" value="{{ request('ip') }}">
        </div>
        <div style="display: inline-block;">
          <label for="activo" style="display: block; margin-bottom: 5px;">
            <h6>Activo:</h6>
          </label>
          <select name="activo" id="activo" class="form-control">
            <option value="">Todos</option>
            <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivo</option>
          </select>
        </div>
        <div style="display: inline-block;">
          <label for="tipo" style="display: block; margin-bottom: 5px;">
            <h6>Tipo equipamiento:</h6>
          </label>
          <select class="form-control" name="tipo" id="tipo">
            <option value="0">{{ 'Todos' }}</option>
            @foreach($tipo_equipamiento as $tipo_eq)
        <option value="{{ $tipo_eq->id }}" {{ request('tipo') == $tipo_eq->id ? 'selected' : '' }}>
          {{ $tipo_eq->equipamiento }}
        </option>
      @endforeach
          </select>
        </div>
        <div style="display: inline-block;">
          <button type="submit" class="btn btn-default" id="asignar-btn">Buscar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabla de Equipamientos -->
  <div>
    <table>
      <thead>
        <th class="text-center">ID</th>
        <th class="text-center">Usuario</th>
        <th class="text-center">Puesto</th>
        <th class="text-center">Localizacion</th>
        <th class="text-center">Area</th>
        <th class="text-center">IP</th>

        <!-- Condiciones de tipo de equipamiento -->
        @if($tipo === '1')
      <th class="text-center">Marca</th>
      <th class="text-center">Procesador</th>
      <th class="text-center">Disco</th>
      <th class="text-center">Memoria</th>
    @elseif($tipo === '2')
    <th class="text-center">Marca</th>
    <th class="text-center">Modelo</th>
    <th class="text-center">Pulgadas</th>
  @elseif($tipo === '3')
  <th class="text-center">Marca</th>
  <th class="text-center">Modelo</th>
  <th class="text-center">Toner</th>
  <th class="text-center">Unidad de imagen (DR)</th>
@endif

        <th class="text-center">Activo</th>
        <th class="text-center">Acciones</th>
      </thead>
      <tbody>
        @foreach($equipamientos as $equipamiento)
          <tr>
            <td class="text-center" width="60">{{$equipamiento->id_equipamiento}}</td>
            <td class="text-center">{{$equipamiento->nombre . ' ' . $equipamiento->apellido}}</td>
            <td width="available" class="text-center">{{$equipamiento->puesto}}</td>
            <td class="text-center">{{$equipamiento->localizacion}}</td>
            <td class="text-center">{{$equipamiento->area}}</td>
            <td width="110" class="text-center">{{$equipamiento->ip}}</td>

            <!-- Detalles según el tipo de equipamiento -->
            @if($tipo === '1')
        <td class="text-center">{{ $equipamiento->marca }}</td>
        <td class="text-center">{{ $equipamiento->procesador }}</td>
        <td class="text-center">{{ $equipamiento->disco }}</td>
        <td class="text-center">{{ $equipamiento->memoria }}</td>
      @elseif($tipo === '2')
    <td class="text-center">{{ $equipamiento->marca }}</td>
    <td class="text-center">{{ $equipamiento->modelo }}</td>
    <td class="text-center">{{ $equipamiento->pulgadas }}</td>
  @elseif($tipo === '3')
  <td class="text-center">{{ $equipamiento->marca }}</td>
  <td class="text-center">{{ $equipamiento->modelo }}</td>
  <td class="text-center">{{ $equipamiento->toner }}</td>
  <td class="text-center">{{ $equipamiento->unidad_imagen }}</td>
@endif

            <!-- Estado de "Activo" -->
            <td class="activo text-center activo-col">
            @if (is_null($equipamiento->activo))
        Nulo
      @elseif ($equipamiento->activo == 1)
    <span class="circle_green"></span>
  @else
  <span class="circle_grey"></span>
@endif
            </td>

            <!-- Acciones -->
            <td align="center" width="170">
            <div class="row justify-content-center align-items-center">
              @if ($equipamiento->relacion != null)
          <a role="button" class="fa-solid fa-xmark eliminar mx-2"
          href="{{url('destroy_relacion', $equipamiento->relacion)}}" title="Eliminar"
          onclick="return confirm('¿Está seguro que desea eliminar la relación?')" data-position="top"
          data-delay="50" data-tooltip="Borrar">
          <img src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar" id="img-icono-equip">
          </a>
        @else
        <a role="button" class="fa-solid fa-plus agregar mx-2" href="#" title="Asignar"
        data-id="{{$equipamiento->id_equipamiento}}" data-toggle="modal" data-target="#asignar">
        <img src="{{ asset('storage/cursos/asignar.png') }}" alt="Asignar" id="img-icono-equip">
        </a>
      @endif

              <a role="button" class="fa-solid fa-pen default mx-2" href="#" title="Editar" data-toggle="modal"
              data-id="{{$equipamiento->id_equipamiento}}" data-ip="{{$equipamiento->ip}}"
              data-marca="{{$equipamiento->marca}}" data-modelo="{{$equipamiento->modelo}}"
              data-tipo="{{$equipamiento->tipo}}" data-num_serie="{{$equipamiento->num_serie}}"
              data-procesador="{{$equipamiento->procesador}}" data-disco="{{$equipamiento->disco}}"
              data-memoria="{{$equipamiento->memoria}}" data-pulgadas="{{$equipamiento->pulgadas}}"
              data-toner="{{$equipamiento->toner}}" data-activo="{{ $equipamiento->activo }}"
              data-unidad_imagen="{{$equipamiento->unidad_imagen}}" data-obs="{{$equipamiento->obs}}"
              data-subred="{{$equipamiento->subred}}" data-target="#editar_equipamiento">
              <img src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono-equip">
              </a>

              <a role="button" class="fa-solid fa-gear default mx-2" href="#" title="Software"
              data-id="{{$equipamiento->id_equipamiento}}" data-toggle="modal" data-target="#ver_s">
              <img src="{{ asset('storage/cursos/settings.png') }}" alt="Software" id="img-icono-equip">
              </a>

              <a role="button" class="fa-solid fa-exclamation default mx-2" href="#" title="Incidente"
              data-id="{{$equipamiento->id_equipamiento}}" data-toggle="modal" data-target="#incidente">
              <img src="{{ asset('storage/cursos/exclamacion.png') }}" alt="Incidente" id="img-icono-equip">
              </a>

              <!-- Observaciones -->
              @php
        $observacionClass = ($equipamiento->obs && $equipamiento->obs != 'Sin observación') ? 'btn-default' : 'btn-default disabled';
        $buttonStyle = ($equipamiento->obs && $equipamiento->obs != 'Sin observación') ? '' : 'pointer-events: none; opacity: 0.5;';
        @endphp
              <a role="button" href="#" class="text-decoration-none mx-0" style="{{ $buttonStyle }}"
              title="{{ $equipamiento->obs ? 'Observaciones' : 'Sin observaciones' }}" data-toggle="modal"
              data-target="#ver_obs" data-obs="{{ $equipamiento->obs }}">
              <img src="{{ asset('storage/cursos/ver.png') }}" alt="Observaciones" id="img-icono-equip">
              </a>
            </div>
            </td>
          </tr>
    @endforeach
      </tbody>
    </table>

    <!-- Ventana modal Observaciones -->
    <div class="modal fade" id="ver_obs" tabindex="-1" role="dialog" aria-labelledby="ver_obs_title" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header text-center">
            <h5 class="modal-title mx-auto">Observaciones</h5>
          </div>
          <div class="modal-body text-center" id="obs_content">
            <!-- Aquí se cargan las observaciones -->
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Paginación -->
    {{ $equipamientos->links('pagination::bootstrap-4') }}
  </div>

  <!-- Modales de los diferentes formularios -->
  @include('incidentes.create_incidente')
  @include('equipamiento.edit')
  @include('equipamiento.asignar')
  @include('equipamiento.asingn_soft')
  @include('equipamiento.create')
  @include('software.create')
</div>




<script> //Mostrar contenido de ventanas modales observaciones
  $('#ver_obs').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var obs = button.data('obs');
    var modal = $(this);

    modal.find('.modal-body').text(obs);
  });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
<script>
  $('#ver_obs').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var obs = button.data('obs');
    var modal = $(this);
    modal.find('.modal-body #obs_content').text(obs);
  });
</script>
<script>
  $("document").ready(function () {
    setTimeout(function () {
      $("div.alert").fadeOut();
    }, 5000); // 5 secs

  });
</script>

<script>
  $('#incidente').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget)
    var id = button.data('id')
    var modal = $(this)

    modal.find('.modal-body #equipamiento').val(id);

  })
</script>

<script>
  $('#asignar').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget)
    var id = button.data('id')
    var modal = $(this)

    modal.find('.modal-body #equipamiento').val(id);

    $.get('select_puesto', function (data) {
      var html_select = '<option value="">Seleccione </option>'
      for (var i = 0; i < data.length; i++) {
        if (data[i].nombre_p == null) {
          html_select += '<option value ="' + data[i].id_puesto + '">' + data[i].nombre_a + ' - ' + data[i].nombre + ' - ' + data[i].desc_puesto + '</option>';
        }
        else {
          html_select += '<option value ="' + data[i].id_puesto + '">' + data[i].nombre_a + ' - ' + data[i].nombre + ' - ' + data[i].desc_puesto + ' - ' + data[i].apellido + ' ' + data[i].nombre_p + '</option>';
        }
      }
      $('#select_puesto').html(html_select);
    });

  })
</script>

<script> //editar 
  $('#editar_equipamiento').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var ip = button.data('ip')
    var marca = button.data('marca')
    var modelo = button.data('modelo')
    var tipo = button.data('tipo')
    var subred = button.data('subred')
    var num_serie = button.data('num_serie')
    var procesador = button.data('procesador')
    var disco = button.data('disco')
    var memoria = button.data('memoria')
    var pulgadas = button.data('pulgadas')
    var toner = button.data('toner')
    var unidad_imagen = button.data('unidad_imagen')
    var obs = button.data('obs')
    var oc = button.data('oc')
    var activo = button.data('activo');
    var modal = $(this)

    let ip_dividida = ip.split('.');
    ip = ip_dividida[3];

    modal.find('.modal-body #id_e').val(id);
    modal.find('.modal-body #ip').val(ip);
    modal.find('.modal-body #marca').val(marca);
    modal.find('.modal-body #modelo').val(modelo);
    modal.find('.modal-body #num_serie').val(num_serie);
    modal.find('.modal-body #procesador').val(procesador);
    modal.find('.modal-body #disco').val(disco);
    modal.find('.modal-body #memoria').val(memoria);
    modal.find('.modal-body #pulgadas').val(pulgadas);
    modal.find('.modal-body #toner').val(toner);
    modal.find('.modal-body #unidad_imagen').val(unidad_imagen);
    modal.find('.modal-body #obs').val(obs);
    modal.find('.modal-body #oc').val(oc);

    modal.find('.modal-body #activo').val(activo);
    //desplegar select de tipo de equipo en editar equipamiento 
    $.get('select_tipo_equipamiento', function (data) {
      var html_select = '<option value="">Seleccione </option>'
      for (var i = 0; i < data.length; i++) {
        if (data[i].id == tipo) {
          html_select += '<option value ="' + data[i].id + '"selected>' + data[i].equipamiento + '</option>';
        }
        else {
          html_select += '<option value ="' + data[i].id + '">' + data[i].equipamiento + '</option>';
        }
      }
      $('#tipo_equipamiento_editar').html(html_select);
    });
    //desplegar select de subred en editar equipamiento 
    $.get('select_ips', function (data) {
      var html_select = '<option value="">Seleccione </option>'
      var html_select2 = '<option value="">Seleccione </option>'
      for (var i = 0; i < data.length; i++) {
        let ip = data[i].puerta_enlace.split('.');
        if (data[i].id == subred) {
          html_select += '<option value ="' + data[i].id + '"selected>' + data[i].nombre + '</option>';
          html_select2 += '<option value ="' + data[i].id + '"selected>' + ip[0] + '.' + ip[1] + '.' + ip[2] + '.' + '</option>';
        }
        else {
          html_select += '<option value ="' + data[i].id + '">' + data[i].nombre + '</option>';
          html_select2 += '<option value ="' + data[i].id + '">' + ip[0] + '.' + ip[1] + '.' + ip[2] + '.' + '</option>';
        }
      }
      //al cambiar un dato de un select se cambia en el otro 
      $("#ips_editar").on("change", () => {
        $("#id_red_editar").val($("#ips_editar").val());
      });

      $("#id_red_editar").on("change", () => {
        $("#ips_editar").val($("#id_red_editar").val());
      });

      //envia opciones de select a la vista edit.blade.php
      $('#ips_editar').html(html_select);
      $('#id_red_editar').html(html_select2);

    });
  });
</script>

<script>
  $('#ver_s').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget)
    var id = button.data('id')
    var modal = $(this)

    modal.find('.modal-body #equipamiento').val(id);

    $.get('select_soft/', function (data) {
      var html_select = '<option value=""> Seleccione </option>'
      for (var i = 0; i < data.length; i++) {
        html_select += '<option value ="' + data[i].id_s + '"selected>' + data[i].Software + ' - ' + data[i].Version + '</option>';
      }

      $('#ssoftware').html(html_select);
    });

  })
</script>

<script>
  $(document).ready(function () {
    $("#equipo").keyup(function () {
      _this = this;
      $.each($("#test tbody tr"), function () {
        if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
          $(this).hide();
        else
          $(this).show();
      });
    });
  });
</script>
<script>
  $('#agregar_equipamiento').on('show.bs.modal', function (event) {
    $.get('select_tipo_equipamiento/', function (data) {
      var html_select = '<option value="">Seleccione </option>'
      for (var i = 0; i < data.length; i++) {
        html_select += '<option value ="' + data[i].id + '">' + data[i].equipamiento + '</option>';
      }
      $('#tipo_equipamiento').html(html_select);
    });

    $.get('select_ips/', function (data) {
      var html_select = '<option value="">Seleccione </option>'
      var html_select2 = '<option value="">Seleccione </option>'
      for (var i = 0; i < data.length; i++) {
        let ip = data[i].puerta_enlace.split('.');
        html_select += '<option value ="' + data[i].id + '">' + data[i].nombre + '</option>';
        html_select2 += '<option value ="' + data[i].id + '">' + ip[0] + '.' + ip[1] + '.' + ip[2] + '.' + '</option>';
      }

      //al cambiar un dato de un select se cambia en el otro 
      $("#ips").on("change", () => {
        $("#id_red").val($("#ips").val());
      });

      $("#id_red").on("change", () => {
        $("#ips").val($("#id_red").val());
      });

      //envia opciones de select a la vista create.blade.php
      $('#ips').html(html_select);
      $('#id_red').html(html_select2);
    });
  });
</script>

<script>
  $('#agregar_puesto').on('show.bs.modal', function (event) {
    $.get('select_area/', function (data) {
      var html_select = '<option value="">Seleccione</option>'
      for (var i = 0; i < data.length; i++)
        html_select += '<option value ="' + data[i].id_a + '">' + data[i].nombre_a + '</option>';
      $('#area').html(html_select);
    });
    $.get('select_persona/', function (data) {
      var html_select = '<option value="">Seleccione</option>'
      for (var i = 0; i < data.length; i++)
        html_select += '<option value ="' + data[i].id_p + '">' + data[i].apellido + ' ' + data[i].nombre_p + '</option>';
      $('#persona').html(html_select);
    });
    $.get('select_localizaciones/', function (data) {
      var html_select = '<option value="">Seleccione</option>'
      /*for(var i = 0; i<data.length; i ++)
        html_select += '<option value ="'+data[i].id+'">'+data[i].nombre+'</option>';*/
      $('#localizacion').html(html_select);
    });

    // Variable para almacenar el valor seleccionado de localizacion
    var selectedLocalizacion = $('#localizacion').val();

    // Al seleccionar un área, cargar las localizaciones correspondientes
    $('#area').on('change', function () {
      var areaId = $(this).val();
      $.get('select_localizaciones_by_area/' + areaId, function (data) {
        var html_select = '<option value="">Seleccione</option>';
        for (var i = 0; i < data.length; i++) {
          html_select += '<option value ="' + data[i].id + '">' + data[i].nombre + '</option>';
        }
        $('#localizacion').html(html_select);

        // Restaurar el valor seleccionado de localizacion después de cargar las opciones
        $('#localizacion').val(selectedLocalizacion);
      });
    });

    // Al seleccionar una localización, cargar el área correspondiente
    $('#localizacion').on('change', function () {
      var localizacionId = $(this).val();
      $.get('select_area_by_localizacion/' + localizacionId, function (data) {
        // Primero, deseleccionamos el área actualmente seleccionada
        $('#area').val('');

        // Luego, seleccionamos el área correspondiente a la localización
        if (data.id_a) {
          $('#area').val(data.id_a);
        }
      });
    });
  });
</script>


<script>
  $('#agregar_software').on('show.bs.modal', function (event) {

  });
</script>

<style>
  /*estilos ventana modal Observaciones*/
  .modal-title {
    color: #333;
    font-size: 1.5rem;
    font-weight: bold;
  }


  .modal-body {
    color: #666;
    font-size: 1.2rem;
  }


  .close {
    color: #aaa;
    font-size: 2rem;
    opacity: 1;
  }
</style>