@extends('layouts.app')
@push('styles')
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush
@section('content')
<div id="internos-container">
  <!-- Buscador -->
  <div id="filtro-internos" class="mb-3">
    <input type="text" class="form-control" id="search" placeholder="Buscar por nombre, apellido, área...">
  </div>

  <!-- Tabla de Internos -->
  <div id="tabla-internos" class="table-responsive">
    <table id="test">
      <thead>
        <tr>
          <th>Interno</th>
          <th>Área</th>
          <th>Nombre / Localización</th>
          <th>Correo electrónico</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($localizaciones as $localizacion)
      <tr>
        <td>{{ $localizacion->interno }}</td>
        <td>{{ $localizacion->area }}</td>
        <td>{{ $localizacion->nombre }}</td>
        <td></td>
      </tr>
    @endforeach
        @foreach($personas as $persona)
      <tr>
        <td>{{ $persona->interno }}</td>
        <td>{{ $persona->area }}</td>
        <td>{{ $persona->nombre . ' ' . $persona->apellido }}</td>
        <td>
        <a href="mailto:{{ $persona->correo }}">{{ $persona->correo }}</a>
        </td>
      </tr>
    @endforeach
      </tbody>
    </table>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
      {{ $personas->links() }}
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function () {
    // Filtro de búsqueda en la tabla
    $("#search").keyup(function () {
      var searchTerm = $(this).val().toLowerCase();
      $("#test tbody tr").each(function () {
      var rowText = $(this).text().toLowerCase();
      $(this).toggle(rowText.indexOf(searchTerm) !== -1);
      });
    });
    });
  </script>
@endpush