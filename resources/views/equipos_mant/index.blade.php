@extends('layouts.app')

@push('styles')

  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

@section('content')
  <!-- alertas -->
  <div id="equipos-container">
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
    <button id="toggle-search" class="btn btn-secondary" style="margin-bottom: 30px; margin-top: -20px;">Mostrar/Ocultar
    búsqueda</button>
    <div id="search-bar" style="display: block;">
    <button class="btn btn-info" onclick='fnOpenModalStore()' data-toggle="modal" data-target="#agregar_equipo_mant"
      id="btn-agregar">Agregar Equipo</button>
    <!-- barra para buscar equipos -->

    <div class="form-group">
      <form method="GET">
      <div style="display: inline-block;">
        <label for="id_e" style="display: block; margin-bottom: 5px;">
        <h6>ID:</h6>
        </label>
        <input type="text" class="form-control" name="id_e" id="id_e" autocomplete="off" value="{{$id_e}}">
      </div>
      <div style="display: inline-block; width: 200px;">
        <label for="tipo" style="display: block; margin-bottom: 5px;">
        <h6>Tipo:</h6>
        </label>
        <select class="form-control" name="tipo" id="tipo">
        <option value="0">{{'Todos'}} </option>
        @foreach($tiposEquipos as $tipoEquipo)
      @if($tipoEquipo->id != 0)
      @if($tipoEquipo->id == $tipo)
      <option value="{{$tipoEquipo->id}}" selected>{{$tipoEquipo->nombre}} </option>
    @else
      <option value="{{$tipoEquipo->id}}">{{$tipoEquipo->nombre}} </option>
    @endif
    @endif
    @endforeach
        </select>
      </div>
      <div style="display: inline-block; width: 200px;">
        <label for="id_area" style="display: block; margin-bottom: 5px;">
        <h6>Area:</h6>
        </label>
        <select class="form-control" name="id_area" id="id_area">
        <option value="">{{'Todos'}} </option>
        @foreach($areas as $area)
      @if($area->id_a == $id_area)
      <option value="{{$area->id_a}}" selected>{{$area->nombre_a}} </option>
    @else
      <option value="{{$area->id_a}}">{{$area->nombre_a}} </option>
    @endif
    @endforeach
        </select>
      </div>
      <div style="display: inline-block; width: 200px;">
        <label for="id_localizacion" style="display: block; margin-bottom: 5px;">
        <h6>Localizaciones:</h6>
        </label>
        <select class="form-control" name="id_localizacion" id="id_localizacion">
        <option value="0">{{'Todos'}} </option>
        @foreach($localizaciones as $localizacion)
      @if($localizacion->id == $id_localizacion)
      <option value="{{$localizacion->id}}" selected>{{$localizacion->nombre}} </option>
    @else
      <option value="{{$localizacion->id}}">{{$localizacion->nombre}} </option>
    @endif
    @endforeach
        </select>
      </div>
      <div style="display: inline-block;">
        <button type="submit" class="btn btn-default" id="asignar-btn"> Buscar</button>
      </div>
      </form>
    </div>
    </div>

    <!-- tabla de datos -->
    <div id="table-container">
    <table>
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
      @can('editar-equiposmant')
      <th class="text-center">Acciones</th>
    @endcan
      </thead>
      <tbody>
      @foreach($equipos_mant as $equipo_mant)
      <tr class="text-center">
      <td width="92">{{$equipo_mant->id_e}}</td>
      <td width="200">{{$equipo_mant->nombre_tipo}}</td>
      <td width="160">{{$equipo_mant->marca}}</td>
      <td width="160">{{$equipo_mant->modelo}}</td>
      <td class="descripcion">{{$equipo_mant->descripcion}}</td>
      <td>{{$equipo_mant->num_serie}}</td>
      <td>{{$equipo_mant->area}}</td>
      <td>{{$equipo_mant->localizacion}}</td>
      @if($equipo_mant->uso == 1)
      <td width="60">
      <div class="circle_green"></div>
      </td>
    @else
      <td width="60">
      <div class="circle_grey"></div>
      </td>
    @endif
      @can('editar-equiposmant')
      <td><button onclick='fnOpenModalUpdate("{{$equipo_mant->id_e}}")' title="Editar"
      data-tipo="{{$equipo_mant->id_tipo}}" data-area="{{$equipo_mant->id_area}}"
      data-localizacion="{{$equipo_mant->id_localizacion}}" id="edit-{{$equipo_mant->id_e}}" style="border: none;
      background: none;padding: 0;cursor: pointer;"> <img src="{{ asset('storage/cursos/editar.png') }}" alt="Editar"
      id="img-icono"></button>
      </td>
    @endcan
      </tr>

    @endforeach
      </tbody>
    </table>
    <div class="modal fade" id="show2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
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

    {{ $equipos_mant->links('pagination::bootstrap-4') }}
    </div>
  </div>

@endsection
@push('scripts')

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/modal-jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/modal-popper.min.js') }}"></script>
  <script language="JavaScript" src="{{ URL::asset('/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>

  <script>
    $(document).ready(function () {
    // Alterna la visibilidad de la barra de búsqueda cuando se hace clic en el botón
    $('#toggle-search').click(function () {
      $('#search-bar').toggle(); // Cambia entre mostrar/ocultar el contenedor
    });
    });
  </script>
  <script>
    //Duracion de alerta (agregado, elimnado, editado)
    $("equipo_mant").ready(function () {
    setTimeout(function () {
      $("div.alert").fadeOut();
    }, 5000); // 5 secs
    });
  </script>

  <script>
    var ruta_create = '{{ route('store_equipo_mant') }}';
    var ruta_update = '{{ route('update_equipo_mant') }}';
    var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cancelar</button>');
    var saveButton = $('<button type="submit" class="btn btn-info" id="asignar-btn">Guardar</button>');


    function fnOpenModalStore() {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));
    var url = window.location.origin + "/show_store_equipo_mant/";

    $.get(url, function (data) {

      $("#modalshow").empty();


      $("#modalshow").html(data);


      $("#modalfooter").empty();


      $("#modalfooter").append(closeButton);
      $("#modalfooter").append(saveButton);


      $('#myForm').attr('action', ruta_create);


      myModal.show();

      // Llenar select de tipos de equipos sin AJAX
      var tiposEquipos = @json($tiposEquipos);
      var html_select = '<option value="">Seleccione</option>';
      tiposEquipos.forEach(function (tipo) {
      html_select += '<option value="' + tipo.id + '">' + tipo.nombre + '</option>';
      });
      $('#tipo_e').html(html_select);

      // Llenar select de áreas y localizaciones sin AJAX
      var areas = @json($areas);
      var localizaciones = @json($localizaciones);

      var html_select_areas = '<option value="">Seleccione</option>';
      areas.forEach(function (area) {
      html_select_areas += '<option value="' + area.id_a + '">' + area.nombre_a + '</option>';
      });

      var html_select_localizaciones = '<option value="">Seleccione</option>';
      localizaciones.forEach(function (loc) {
      html_select_localizaciones += '<option value="' + loc.id + '">' + loc.nombre + '</option>';
      });

      $('#area').html(html_select_areas);
      $('#localizacion').html(html_select_localizaciones);

      // Manejo del cambio en el select de áreas para filtrar localizaciones
      $('#area').on('change', function () {
      var selectedOption = $(this).val();
      var filteredLocalizaciones = localizaciones.filter(loc => loc.id_area == selectedOption);

      var html_select_localizaciones = '<option value="">Seleccione</option>';
      filteredLocalizaciones.forEach(function (loc) {
        html_select_localizaciones += '<option value="' + loc.id + '">' + loc.nombre + '</option>';
      });

      $('#localizacion').html(html_select_localizaciones);

      if (selectedOption === '') {
        $('#div_localizacion').hide();
      } else {
        $('#div_localizacion').show();
      }
      });

      closeButton.on('click', function () {
      myModal.hide();
      });
    });
    }


    function fnOpenModalUpdate(id_e) {
    var myModal = new bootstrap.Modal(document.getElementById('show2'));


    var tipo = document.getElementById('edit-' + id_e).getAttribute('data-tipo');
    var area = document.getElementById('edit-' + id_e).getAttribute('data-area');
    var localizacion = document.getElementById('edit-' + id_e).getAttribute('data-localizacion');

    var url = window.location.origin + "/show_update_equipo_mant/" + id_e;

    $.get(url, function (data) {

      $("#modalshow").empty();

      $("#modalshow").html(data);


      $("#modalfooter").empty();
      // Agregar botones al footer
      $("#modalfooter").append(closeButton);
      $("#modalfooter").append(saveButton);


      $('#myForm').attr('action', ruta_update);


      myModal.show();

      // Llenar select de tipos de equipos sin AJAX
      var tiposEquipos = @json($tiposEquipos);
      var html_select = '<option value="">Seleccione</option>';
      tiposEquipos.forEach(function (tipoEquipo) {
      var selected = (tipoEquipo.id == tipo) ? "selected" : "";
      html_select += `<option value="${tipoEquipo.id}" ${selected}>${tipoEquipo.nombre}</option>`;
      });
      $('#tipo_equipo_mant_editar').html(html_select);

      // Llenar select de áreas y localizaciones sin AJAX
      var areas = @json($areas);
      var localizaciones = @json($localizaciones);

      var html_select_areas = '<option value="">Seleccione</option>';
      areas.forEach(function (areaData) {
      var selected = (areaData.id_a == area) ? "selected" : "";
      html_select_areas += `<option value="${areaData.id_a}" ${selected}>${areaData.nombre_a}</option>`;
      });
      $('#area_editar').html(html_select_areas);

      // Filtrar localizaciones basadas en el área seleccionada
      var html_select_localizaciones = '<option value="">Seleccione</option>';
      localizaciones.forEach(function (loc) {
      if (loc.id_area == area) {
        var selected = (loc.id == localizacion) ? "selected" : "";
        html_select_localizaciones += `<option value="${loc.id}" ${selected}>${loc.nombre}</option>`;
      }
      });
      $('#localizacion_editar').html(html_select_localizaciones);

      // Evento para actualizar localizaciones cuando cambia el área
      $('#area_editar').on('change', function () {
      var selectedOption = $(this).val();
      var filteredLocalizaciones = localizaciones.filter(loc => loc.id_area == selectedOption);

      var html_select_localizaciones = '<option value="">Seleccione</option>';
      filteredLocalizaciones.forEach(function (loc) {
        var selected = (loc.id == localizacion) ? "selected" : "";
        html_select_localizaciones += `<option value="${loc.id}" ${selected}>${loc.nombre}</option>`;
      });

      $('#localizacion_editar').html(html_select_localizaciones);
      $('#div_localizacion').css('display', selectedOption ? "block" : "none");
      });


      closeButton.on('click', function () {
      myModal.hide();
      });
    });
    }

  </script>
@endpush