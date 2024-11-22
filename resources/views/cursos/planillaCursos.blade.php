<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
</head>
<body>


  <!-- Contenedor principal -->
  <div class="contenedor" style="max-width: 900px; margin: 20px auto; padding: 20px;">
    <!-- Tabla -->
    <div class="tabla" style="width: 100%; border: 2px solid black; border-collapse: collapse; display: table;">
      <!-- Fila 1 -->
      <div class="fila-1" style="display: table-row; width: 100%; border-bottom: 2px solid black;">
        <div class="celda-izquierda" style="display: table-cell; width: 20%; border-right: none; padding: 2px; text-align: right; font-size: 20px;">
        
        <b>{{$anexo->valor_formulario}}</b>
        </div>
        <div class="celda-derecha" style="display: table-cell; width: 40%; border-left: none; padding: 2px; text-align: right; font-size: 20px;">
          <b>POE N°: {{$anexo->valor1}}</b>
        </div>
        <div class="celda-derecha" style="display: table-cell; width: 40%; border-left: none; padding: 2px; text-align: right; font-size: 20px;">
        </div>
      </div>
  
      <!-- Fila 2 -->
      <div class="fila-2" style="display: table-row; width: 80%;">  <!-- Cambié el ancho de la fila -->
        <!-- Celda izquierda (Logo) -->
        <div class="celda-izquierda-2" style="display: table-cell; border-right: 2px solid black; box-sizing: border-box; height: 50px; vertical-align: middle; text-align: center;">
  <img src="{{ $imageBase64 }}" alt="Logo" width="100" height="70" />
</div>




  
        <!-- Celda central -->
        <div class="celda-central" style="display: table-cell; width: 50%; padding: 5px; border-right: 2px solid black; text-align: center; font-size: 20px; box-sizing: border-box;">
          <b>{{$anexo->valor2}}</b>
        </div>
  
        <!-- Celda derecha -->
        <div class="celda-derecha-2" style="display: table-cell; position: relative; padding: 2px; text-align: center; font-size: 20px; width: 30%;"> <!-- Cambié el ancho de esta celda -->
          <!-- Contenedor para el texto "Version N°" -->
          <div class="arriba" style="margin-bottom: 2px;">
            <b>Version N°: {{$anexo->valor3}}</b>
          </div>
  
          <!-- Línea horizontal centrada en la celda -->
          <div class="linea-horizontal" style="position: relative; border-bottom: 2px solid black;"></div>
  
          <!-- Contenido inferior -->
          <div class="abajo" style="margin-top: 5px;">
            <b>Pagina N°: {{$anexo->valor4}}</b>
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
    @if ($enrolamiento->evaluacion === 'Aprobado')
        <tr>
            <td style="border: 1px solid black; text-align: center; height:30px">
                {{ $enrolamiento->fecha_enrolamiento ? $enrolamiento->fecha_enrolamiento : 'No disponible' }}
            </td>
            <td style="border: 1px solid black; text-align: center;">
                {{ $enrolamiento->persona->nombre_p }} {{ $enrolamiento->persona->apellido }}
            </td>
            <td style="border: 1px solid black; text-align: center;"></td>
            <td style="border: 1px solid black; text-align: center;"></td>
            <td style="border: 1px solid black; text-align: center;"></td>
        </tr>
    @endif
@endforeach

      </tbody>
    </table>
    <br><br><br>
    @if(!empty($instancia->id_instancia))
      <form action="{{ route('cursos.generarPDF', ['formulario_id' => $anexo->formulario_id, 'cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia]) }}" method="GET">
        @csrf
        <button type="submit" class="btn btn-primary">Generar PDF</button>
    
      </form>
  @endif
  </div>

  

</body>
</html>


