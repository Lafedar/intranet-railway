@extends('layouts.app')
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Internos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>

  <div id="custom-container-internos">
    <!-- Buscador -->
    <div id="filtro-internos">
      <input type="text" class="form-control" id="search" placeholder="Buscar por nombre, apellido, área...">
    </div>


    <!-- Tabla de Internos -->
    <div id="tabla-internos">
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
        <td><a href="mailto:{{ $persona->correo }}">{{ $persona->correo }}</a></td>
        </tr>
      @endforeach
        </tbody>
      </table>

      <!-- Paginación -->
      <div>
        {{ $personas->links() }}
      </div>
    </div>
  </div>
  
  <footer>
        <p>Laboratorio Lafedar S.A. | Laboratorios Federales Argentinos S.A </p>
    </footer>

  <script>
    $(document).ready(function () {
      // Filtro de búsqueda en la tabla
      $("#search").keyup(function () {
        var searchTerm = $(this).val().toLowerCase();
        $("#test tbody tr").each(function () {
          var rowText = $(this).text().toLowerCase();
          if (rowText.indexOf(searchTerm) === -1) {
            $(this).hide();
          } else {
            $(this).show();
          }
        });
      });
    });
  </script>
</body>

</html>