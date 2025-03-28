@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/createOptimized.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    @if (session('success'))
        <div class="alert alert-success" style="margin-top: 80px; text-align: center;" id="successMessage">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger text-center">
            <ul class="list-unstyled">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="cursos-create-container">
        <h1 class="mb-4 text-center">Crear Instancia</h1>
        <form id="cursoForm" action="{{ route('courses.instances.optmizedStore', ['course' => ':course']) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="form-group d-flex justify-content-between">
                <div class="mr-2">
                    <label for="titulo"><b>ID</b></label>
                    <select class="form-control" id="course" name="course" required>
                        <option value="">Seleccione una capacitacion</option>
                        @foreach($courses as $course)
                            <option value="{{ $course['id'] }}">
                                {{ $course['titulo'] }}
                            </option>
                        @endforeach
                    </select>
                    <a href="javascript:void(0);" id="toggle-capacitacion">Crear Capacitación</a>


                </div>

                <div class="flex-fill mr-2">
                    <label for="start_date"><b>Fecha Inicio</b></label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="flex-fill">
                    <label for="trainer"><b>Capacitador</b></label>
                    <select class="form-control" id="trainer" name="trainer" required>
                        <option value="">Seleccione un capacitador</option>
                        @foreach($persons as $person)
                            <option value="{{ $person->nombre_p }} {{ $person->apellido }}">
                                {{ $person->apellido }} {{ $person->nombre_p }}
                            </option>
                        @endforeach
                    </select>
                    <a href="javascript:void(0);" id="anotherTrainerLink">Otro capacitador</a>
                    <a href="javascript:void(0);" id="closeTrainerLink" style="display: none;">Cerrar</a>

                </div>
            </div>


            <div id="anotherTrainerInput" style="display: none; margin-left: 1480px;">
                <label for="another_trainer"><b>Escribe el nombre del capacitador</b></label>
                <input type="text" class="form-control" id="another_trainer" name="another_trainer" maxlength="60" style="width: 400px;">
            </div>


    </div>

    <br><br><br>


    <div class="row no-gutters" id="container-to-registering-people">

        <div class="col-md-6 d-flex flex-column" style="width: 50vw; padding: 20px;">
            <h3 class="mb-4 text-center">Inscribir Personas</h3>
            <div class="form-group">
                <input type="text" id="filtro" class="form-control"
                    placeholder="Filtrar por Nombre, Apellido, Área o Legajo" autocomplete="off">
            </div>
            <div class="d-inline-block">
                <input type="checkbox" name="mail"> <label for="mail" id="mail"><b>Enviar Mail a los
                        inscriptos</b></label>
            </div>
            <div class="table-responsive flex-grow-1">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Legajo</th>
                            <th>Apellido y Nombre</th>
                            <th>Área</th>
                            <th>Inscribir</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($persons as $person)
                            <tr>
                                <td>{{ $person->legajo }}</td>
                                <td>{{ $person->apellido }} {{ $person->nombre_p }}</td>
                                <td>{{ $person->area->nombre_a ?? 'N/A' }}</td>
                                <td>
                                    @if($person->estadoEnrolado)
                                        <p>Ya inscripto</p>
                                    @else
                                        <input type="checkbox" class="persona-checkbox" name="personas[{{ $person->id_p }}]"
                                            value="1">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center" style="margin-top: 50px;">
                <a href="{{ route('cursos.index') }}" id="asignar-btn">Cancelar</a>
                <button type="submit" id="asignar-btn">Crear Instancia</button>
            </div>
        </div>
        </form>

        <div class="col-md-6 d-flex flex-column" style="width: 50vw; padding: 20px;">
            <div id="capacitacion-form" style="display: none;">
                <h3 class="mb-4 text-center">Crear Capacitacion</h3>
                <form id="cursoForm" action="{{ route('course.optimizedStore') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="titulo"><b>ID</b></label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="252">
                        <small id="titulo-count" class="form-text text-muted">Quedan 252 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label for="area"><b>Áreas</b></label><br>
                        <div class="row">
                            @foreach($areas as $index => $area)
                                    @if($index % 4 == 0 && $index != 0)
                                        </div>
                                        <div class="row">
                                    @endif
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="area_{{ $area->id_a }}"
                                                name="area[]" value="{{ $area->id_a }}">
                                            <label class="form-check-label"
                                                for="area_{{ $area->id_a }}">{{ $area->nombre_a }}</label>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                        <div class="text-center">
                            <a href="javascript:void(0);" class="cancelar-btn" id="asignar-btn">Cancelar</a>
                            <button type="submit" id="asignar-btn">Crear Capacitación</button>
                        </div>
                </form>
            </div>
        </div>

    </div>

@endsection




@push('scripts')
    <script>
        $(document).ready(function () {
            $('#toggle-capacitacion').click(function () {
                // Alternar la visibilidad del bloque
                $('#capacitacion-form').toggle();

                // Cambiar el texto del enlace según la visibilidad del bloque
                if ($('#capacitacion-form').is(':visible')) {
                    $(this).text('Cerrar');  // Cambiar a "Cerrar" cuando el bloque esté visible
                } else {
                    $(this).text('Crear Capacitación');  // Cambiar de vuelta a "Capacitación" cuando el bloque esté oculto
                }
            });

            $('.cancelar-btn').click(function () {
                $('#capacitacion-form').hide(); // Ocultar el bloque
                $('#toggle-capacitacion').text('Crear Capacitación'); // Cambiar el texto de "Capacitación"
            });
        });

    </script>


    <script>
        document.getElementById('cursoForm').addEventListener('submit', function (e) {
            var courseValue = document.getElementById('course').value;
            // Reemplaza ":course" en la ruta con el valor seleccionado
            var actionUrl = this.action.replace(':course', courseValue);
            this.action = actionUrl;
        });
    </script>
    <!-- Incluyendo Select2 (si usas un CDN, por ejemplo) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Inicia Select2 en tu select -->
    <script>
        $(document).ready(function () {
            $('#area').select2();
        });
    </script>

    <script>
        $(document).ready(function () {
            // Inicializar el select2 si lo necesitas
            $('.select2').select2();

            // Obtener el checkbox "Todas las Áreas"
            const selectAllCheckbox = document.querySelector('input[type="checkbox"][value="tod"]');

            // Obtener todos los checkboxes de áreas
            const areaCheckboxes = document.querySelectorAll('.area-checkbox');

            // Evento cuando se cambia el estado del checkbox "Todas las Áreas"
            selectAllCheckbox.addEventListener('change', function () {
                // Si se selecciona "Todas las Áreas", seleccionar todos y deshabilitar los demás checkboxes
                if (this.checked) {
                    areaCheckboxes.forEach(function (checkbox) {
                        checkbox.checked = true; // Marcar todos
                        checkbox.disabled = true; // Deshabilitar los demás
                    });
                } else {
                    areaCheckboxes.forEach(function (checkbox) {
                        checkbox.checked = false; // Deseleccionar todos
                        checkbox.disabled = false; // Habilitar los demás
                    });
                }
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Ocultar el mensaje de éxito después de 3 segundos
            setTimeout(function () {
                $('#successMessage').fadeOut('slow');
            }, 3000);

            // Ocultar el mensaje de error después de 3 segundos
            setTimeout(function () {
                $('#errorMessage').fadeOut('slow');
            }, 3000);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Escuchar el cambio en el campo 'fecha_inicio'
            const fechaInicio = document.getElementById('start_date');
            const fechaFin = document.getElementById('end_date');

            // Si el campo fecha_fin está vacío, asignamos el valor de fecha_inicio
            fechaInicio.addEventListener('input', function () {
                if (!fechaFin.value) {  // Solo actualizamos 'fecha_fin' si está vacío
                    fechaFin.value = fechaInicio.value;
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectCapacitador = document.getElementById('trainer');
            const otroLink = document.getElementById('anotherTrainerLink');
            const cerrarLink = document.getElementById('closeTrainerLink');
            const inputOtroCapacitador = document.getElementById('anotherTrainerInput');
            const otroCapacitadorInput = document.getElementById('another_trainer');

            // Mostrar el input cuando se hace clic en el enlace "Otro"
            otroLink.addEventListener('click', function () {
                // Cambiar el valor del select a la opción predeterminada
                selectCapacitador.value = ""; // Esto selecciona la opción "Seleccione un capacitador"

                inputOtroCapacitador.style.display = 'block'; // Mostrar el input
                selectCapacitador.disabled = true; // Bloquear el select
                cerrarLink.style.display = 'inline'; // Mostrar el botón "Cerrar"
                otroLink.style.display = 'none'; // Ocultar el enlace "Otro"

                // Limpiar el campo de texto
                otroCapacitadorInput.value = '';
            });

            // Mostrar el input cuando se selecciona un capacitador del select
            selectCapacitador.addEventListener('change', function () {
                if (selectCapacitador.value !== "") {
                    inputOtroCapacitador.style.display = 'none'; // Ocultar el input si no es "Otro"
                    selectCapacitador.disabled = false; // Desbloquear el select
                    cerrarLink.style.display = 'none'; // Ocultar el botón "Cerrar"
                    otroLink.style.display = 'inline'; // Mostrar el enlace "Otro"
                } else {
                    inputOtroCapacitador.style.display = 'none'; // Ocultar el input si no se elige un capacitador
                    cerrarLink.style.display = 'none'; // Ocultar el botón "Cerrar"
                }
            });

            // Cuando se hace clic en el botón "Cerrar"
            cerrarLink.addEventListener('click', function () {
                inputOtroCapacitador.style.display = 'none'; // Ocultar el input de "Otro"
                selectCapacitador.disabled = false; // Habilitar el select
                otroLink.style.display = 'inline'; // Mostrar el enlace "Otro"
                cerrarLink.style.display = 'none'; // Ocultar el botón "Cerrar"
            });

            // Antes de enviar el formulario, asignamos el valor del input de "Otro" al campo de capacitador
            document.querySelector('form').addEventListener('submit', function (event) {
                // Si el input "Otro" es visible y tiene valor, lo asignamos al select
                if (inputOtroCapacitador.style.display === 'block' && otroCapacitadorInput.value.trim() !== "") {
                    // Asignamos el valor del input al select antes de enviar
                    selectCapacitador.value = otroCapacitadorInput.value.trim();
                }
            });
        });

    </script>
    <script>
        // Función para limitar la longitud del input a 11 caracteres
        function limitInputLength(input) {
            if (input.value.length > 9) {
                input.value = input.value.slice(0, 9);
            }
        }
    </script>
    <script>
        // Obtener los elementos de los radio buttons y el input de examen
        const examenInput = document.getElementById("examInput");
        const certificadoAprobacion = document.getElementById("approval_certificate");
        const certificadoParticipacion = document.getElementById("participation_certificate");

        // Función que activa o desactiva el campo 'examen' según la opción seleccionada
        function toggleExamenField() {
            if (certificadoParticipacion.checked) {
                examenInput.disabled = true;  // Desactivar input cuando 'Participacion' está seleccionado
            } else {
                examenInput.disabled = false;  // Habilitar input cuando 'Aprobacion' está seleccionado
            }
        }

        // Ejecutar la función cada vez que cambie el estado de los radios
        certificadoAprobacion.addEventListener("change", toggleExamenField);
        certificadoParticipacion.addEventListener("change", toggleExamenField);

        // Llamar la función al cargar la página para que el estado inicial sea correcto
        window.onload = function () {
            toggleExamenField();
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>




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


    <script>
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

    </script>


@endpush