<!DOCTYPE html>
<html>
<head>
 
</head>
<body>

  <!-- Contenedor principal -->
  <div class="contenedor" style="max-width: 900px; margin: 20px auto; padding: 20px;">
    <!-- Tabla -->
    <div class="tabla" style="width: 100%; border: 2px solid black; border-collapse: collapse; display: table;">
      <!-- Fila 1 -->
      <div class="fila-1" style="display: table-row; width: 100%; border-bottom: 2px solid black;">
        <div class="celda-izquierda" style="display: table-cell; width: 20%; border-right: none; padding: 2px; text-align: right; font-size: 20px;">
          <b>LAFEDAR</b>
        </div>
        <div class="celda-derecha" style="display: table-cell; width: 40%; border-left: none; padding: 2px; text-align: right; font-size: 20px;">
          <b>POE N°: POE</b>
        </div>
        <div class="celda-derecha" style="display: table-cell; width: 40%; border-left: none; padding: 2px; text-align: right; font-size: 20px;">
        </div>
      </div>
  
      <!-- Fila 2 -->
      <div class="fila-2" style="display: table-row; width: 80%;">  <!-- Cambié el ancho de la fila -->
        <!-- Celda izquierda (Logo) -->
        <div class="celda-izquierda-2" style="display: table-cell; border-right: 2px solid black; box-sizing: border-box; height: 50px; vertical-align: middle; text-align: center;">
  <img src="{{ $imageBase64 }}" alt="Logo" width="100" height="50" />
</div>




  
        <!-- Celda central -->
        <div class="celda-central" style="display: table-cell; width: 50%; padding: 5px; border-right: 2px solid black; text-align: center; font-size: 20px; box-sizing: border-box;">
          <b>Título</b>
        </div>
  
        <!-- Celda derecha -->
        <div class="celda-derecha-2" style="display: table-cell; position: relative; padding: 2px; text-align: center; font-size: 20px; width: 30%;"> <!-- Cambié el ancho de esta celda -->
          <!-- Contenedor para el texto "Version N°" -->
          <div class="arriba" style="margin-bottom: 2px;">
            <b>Version N°: Version</b>
          </div>
  
          <!-- Línea horizontal centrada en la celda -->
          <div class="linea-horizontal" style="position: relative; border-bottom: 2px solid black;"></div>
  
          <!-- Contenido inferior -->
          <div class="abajo" style="margin-top: 5px;">
            <b>Pagina N°: Hojas</b>
          </div>
        </div>
      </div>
    </div>
  
    <!-- Nuevo div con el label -->
    <div class="procedimiento-div" style="margin-top: 20px; padding: 10px; border: 1px solid black; position: relative; text-align: left; font-size: 20px;">
      <b>Id y Nombre del Procedimiento:</b>
    </div>
  
    <!-- Nueva tabla debajo -->
    <table class="tabla-datos" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
      <thead>
        <tr style="border: 1px solid black;">
          <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;">Fecha</th>
          <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;">Nombre y Apellido del Entrenado</th>
          <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;">Firma Entrenado</th>
          <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;">Firma Entrenador</th>
          <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;">Observaciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($inscriptos as $enrolamiento)
          <tr>
            <td style="border: 1px solid black; text-align: center; height:30px">{{ $enrolamiento->fecha_enrolamiento ? $enrolamiento->fecha_enrolamiento : 'No disponible' }}</td>
            <td style="border: 1px solid black; text-align: center;">{{ $enrolamiento->persona->nombre_p }} {{ $enrolamiento->persona->apellido }}</td>
            <td style="border: 1px solid black; text-align: center;"></td>
            <td style="border: 1px solid black; text-align: center;"></td>
            <td style="border: 1px solid black; text-align: center;"></td>
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
