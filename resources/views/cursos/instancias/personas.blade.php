@extends('layouts.app')


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción a la capacitación</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap&italic=true" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cursos.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


</head>


<body>
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success" id="success">
                {{ session('success') }}
            </div>
        @endif
        @if(session()->has('inscripcion_desde_excel') && session()->has('archivo_descargable'))
                <div class="alert alert-info" id="archivo-descargable">
                    El archivo de personas no correspondientes ha sido generado. Puedes descargarlo ahora:
                    <a href="{{ asset('storage/' . session('archivo_descargable')) }}" class="btn btn-secondary" download>
                        Descargar archivo
                    </a>
                </div>
                @php
                    // Eliminar la variable de sesión después de mostrar el mensaje
                    session()->forget('inscripcion_desde_excel');
                @endphp
        @endif



        @if(session('error'))
            <div class="alert alert-danger" id="danger">
                {{ session('error') }}
            </div>
        @endif


        <div id="encabezados">
            <h1 id="titulo-personas">Inscripción para la capacitación: {{ $curso->titulo }}</h1>

        </div>
        <h5 id="cupo">Cupo disponible: <span id="cupoDisponible">{{ $restantes }}</span></h5>

        <div class="form-group">

            <input type="text" id="filtro" class="form-control"
                placeholder="Filtrar por Nombre, Apellido, Area o Legajo" autocomplete="off" style="width: 366px">
        </div>


        <div class="button-link-container">
            <a id="icono" title="Descargar instructivo sobre importación de inscriptos"
                href="{{ asset('instructivos/instructivo_importacion_inscriptos.docx') }}" download>
                <img src="{{ asset('storage/Imagenes-principal-nueva/info.png') }}" alt="Info" id="img-icono"
                    style="margin-left: 1370px;">
            </a>



            <a href="{{ asset('plantillas/plantilla.xlsx') }}" download class="download-link">
                Descargar Plantilla Excel
            </a>
            @if($restantes != 0)
                <form id="excelForm"
                    action="{{ route('inscribir.excel', ['instancia_id' => $instancia->id_instancia, 'cursoId' => $curso->id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="excel_file" accept=".xlsx,.xls" id="excel_file" style="display:none;"
                        onchange="submitForm()">
                    <button type="button" id="BI" onclick="document.getElementById('excel_file').click()">Cargar
                        Excel</button>
                </form>
            @else
                <form id="excelForm"
                    action="{{ route('inscribir.excel', ['instancia_id' => $instancia->id_instancia, 'cursoId' => $curso->id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="excel_file" accept=".xlsx,.xls" id="excel_file" style="display:none;"
                        onchange="submitForm()">
                    <button type="button" id="btn-excel-disabled"
                        onclick="document.getElementById('excel_file').click()">Cargar
                        Excel</button>
                </form>
            @endif
        </div>

        <form
            action="{{ route('inscribir.varias.personas', ['instancia_id' => $instancia->id_instancia, 'cursoId' => $curso->id]) }}"
            method="POST">
            @csrf
            <table>
                <thead>
                    <button type="submit" class="btn btn-primary"
                        style="margin-bottom: 10px; margin-right: 10px; margin-top: -70px" id="btn-agregar">Inscribir
                        seleccionados</button>
                    <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}"
                        class="btn btn-secondary" id="asignar-btn" style="margin-top:-80px;">Volver</a>

                    <tr>
                        <th>Legajo</th>
                        <th>Apellido y Nombre</th>
                        <th>Área</th>
                        <th>Inscribir</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($personasConEstado as $persona)
                                    <tr>
                                        <td>{{ $persona->legajo }}</td>
                                        <td>{{ $persona->apellido }} {{ $persona->nombre_p }}</td>
                                        <td>
                                            @if($persona->area)
                                                {{ $persona->area ? $persona->area->nombre_a : 'Sin área asignada' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td>
                                            @if($persona->estadoEnrolado)
                                                <p>Ya inscripto</p>
                                            @else

                                                <input type="checkbox" class="persona-checkbox" name="personas[{{ $persona->id_p }}]"
                                                    value="1">
                                            @endif
                                        </td>
                        </form>
                        <td>
                            @if($persona->estadoEnrolado)

                                <form
                                    action="{{ route('desinscribir', ['userId' => $persona->id_p, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id]) }}"
                                    method="POST" onsubmit="return confirm('¿Estás seguro de que deseas desuscribir a esta persona ?');">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" id="icono" title="Desuscribir"><img
                                            src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar" id="img-icono"></button>

                                </form>
                            @else
                                N/A
                            @endif
                        </td>
                        </tr>
                    @endforeach
        </tbody>
        </table>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>


</body>

</html>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        // Función para actualizar el cupo disponible
        function actualizarCupo() {
            var totalSeleccionados = $("input[name^='personas']:checked").length;
            var cupoMaximo = {{ $restantes }};  // El valor del cupo original
            var cupoDisponible = cupoMaximo - totalSeleccionados;
            $("#cupoDisponible").text(cupoDisponible);

            // Si el cupo es 0, deshabilitar solo los checkboxes que no están seleccionados
            if (cupoDisponible <= 0) {
                // Deshabilitar los checkboxes no seleccionados
                $("input[name^='personas']:not(:checked)").prop('disabled', true);
                $("#cupoDisponible").css('color', 'red'); // Cambiar color a rojo cuando el cupo sea 0
            } else {
                // Habilitar todos los checkboxes si hay cupo
                $("input[name^='personas']").prop('disabled', false);
                $("#cupoDisponible").css('color', ''); // Restaurar el color original
            }
        }

        // Llamar a la función al cargar la página por si ya hay checkboxes seleccionados
        actualizarCupo();

        // Escuchar el cambio en los checkboxes
        $("input[name^='personas']").change(function () {
            actualizarCupo();
        });

        // Filtrado en tiempo real
        $('#filtro').on('input', function () {
            var filtro = $(this).val().toLowerCase();

            // Iterar sobre las filas de la tabla
            $('table tbody tr').each(function () {
                var nombreApellido = $(this).find('td:nth-child(2)').text().toLowerCase(); // Segunda columna
                var legajo = $(this).find('td:nth-child(1)').text().toLowerCase(); // Primera columna
                var area = $(this).find('td:nth-child(3)').text().toLowerCase(); // Tercera columna

                // Si el filtro no coincide ni con nombre/apellido ni con legajo, ocultar la fila
                if (nombreApellido.indexOf(filtro) === -1 && legajo.indexOf(filtro) === -1 && area.indexOf(filtro) === -1) {
                    $(this).hide();  // Si no coincide, ocultar la fila
                } else {
                    $(this).show();  // Si coincide, mostrar la fila
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Ocultar los mensajes de éxito y error después de 3 segundos
        setTimeout(function () {
            $('.alert').fadeOut('slow'); // 'slow' es la duración de la animación
        }, 5000); // 3000 milisegundos = 3 segundos
    });
</script>
<script>
    function submitForm() {
        document.getElementById('excelForm').submit();  // Enviar el formulario cuando se selecciona el archivo
    }

</script>