@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/createOptimized.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
                <div class="mr-2" style="flex: 1;">
                    <label for="titulo"><b>Capacitación</b></label>
                    <select class="form-control" id="course" name="course" required>
                        <option value="">Seleccione una capacitación</option>
                        @foreach($courses as $course)
                            <option value="{{ $course['id'] }}">
                                {{ $course['titulo'] }}
                            </option>
                        @endforeach
                    </select>
                    <a href="javascript:void(0);" id="toggle-capacitacion">Crear Capacitación</a>
                </div>

                <div class="mr-2" style="flex: 1;">
                    <label for="start_date"><b>Fecha Inicio</b></label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>

                <div style="flex: 1;">
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

            <div id="anotherTrainerInput" style="display: none; margin-left: 1255px;">
                <label for="another_trainer"><b>Escribe el nombre del capacitador</b></label>
                <input type="text" class="form-control" id="another_trainer" name="another_trainer" maxlength="60"
                    style="width: 500px;">
            </div>


            <div class="row mt-4">

                <div class="d-flex justify-content-between">

                    <div class="col-md-6">
                        <h3 class="mb-4 text-center">Inscribir Personas</h3>
                        <form id="cursoForm"
                            action="{{ route('courses.instances.optmizedStore', ['course' => ':course']) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="text" id="filtro" class="form-control"
                                    placeholder="Filtrar por Nombre, Apellido, Área o Legajo" autocomplete="off">
                            </div>

                            <div class="d-inline-block">
                                <input type="checkbox" name="mail"> <label for="mail"><b>Enviar Mail a los
                                        inscriptos</b></label>
                            </div>

                            <div class="table-responsive">
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
                                                        <input type="checkbox" class="persona-checkbox"
                                                            name="personas[{{ $person->id_p }}]" value="1">
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center mt-4">
                                <a href="{{ route('cursos.index') }}" class="btn btn-secondary"
                                    id="asignar-btn">Cancelar</a>
                                <button type="submit" class="btn btn-primary" id="asignar-btn">Crear Instancia</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <h3 class="mb-4 text-center" id="capacitacion-title">Capacitación</h3>
                        <form id="capacitacionForm" action="{{ route('course.optimizedStore') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="titulo"><b>Título</b></label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="252"
                                    oninput="updateCharacterCount()" readonly>
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
                                                        name="area[]" value="{{ $area->id_a }}" style="pointer-events: none;">
                                                    <label class="form-check-label" style="pointer-events: none;"
                                                        for="area_{{ $area->id_a }}">{{ $area->nombre_a }}</label>
                                                </div>
                                            </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-center mt-4" style="display: none">
                                <a href="javascript:void(0);"  id="asignar-btn">Cancelar</a>
                                <button type="submit"  id="asignar-btn" >Crear Capacitación</button>
                            </div>
                        </form>
                    </div>
                </div>


@endsection



            @push('scripts')
            <script>
              document.addEventListener("DOMContentLoaded", function () {
    const toggleCapacitacion = document.getElementById("toggle-capacitacion");
    const tituloInput = document.getElementById("titulo");
    const areaCheckboxes = document.querySelectorAll(".form-check-input");
    const submitButton = document.querySelector("#capacitacionForm button[type='submit']");
    const cancelButton = document.querySelector("#capacitacionForm a.btn-secondary");
    const capacitacionTitle = document.getElementById("capacitacion-title");

    let isCreating = false; // Estado para controlar el texto y acciones

    toggleCapacitacion.addEventListener("click", function () {
        isCreating = !isCreating; // Alternar estado

        if (isCreating) {
            console.log("Modo Crear Capacitación activado");

            // Cambiar el texto del título
            capacitacionTitle.textContent = "Crear Capacitación";

            // Habilitar el campo de título y limpiar su contenido
            tituloInput.removeAttribute("readonly");
            tituloInput.value = "";

            // Habilitar y desmarcar todos los checkboxes de áreas
            areaCheckboxes.forEach((checkbox) => {
                checkbox.style.pointerEvents = "auto";
                checkbox.removeAttribute("disabled");
                checkbox.checked = false; // Desmarcar el checkbox
            });

            // Mostrar los botones
            [submitButton, cancelButton].forEach(button => {
                if (button) {
                    button.style.display = "inline-block";
                    button.style.visibility = "visible";
                    button.style.opacity = "1";
                }
            });
        } else {
            console.log("Modo Crear Capacitación desactivado");

            // Restaurar el texto original
            capacitacionTitle.textContent = "Capacitación";

            // Deshabilitar el campo de título y restaurar el contenido
            tituloInput.setAttribute("readonly", true);
            tituloInput.value = "";

            // Deshabilitar los checkboxes y desmarcarlos
            areaCheckboxes.forEach((checkbox) => {
                checkbox.style.pointerEvents = "none";
                checkbox.setAttribute("disabled", true);
                checkbox.checked = false;
            });

            // Ocultar los botones
            [submitButton, cancelButton].forEach(button => {
                if (button) {
                    button.style.display = "none";
                }
            });
        }
    });
});


            </script>
                <script>
                    $(document).ready(function () {
                        $('#course').change(function () {
                            var courseId = $(this).val();  // Obtener el ID del curso seleccionado

                            if (courseId) {
                                // Realizar una petición AJAX para obtener los detalles del curso seleccionado
                                $.ajax({
                                    url: '/courses/json/' + courseId,  // La URL de tu ruta de detalles del curso
                                    method: 'GET',
                                    success: function (response) {
                                        // Completar el campo Título
                                        $('#titulo').val(response.course.titulo);

                                        // Limpiar los checkboxes de áreas
                                        $('input[name="area[]"]').prop('checked', false);

                                        // Marcar las áreas correspondientes
                                        response.areas.forEach(function (area) {
                                            $('#area_' + area.id_a).prop('checked', true);
                                        });
                                    },
                                    error: function (xhr, status, error) {
                                        alert('Error al cargar los detalles del curso.');
                                    }
                                });
                            }
                        });
                    });
                </script>
                
                <script>
                    function updateCharacterCount() {
                        const tituloInput = document.getElementById('titulo');
                        const tituloCount = document.getElementById('titulo-count');
                        const remainingChars = 252 - tituloInput.value.length;
                        tituloCount.textContent = `Quedan ${remainingChars} caracteres`;
                    }
                </script>
                

                <script>
                    document.getElementById('cursoForm').addEventListener('submit', function (e) {
                        var courseValue = document.getElementById('course').value;
                        // Reemplaza ":course" en la ruta con el valor seleccionado
                        var actionUrl = this.action.replace(':course', courseValue);
                        this.action = actionUrl;
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