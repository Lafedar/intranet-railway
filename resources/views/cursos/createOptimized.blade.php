@extends('layouts.app')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/createOptimized.css') }}">
@endpush
@section('content')


    @if (session('success'))
        <div class="alert alert-success" id="message">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger text-center" id="message">
            <ul class="list-unstyled">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="cursos-create-container">
        <div id="instance-div">
            <h1 class="mb-4 text-center">Crear Instancia</h1>
            <form id="cursoForm" action="{{ route('courses.instances.optmizedStore', ['course' => ':course']) }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                <div class=" d-flex justify-content-between">
                    <div class="mr-2" id="flex1">
                        <label for="titulo"><b>Capacitación</b></label>
                        <select class="form-control" id="course" name="course" required>
                            <option value="">Seleccione una capacitación</option>
                            @foreach($courses as $course)
                                <option value="{{ $course['id'] }}" @if(old('course') == $course['id'] || isset($courseId) && $courseId == $course['id']) selected @endif>{{ $course['titulo'] }}
                                </option>
                            @endforeach
                        </select>



                        <a href="javascript:void(0);" id="toggle-capacitacion">Crear Capacitación</a>
                    </div>

                    <div class="mr-2" id="flex1">
                        <label for="start_date"><b>Fecha Inicio</b></label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="{{ old('start_date', $course->start_date ?? '') }}" required>
                    </div>

                    <div id="flex1">
                        <label for="trainer"><b>Capacitador</b></label>
                        <select class="form-control" id="trainer" name="trainer" required>
                            <option value="">Seleccione un capacitador</option>
                            @foreach($persons as $person)
                                <option value="{{ $person->nombre_p }} {{ $person->apellido }}" @if(old('trainer') == $person->nombre_p . ' ' . $person->apellido) selected @endif>
                                    {{ $person->apellido }} {{ $person->nombre_p }}
                                </option>
                            @endforeach
                        </select>
                        <a href="javascript:void(0);" id="anotherTrainerLink">Otro capacitador</a>
                        <a href="javascript:void(0);" id="closeTrainerLink">Cerrar</a>
                    </div>
                </div>

                <div id="anotherTrainerInput">
                    <label for="another_trainer"><b>Escribe el nombre del capacitador</b></label>
                    <input type="text" class="form-control" id="another_trainer" name="another_trainer" maxlength="60"
                        id="other-trainer">
                </div>

        </div>


        <div class="row mt-4">

            <div class="d-flex justify-content">

                <div class="col-md-6" id="enroll-div">
                    <h3 id="enroll-persons-h3">Inscribir Personas</h3>
                    <form id="cursoForm" action="{{ route('courses.instances.optmizedStore', ['course' => ':course']) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group" id="filter">
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
                                        <tr data-area="{{ $person->area->nombre_a ?? '' }}">
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
                        <input type="hidden" name="flag" id="flagInput" value="0">
                        <div id="enroll-buttons">
                            <a href="{{ route('cursos.index') }}" class="btn btn-secondary" id="asignar-btn">Cancelar</a>
                            <button type="submit" class="btn btn-primary" id="asignar-btn" onclick="setFlag(0)">Crear
                                Instancia</button>
                            <button type="submit" class="btn btn-primary" id="asignar-btn" onclick="setFlag(1)">Crear y
                                Agregar Nueva Instancia</button>
                        </div>
                    </form>
                </div>

                <div class="col-md-6" id="training-div">
                    <h3 class="mb-4 text-center" id="capacitacion-title">Capacitación</h3>
                    <form id="capacitacionForm" action="{{ route('course.optimizedStore') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" id="start_date_2" name="start_date">
                        <input type="hidden" id="trainer_2" name="trainer">
                        <div class="form-group">
                            <label for="titulo"><b>Título</b></label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="252"
                                oninput="updateCharacterCount()">
                            <small id="titulo-count" class="form-text text-muted">Quedan 252 caracteres</small>
                        </div>

                        <div class="form-group">
                            <label for="area"><b>Áreas</b></label><br>
                            <div class="row">
                                <!-- Checkbox "Todas las Áreas" -->
                                <div class="col-3">
                                    <div class="form-check">
                                        @foreach($areas as $area)
                                            @if($area->id_a == 'tod')
                                                <input type="checkbox" class="form-check-input" id="select-all-areas" name="area[]"
                                                    value="{{ $area->id_a }}">
                                                <label class="form-check-label" for="select-all-areas">{{$area->nombre_a}}</label>
                                            @endif
                                        @endforeach

                                    </div>
                                </div>

                                @foreach($areas as $index => $area)
                                        @if($area->id_a != 'tod')
                                                @if($index % 4 == 0 && $index != 0)
                                                    </div>
                                                    <div class="row">
                                                @endif
                                                <div class="col-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input area-checkbox"
                                                            id="area_{{ $area->id_a }}" name="area[]" value="{{ $area->id_a }}">
                                                        <label class="form-check-label"
                                                            for="area_{{ $area->id_a }}">{{ $area->nombre_a }}</label>
                                                    </div>
                                                </div>
                                        @endif
                                @endforeach

                            </div>

                        </div>
                        <input type="hidden" name="flag2" id="flagInput2" value="0">
                        <input type="hidden" id="hidden-course" name="course">

                        <div id="update-areas">
                            <button type="submit" id="asignar-btn" class="captureData" onclick="setFlag2(2)">Editar
                                Capacitación</button>
                        </div>
                        <div id="botones">
                            <a href="{{ route('cursos.createOptimized') }}" id="asignar-btn">Cancelar</a>

                            <button type="submit" id="asignar-btn" onclick="setFlag2(3)">Crear Capacitación</button>
                        </div>



                    </form>
                </div>
            </div>
        </div>


@endsection



    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                // Cuando se hace clic en el botón "Capturar Datos" de form1
                $('.captureData').click(function () {
                    // Capturamos los valores de los inputs de form1
                    var startDate = $('#start_date').val();
                    var trainer = $('#trainer').val();

                    // Asignamos esos valores a los campos ocultos en form2
                    $('#start_date_2').val(startDate);
                    $('#trainer_2').val(trainer);

                    // Enviar form2 al controlador
                    $('#capacitacionForm').submit();
                });
            });

        </script>
        <!--VALIDAR QUE AL MENOS SE SELECCIONE UN CHECK BOX DE AREAS-->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const form = document.getElementById("capacitacionForm");
                const selectAllCheckbox = document.getElementById("select-all-areas");
                const areaCheckboxes = document.querySelectorAll(".area-checkbox");
                const cancelButton = document.querySelector("#botones a");

                // Función para actualizar los checkboxes dependiendo de la selección de "Todas las Áreas"
                selectAllCheckbox.addEventListener("change", function () {
                    areaCheckboxes.forEach(checkbox => {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });

                // Evitar el envío del formulario si no se selecciona ningún área
                form.addEventListener("submit", function (event) {
                    // Primero, habilitar temporalmente los checkboxes deshabilitados que están marcados
                    areaCheckboxes.forEach(checkbox => {
                        if (checkbox.disabled && checkbox.checked) {
                            checkbox.disabled = false;  // Habilitar el checkbox marcado
                        }
                    });

                    // Verificar si al menos un checkbox está seleccionado
                    let isChecked = false;
                    areaCheckboxes.forEach(checkbox => {
                        if (checkbox.checked) {
                            isChecked = true; // Si hay al menos un checkbox marcado
                        }
                    });

                    // Si no hay ningún checkbox seleccionado ni "Todas las Áreas" marcada, previene el envío
                    if (!isChecked && !selectAllCheckbox.checked) {
                        event.preventDefault(); // Detiene el envío del formulario
                        alert("Por favor, selecciona al menos un área.");
                        return;  // Evita el envío del formulario si no se selecciona nada
                    }

                    // Si no se selecciona nada, marcar "Todas las Áreas" automáticamente
                    if (!isChecked) {
                        selectAllCheckbox.checked = true; // Marca "Todas las Áreas"
                    }

                    // Volver a deshabilitar los checkboxes que estaban deshabilitados antes
                    areaCheckboxes.forEach(checkbox => {
                        if (checkbox.disabled && checkbox.checked) {
                            checkbox.disabled = true;  // Deshabilitar los checkboxes que estaban deshabilitados
                        }
                    });
                });
            });
        </script>




        <!--OBTENGO EL VALOR DE ID CURSO PARA ENVIAR AL CONTROLADOR-->
        <script>
            // Actualiza el valor del campo oculto cada vez que el usuario cambie el valor en el select
            document.getElementById('course').addEventListener('change', function () {
                var courseValue = this.value;
                document.getElementById('hidden-course').value = courseValue;
            });
        </script>

        <!--BANDERA PARA REDIRECCIONAR AL CREAR UNA INSTANCIA-->
        <script>
            function setFlag(value) {
                document.getElementById('flagInput').value = value;
            }
        </script>
        <script>
            function setFlag2(value) {
                document.getElementById('flagInput2').value = value;
            }
        </script>

        <!-- DESACTIVAR Y ACTIVAR CREACION DE CURSOS -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const toggleCapacitacion = document.getElementById("toggle-capacitacion");
                const tituloInput = document.getElementById("titulo");
                const areaCheckboxes = document.querySelectorAll(".form-check-input");
                const submitButton = document.querySelector("#capacitacionForm button[type='submit']");
                const cancelButton = document.querySelector("#capacitacionForm a.btn-secondary");
                const capacitacionTitle = document.getElementById("capacitacion-title");
                const botonesDiv = document.getElementById("botones");
                const courseSelect = document.getElementById("course");
                const courseSelectLabel = document.querySelectorAll(".form-check-label");
                const enrollButtons = document.getElementById("enroll-buttons");
                const enrollDiv = document.getElementById("enroll-div");
                const startDate = document.getElementById("start_date");
                const trainer = document.getElementById("trainer");
                const anotherTrainer = document.getElementById("anotherTrainerLink");
                const instanceDiv = document.getElementById("instance-div");
                const updateAreas = document.getElementById("update-areas");



                let isCreating = false;
                let previousTitle = tituloInput.value;
                let previousCheckboxStates = new Map();


                areaCheckboxes.forEach((checkbox) => {
                    previousCheckboxStates.set(checkbox, checkbox.checked);
                });

                toggleCapacitacion.addEventListener("click", function () {
                    isCreating = !isCreating;

                    if (isCreating) {

                        toggleCapacitacion.textContent = "Cerrar";
                        // Cambiar el texto del título
                        capacitacionTitle.textContent = "Crear Capacitación";

                        // Habilitar el campo de título y limpiar su contenido
                        tituloInput.removeAttribute("readonly");
                        tituloInput.value = "";

                        // Habilitar y desmarcar todos los checkboxes de áreas
                        areaCheckboxes.forEach((checkbox) => {
                            checkbox.style.pointerEvents = "auto";
                            checkbox.removeAttribute("disabled");
                            checkbox.checked = false;
                        });

                        // Mostrar los botones del formulario
                        [submitButton, cancelButton].forEach(button => {
                            if (button) {
                                button.style.display = "inline-block";
                                button.style.visibility = "visible";
                                button.style.opacity = "1";
                            }
                        });

                        // Mostrar el div de los botones adicionales
                        botonesDiv.style.display = "block";
                        courseSelect.setAttribute("disabled", "true");

                        enrollDiv.disabled = true;
                        enrollDiv.classList.add("disabled");
                        startDate.setAttribute("disabled", "true");
                        trainer.setAttribute("disabled", "true");
                        anotherTrainer.classList.add("disabled");
                        instanceDiv.disabled = true;
                        instanceDiv.classList.add("disabled");
                        toggleCapacitacion.setAttribute("disabled", "false");
                        updateAreas.setAttribute("disabled", "true");
                        updateAreas.style.display = "none";



                    } else {

                        toggleCapacitacion.textContent = "Crear Capacitación";
                        // Restaurar el título original
                        capacitacionTitle.textContent = "Capacitación";
                        tituloInput.value = previousTitle;
                        tituloInput.setAttribute("readonly", true);

                        // Restaurar el estado de los checkboxes
                        areaCheckboxes.forEach((checkbox) => {
                            checkbox.style.pointerEvents = "none";
                            checkbox.checked = previousCheckboxStates.get(checkbox);
                        });

                        // Ocultar los botones del formulario
                        [submitButton, cancelButton].forEach(button => {
                            if (button) {
                                button.style.display = "none";
                            }
                        });

                        // Ocultar el div de los botones adicionales
                        botonesDiv.style.display = "none";
                        courseSelect.removeAttribute("disabled");

                        enrollDiv.disabled = false;
                        enrollDiv.classList.remove("disabled");
                        startDate.removeAttribute("disabled");
                        trainer.removeAttribute("disabled");
                        anotherTrainer.classList.remove("disabled");
                        instancelDiv.disabled = false;
                        instanceDiv.classList.remove("disabled");

                    }
                });
            });
        </script>


        <!-- DESACTIVAR CHECKBOXES DE ÁREAS CUANDO SE MARCA "TODAS LAS ÁREAS" -->
        <script>
            $(document).ready(function () {
                const selectAllCheckbox = $('#select-all-areas')[0];  // Checkbox "Todas las Áreas"
                const areaCheckboxes = $('.area-checkbox'); // Todos los checkboxes de área
                let courseAreasState = {}; // Objeto que guardará el estado original de los checkboxes del curso

                // Función para completar los datos del curso
                function completarDatosCurso(courseId) {
                    if (courseId === "") {
                        // Limpiar todos los checkboxes
                        areaCheckboxes.prop('checked', false).prop('disabled', false);
                        return;
                    }

                    $.ajax({
                        url: '/courses/json/' + courseId,
                        method: 'GET',
                        success: function (response) {
                            // Guardamos el estado de los checkboxes correspondientes al curso
                            const courseAreas = response.areas.map(area => 'area_' + area.id_a);
                            courseAreasState = {}; // Limpiar el estado previo

                            // Guardar el estado de los checkboxes del curso
                            areaCheckboxes.each(function () {
                                const areaId = $(this).attr('id');
                                if (courseAreas.includes(areaId)) {
                                    courseAreasState[areaId] = { checked: true, disabled: true };
                                } else {
                                    courseAreasState[areaId] = { checked: false, disabled: false };
                                }
                            });

                            // Marcar y deshabilitar los checkboxes del curso
                            response.areas.forEach(function (area) {
                                $('#area_' + area.id_a).prop('checked', true).prop('disabled', true);
                            });

                        },
                        error: function (xhr, status, error) {
                            alert('Error al cargar los detalles del curso.');
                        }
                    });
                }

                // Evento cuando se cambia el estado del checkbox "Todas las Áreas"
                $(selectAllCheckbox).change(function () {
                    if (this.checked) {
                        // Cuando se marca "Todas las Áreas", marcar todos los checkboxes habilitados y deshabilitar
                        areaCheckboxes.each(function () {
                            if (!$(this).prop('disabled')) {
                                $(this).prop('checked', true);  // Marcar
                                $(this).prop('disabled', true); // Deshabilitar
                            }
                        });
                    } else {
                        // Cuando se desmarca "Todas las Áreas", restaurar los checkboxes del curso
                        areaCheckboxes.each(function () {
                            const areaId = $(this).attr('id');
                            if (courseAreasState[areaId]) {
                                // Restaurar los checkboxes del curso (marcarlos y deshabilitarlos)
                                $(this).prop('checked', courseAreasState[areaId].checked);
                                $(this).prop('disabled', courseAreasState[areaId].disabled);
                            } else {
                                // Para los checkboxes que no son parte del curso, desmarcar y habilitar
                                $(this).prop('checked', false);
                                $(this).prop('disabled', false);
                            }
                        });
                    }
                });

                // Al cambiar la selección del curso
                $('#course').change(function () {
                    var courseId = $(this).val();
                    completarDatosCurso(courseId); // Llama a la función para completar los datos
                });

                // Si hay un curso previamente seleccionado al cargar la página, completa los datos
                var selectedCourseId = $('#course').val();
                if (selectedCourseId) {
                    completarDatosCurso(selectedCourseId); // Completa los datos del curso seleccionado
                }
            });
        </script>


        <!--COMPLETAR DATOS AL SELECCIONAR CURSO-->
        <script>
            $(document).ready(function () {
                // Función que se ejecuta cuando se selecciona un curso
                function completarDatosCurso(courseId) {
                    if (courseId === "") {
                        // Limpiar todos los checkboxes
                        $('input[name="area[]"]').prop('checked', false).prop('disabled', false); // Restablecer la capacidad de marcar/desmarcar
                        $('#titulo').val('');
                        $('#update-areas button').hide();
                    } else {
                        $.ajax({
                            url: '/courses/json/' + courseId,
                            method: 'GET',
                            success: function (response) {
                                $('#titulo').val(response.course.titulo);

                                // Limpiar los checkboxes de áreas
                                $('input[name="area[]"]').prop('checked', false).prop('disabled', false); // Restablecer

                                // Comprobar si el id_a "tod" está presente
                                var marcarTodos = response.areas.some(function (area) {
                                    return area.id_a === "tod";
                                });

                                if (marcarTodos) {
                                    // Si existe "tod", marcar todos los checkboxes y deshabilitarlos
                                    $('input[name="area[]"]').prop('checked', true).prop('disabled', true); // Marcar todos los checkboxes y deshabilitarlos
                                } else {
                                    // Marcar solo las áreas correspondientes
                                    response.areas.forEach(function (area) {
                                        $('#area_' + area.id_a).prop('checked', true).prop('disabled', true); // Marcar y deshabilitar los checkboxes correspondientes
                                    });
                                }

                                // Asegurarse de que los checkboxes ya marcados no se puedan desmarcar
                                $('input[name="area[]"]:checked').each(function () {
                                    $(this).on('click', function (e) {
                                        if ($(this).prop('disabled')) {
                                            // Si el checkbox está deshabilitado (marcado automáticamente), evitar el desmarcado
                                            e.preventDefault();
                                        }
                                    });
                                });

                                // Asegurarse de que los checkboxes no marcados puedan ser interactivos (marcar/desmarcar)
                                $('input[name="area[]"]:not(:checked)').prop('disabled', false); // Habilitar checkboxes no marcados

                                // MOSTRAR EL BOTÓN "Editar Capacitación"
                                $('#update-areas button').show(); // Usa fadeIn para que aparezca suavemente
                            },
                            error: function (xhr, status, error) {
                                alert('Error al cargar los detalles del curso.');
                            }
                        });
                    }
                }

                // Al cambiar la selección del curso
                $('#course').change(function () {
                    var courseId = $(this).val();
                    if (courseId) {
                        completarDatosCurso(courseId); // Llama a la función para completar los datos
                    }
                });

                // Si hay un curso previamente seleccionado al cargar la página, completa los datos
                var selectedCourseId = $('#course').val();

                if (selectedCourseId) {
                    completarDatosCurso(selectedCourseId); // Completa los datos del curso seleccionado
                }

                // Si se recarga la página, se asegura que el valor del curso se mantenga
                if ($('#course').val() === '') {
                    // Si el valor del curso está vacío, el comportamiento se restablece para que no sea nulo
                    var defaultCourseId = $('#course').data('default-id');  // Guardar el ID del curso en un atributo 'data' de #course
                    if (defaultCourseId) {
                        $('#course').val(defaultCourseId); // Si existe, se selecciona el curso por defecto
                        completarDatosCurso(defaultCourseId);
                    }
                }
            });


        </script>





        <!--CONTADOR DE CARACTERES-->
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
                var actionUrl = this.action.replace(':course', courseValue);
                this.action = actionUrl;
            });
        </script>

        <!--OCULTAR MENSAJES DE EXITO Y ERRROR-->
        <script>
            $(document).ready(function () {
                // Ocultar el mensaje de éxito después de 3 segundos
                setTimeout(function () {
                    $('#message').fadeOut('slow');
                }, 3000);


            });
        </script>


        <!--OTRO CAPACITADOR-->
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


        <!--FILTRO DE PERSONAS-->
        <script>
            $(document).ready(function () {
                $('#filtro').on('input', function () {
                    var filtro = $(this).val().toLowerCase();


                    $('table tbody tr').each(function () {
                        if ($(this).data('hidden-by-area') !== true) {
                            $(this).show();
                        }
                    });

                    // Ahora aplicar el filtro solo a las filas visibles (no afectamos las ocultas por área)
                    $('table tbody tr:visible').each(function () {
                        var nombreApellido = $(this).find('td:nth-child(2)').text().toLowerCase();
                        var legajo = $(this).find('td:nth-child(1)').text().toLowerCase();
                        var area = $(this).find('td:nth-child(3)').text().toLowerCase();

                        if (nombreApellido.indexOf(filtro) === -1 && legajo.indexOf(filtro) === -1 && area.indexOf(filtro) === -1) {
                            $(this).hide();
                        }
                    });
                });

                // Asegurar que el filtro de cursos funcione sin interferencias
                $('#course').on('change', function () {
                    let selectedOption = this.options[this.selectedIndex];
                    let selectedAreas = selectedOption.getAttribute("data-areas") ? selectedOption.getAttribute("data-areas").split(",") : [];
                    let mostrarTodas = selectedAreas.includes("Todas las Areas");

                    $('table tbody tr').each(function () {
                        let personArea = $(this).attr("data-area");

                        if (mostrarTodas || selectedAreas.includes(personArea) || selectedAreas.length === 0) {
                            $(this).show().removeData('hidden-by-area'); // Mostrar y quitar marca de "ocultado por área"
                        } else {
                            $(this).hide().data('hidden-by-area', true); // Ocultar y marcar como "ocultado por área"
                        }
                    });

                    // Resetear el filtro de búsqueda cuando se cambia el curso
                    $('#filtro').val("").trigger("input");
                });
            });
        </script>




        <!--COMPLETAR TABLA DE PERSONAS SEGUN EL AREA DEL CURSO-->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const courseSelect = document.getElementById("course");
                const rows = document.querySelectorAll("tbody tr");

                courseSelect.addEventListener("change", function () {
                    let selectedOption = courseSelect.options[courseSelect.selectedIndex];
                    let selectedAreas = selectedOption.getAttribute("data-areas") ? selectedOption.getAttribute("data-areas").split(",") : [];

                    rows.forEach(row => {
                        let personArea = row.getAttribute("data-area");

                        // Si "Todas las Areas" está en la lista de áreas del curso, mostramos todas las filas
                        if (selectedAreas.includes("Todas las Areas") || selectedAreas.length === 0) {
                            row.style.display = "";
                        } else {
                            row.style.display = selectedAreas.includes(personArea) ? "" : "none";
                        }
                    });
                });
            });
        </script>


    @endpush