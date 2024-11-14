<!DOCTYPE html>
<html>
<head>
  <style>
    /* Contenedor principal para centrar todo */
    .contenedor {
      max-width: 900px; /* Puedes ajustar este valor según el tamaño que desees */
      margin: 20px auto; /* Centra el contenedor y agrega un margen superior de 20px */
      padding: 20px; /* Relleno para darle espacio al contenedor */
    }

    /* Tabla con borde de 2px */
    .tabla {
      display: block;
      width: 100%;
      border: 2px solid black; /* Borde de 2px para toda la tabla */
      border-collapse: collapse; /* Asegura que las celdas no tengan espacio entre ellas */
    }

    /* Primer fila */
    .fila-1 {
      display: flex;
      border-bottom: 2px solid black; /* Borde inferior para separar las filas */
    }

    .celda-izquierda {
      flex: 4;
      border-right: 2px solid black; /* Borde derecho */
      padding: 10px;
    }

    .celda-derecha {
      flex: 2;
      padding: 10px;
      
      
      
    }

    /* Segunda fila */
    .fila-2 {
      display: flex;
      
    }

    .celda-izquierda-2 {
      flex: 1;
      border-right: 2px solid black; /* Borde derecho */
      padding: 10px;
    }

    .celda-central {
      flex: 8;
      padding: 35px;
      border-right: 2px solid black;
    }

    .celda-derecha-2 {
      flex: 3;
      padding: 10px;
      
      
    }

    .celda-derecha-2 div {
      padding: 10px;
      
      
    }

    .celda-derecha-2 .arriba,
    .celda-derecha-2 .abajo {
      position: relative;
      
    }

    .celda-derecha-2 .arriba::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      border-top: 2px solid black;
    }

    .procedimiento-div {
      margin-top: 20px;
      padding: 10px;
      position: relative;
    }

    .procedimiento-div label {
      color: black;
    }

    .procedimiento-div::after {
      content: "";
      position: absolute;
      top: 30px;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: -1;
    }

    .tabla-datos {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    /* Borde de 2px para las celdas de la tabla */
    .tabla-datos th,
    .tabla-datos td {
      border: 1px solid black; /* Borde de 2px para las celdas */
      padding: 5px;
      text-align: left;
      height: 30px;
    }

    .tabla-datos th {
      font-weight: bold;
      padding: 15px;  
    }
    
    /* Estilo para las celdas de las columnas de Fecha y Nombre y Apellido */
    .tabla-datos td:nth-child(1), /* Primera columna (Fecha) */
    .tabla-datos td:nth-child(2) { /* Segunda columna (Nombre y Apellido) */
      background-color: #ADD8E6; /* Azul claro-celeste */
    }

  </style>
</head>
<body>

  <!-- Contenedor principal -->
  <div class="contenedor">
    <!-- Tabla -->
    <div class="tabla">
      <!-- Primera fila -->
      <div class="fila-1">
        <div class="celda-izquierda" style="text-align:center; font-size: 20px;"><b>{{$anexos->descripcion5 ?? $anexos->valor5}}</b></div>
        <div class="celda-derecha" style="font-size: 20px;"><b>POE N°: {{$anexos->descripcion1 ?? $anexos->valor1}}</b></div>
      </div>

      <!-- Segunda fila -->
      <div class="fila-2">
        <div class="celda-izquierda-2">
          <img src="{{ asset('storage/cursos/logo-lafedar.png') }}" alt="Logo" style="width:100px; height:auto;">
        </div>
        <div class="celda-central" style="text-align:center; font-size: 20px;"><b>{{$anexos->descripcion2 ?? $anexos->valor2}}</b></div>
        <div class="celda-derecha-2">
          <div class="arriba" style="text-align:center; font-size: 20px;"><b>Version N°: {{$anexos->descripcion3 ?? $anexos->valor3}}</b></div>
          <div class="abajo" style="text-align:center; font-size: 20px;"><b>Pagina N°: {{$anexos->descripcion4 ?? $anexos->valor4}}</b></div>
        </div>
      </div>
    </div>

    <!-- Nuevo div con el label -->
    <div class="procedimiento-div" style="border: 1px solid black;">
      <label for="id-nombre" style="text-align:center; font-size: 20px;"><b>Id y Nombre del Procedimiento:</b></label>
    </div>

    <!-- Nueva tabla debajo -->
    <table class="tabla-datos" >
      <thead >
        <tr>
          <th style="text-align:center; font-size: 17px;" >Fecha</th>
          <th style="text-align:center; font-size: 17px;">Nombre y Apellido del Entrenado</th>
          <th style="text-align:center; font-size: 17px;">Firma Entrenado</th>
          <th style="text-align:center; font-size: 17px;">Firma Entrenador</th>
          <th style="text-align:center; font-size: 17px;">Observaciones</th>
        </tr>
      </thead>
      <tbody>
        
      
  @foreach ($inscriptos as $enrolamiento)
    <tr>
      <td style="text-align:center">{{ $enrolamiento->fecha_enrolamiento ? $enrolamiento->fecha_enrolamiento : 'No disponible' }}</td>
      <td style="text-align:center">{{ $enrolamiento->persona->nombre_p }} {{ $enrolamiento->persona->apellido }}</td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  @endforeach

 
      </tbody>
    </table>

  </div>
  <form action="{{ route('cursos.generarPDF', ['instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id]) }}" method="GET">
    @csrf
    <button type="submit" class="btn btn-primary">Generar PDF</button>
</form>
</body>
</html>
