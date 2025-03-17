<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <style>
    /* Estilo para controlar la paginación */
    @media print {
      .pagina {
        page-break-before: always;
      }

      .pagina:first-of-type {
        page-break-before: auto;
        /* No insertar salto de página antes de la primera */
      }

      table {
        width: 100%;
        border-collapse: collapse;
      }

      th,
      td {
        border: 1px solid black;
        padding: 10px;
        text-align: center;
      }
    }
  </style>
</head>

<body>
  <!-- Contenedor principal -->
  @if (empty($registeredChunks) || count($registeredChunks) === 0)
    <!-- Si inscriptosChunks es null o está vacío, mostrar este contenido -->
    <div class="pagina">
    <div class="contenedor" style="max-width: 900px; margin: 20px auto; padding: 20px;">
      <!-- Tabla -->
      <div class="tabla" style="width: 100%; border: 2px solid black; border-collapse: collapse; display: table;">
      <!-- Fila 1 -->
      <div class="fila-1" style="display: table-row; width: 100%; border-bottom: 2px solid black;">
        <div class="celda-izquierda"
        style="display: table-cell; width: 20%; border-right: none; padding: 2px; text-align: right; font-size: 20px;">
        <b>{{$annexed->valor_formulario}}</b>
        </div>
        <div class="celda-derecha"
        style="display: table-cell; width: 40%; border-left: none; padding: 2px; text-align: right; font-size: 20px;">
        <b>POE N°: {{$annexed->valor1}}</b>
        </div>
        <div class="celda-derecha"
        style="display: table-cell; width: 40%; border-left: none; padding: 2px; text-align: right; font-size: 20px;">
        </div>
      </div>

      <!-- Fila 2 -->
      <div class="fila-2" style="display: table-row; width: 80%;">
        <!-- Celda izquierda (Logo) -->
        <div class="celda-izquierda-2"
        style="display: table-cell; border-right: 2px solid black; box-sizing: border-box; height: 50px; vertical-align: middle; text-align: center;">
        <img src="{{ $imageBase64 }}" alt="Logo" width="100" height="50" />
        </div>

        <!-- Celda central -->
        <div class="celda-central"
        style="display: table-cell; width: 50%; padding: 5px; border-right: 2px solid black; text-align: center; font-size: 20px; box-sizing: border-box;">
        <b>{{$annexed->valor2}}</b>
        </div>

        <!-- Celda derecha -->
        <div class="celda-derecha-2"
        style="display: table-cell; position: relative; padding: 2px; text-align: center; font-size: 20px; width: 30%;">
        <div class="arriba" style="margin-bottom: 2px;">
          <b>Version N°: {{$annexed->valor3}}</b>
        </div>

        <div class="linea-horizontal" style="position: relative; border-bottom: 2px solid black;"></div>

        <div class="abajo" style="margin-top: 5px;">
          <b>Pagina N°: {{$annexed->valor4}}</b>
        </div>
        </div>
      </div>
      </div>

      <!-- Nuevo div con el label -->
      <div class="procedimiento-div"
      style="margin-top: 20px; padding: 10px; border: 1px solid black; position: relative; text-align: left; font-size: 20px;">
      <b>Id y Nombre del Procedimiento:</b>
      </div>

      <!-- Nueva tabla debajo -->
      <table class="tabla-datos" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
      <thead>
        <tr style="border: 1px solid black;">
        <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;"><b>Fecha</b></th>
        <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;"><b>Apellido y Nombre del Entrenado</b></th>
        <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;"><b>Firma
          Entrenado</b></th>
        <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;"><b>Firma
          Entrenador</b></th>
        <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;">
          <b>Observaciones</b>
        </th>
        </tr>
      </thead>
      <tbody>
        <tr>
        <td colspan="5" style="text-align: center; font-size: 18px; padding: 20px;">
          <b>No hay datos disponibles.</b>
        </td>
        </tr>
      </tbody>
      </table>
    </div>
    </div>
  @else
    <!-- Si inscriptosChunks tiene datos, muestra la tabla con los inscritos -->
    @foreach ($registeredChunks as $paginaInscriptos)
    <div class="pagina">
    <div class="contenedor" style="max-width: 900px; margin: 20px auto; padding: 20px;">
      <!-- Tabla -->
      <div class="tabla" style="width: 100%; border: 2px solid black; border-collapse: collapse; display: table;">
      <div class="fila-1" style="display: table-row; width: 100%; border-bottom: 2px solid black;">
      <div class="celda-izquierda"
      style="display: table-cell; width: 20%; border-right: none; padding: 2px; text-align: right; font-size: 20px;">
      <b>{{$annexed->valor_formulario}}</b>
      </div>
      <div class="celda-derecha"
      style="display: table-cell; width: 40%; border-left: none; padding: 2px; text-align: right; font-size: 20px;">
      <b>POE N°: {{$annexed->valor1}}</b>
      </div>
      <div class="celda-derecha"
      style="display: table-cell; width: 40%; border-left: none; padding: 2px; text-align: right; font-size: 20px;">
      </div>
      </div>

      <div class="fila-2" style="display: table-row; width: 80%;">
      <div class="celda-izquierda-2"
      style="display: table-cell; border-right: 2px solid black; box-sizing: border-box; height: 50px; vertical-align: middle; text-align: center;">
      <img src="{{ $imageBase64 }}" alt="Logo" width="100" height="50" />
      </div>
      <div class="celda-central"
      style="display: table-cell; width: 50%; padding: 5px; border-right: 2px solid black; text-align: center; font-size: 20px; box-sizing: border-box;">
      <b>{{$annexed->valor2}}</b>
      </div>
      <div class="celda-derecha-2"
      style="display: table-cell; position: relative; padding: 2px; text-align: center; font-size: 20px; width: 30%;">
      <div class="arriba" style="margin-bottom: 2px;">
        <b>Version N°: {{$annexed->valor3}}</b>
      </div>
      <div class="linea-horizontal" style="position: relative; border-bottom: 2px solid black;"></div>
      <div class="abajo" style="margin-top: 5px;">
        <b>Pagina N°: {{$annexed->valor4}}</b>
      </div>
      </div>
      </div>
      </div>

      <div class="procedimiento-div"
      style="margin-top: 20px; padding: 10px; border: 1px solid black; position: relative; text-align: left; font-size: 20px;">
      <b>Id y Nombre del Procedimiento:</b>
      </div>

      <table class="tabla-datos" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
      <thead>
      <tr style="border: 1px solid black;">
      <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 20px;">Fecha</th>
      <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;">Apellido y Nombre
        del Entrenado</th>
      <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;">Firma Entrenado
      </th>
      <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;">Firma Entrenador
      </th>
      <th style="border: 1px solid black; text-align: center; font-size: 17px; padding: 10px;">Observaciones</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($paginaInscriptos as $enrolamiento)
      <tr>
      <td style="border: 1px solid black; text-align: center; height:55px" class="fecha-inscripcion">
      @if(!empty($enrolamiento['persona']['nombre_p']) && !empty($enrolamiento['persona']['apellido']))
      {{ !empty($fechaSeleccionada) ? $fechaSeleccionada : ($enrolamiento['fecha_enrolamiento'] ?? '') }}
    @else
      {{ $enrolamiento['fecha_enrolamiento'] ?? '' }}
    @endif
      </td>
      <td style="border: 1px solid black; text-align: center;">
      {{ $enrolamiento['persona']['apellido'] ?? '' }} {{ $enrolamiento['persona']['nombre_p'] ?? '' }} 
      </td>
      <td style="border: 1px solid black; text-align: center;"></td>
      <td style="border: 1px solid black; text-align: center;"></td>
      <td style="border: 1px solid black; text-align: center;"></td>
      </tr>
    @endforeach
      </tbody>
      </table>
    </div>
    </div>
  @endforeach
  @endif


  @if(!empty($instance->id_instancia) && empty($is_pdf))
    <div class="centrado-horizontal">

    </div>
    <div style="text-align: center; margin-top: 20px;">
    <label for="fechaSeleccionada"><b>Seleccionar Fecha:</b></label>
    <input type="date" id="fechaSeleccionada" name="fechaSeleccionada"
      style="padding: 10px; font-size: 16px; margin-right: 10px;">
    <button type="button" id="actualizarFechas" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Actualizar
      Fechas</button>
    <form
      action="{{ route('cursos.generarPDF', ['formulario_id' => $annexed->formulario_id, 'cursoId' => $course->id, 'instanciaId' => $instance->id_instancia]) }}"
      method="GET">
      @csrf
      <input type="hidden" id="fechaSeleccionadaInput" name="fechaSeleccionada" value="">
      <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Generar PDF</button>
    </form>
    </div>

    <script>
    // Esperar a que el DOM se cargue completamente
    document.addEventListener("DOMContentLoaded", function () {
      document.getElementById("actualizarFechas").addEventListener("click", function () {
      // Obtener la fecha seleccionada
      var fechaSeleccionada = document.getElementById("fechaSeleccionada").value;

      // Verificar si se seleccionó una fecha
      if (fechaSeleccionada) {
        // Formatear la fecha al formato "día/mes/año"
        var fechaParts = fechaSeleccionada.split("-");
        var dia = fechaParts[2];
        var mes = fechaParts[1];
        var año = fechaParts[0];

        var fechaFormateada = dia + "/" + mes + "/" + año;

        console.log("Fecha seleccionada formateada: ", fechaFormateada); // Log para verificar la fecha

        // Obtener todas las celdas de la columna de fechas
        var celdasFecha = document.querySelectorAll(".fecha-inscripcion");

        // Verificar si se encontraron celdas
        console.log("Celdas encontradas: ", celdasFecha.length);

        if (celdasFecha.length === 0) {
        alert("No se encontraron celdas de fecha para actualizar.");
        }

        // Actualizar todas las celdas con la fecha seleccionada
        celdasFecha.forEach(function (celda, index) {
        console.log("Actualizando celda:", celda); // Log para verificar cada celda que se actualiza
        celda.textContent = fechaFormateada; // Actualiza la celda con la nueva fecha
        });

        // Asignar la fecha seleccionada al campo oculto para enviarla al controlador
        document.getElementById("fechaSeleccionadaInput").value = fechaFormateada;
      } else {
        alert("Por favor, seleccione una fecha.");
      }
      });
    });
    </script>

  @endif


</body>

</html>
<style>
  .centrado-horizontal {
    display: flex;
    justify-content: center;
  }

  .btn {
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
  }
</style>