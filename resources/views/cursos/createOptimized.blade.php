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
                        <select class="form-control" id="course" name="course" required
                            data-default-id="{{ session('courseId') }}">
                            <option value="">Seleccione una capacitación</option>
                            @foreach($courses as $course)

                                <option value="{{ $course['id'] }}"
                                    data-areas="{{ is_array($course['areas']) ? implode(',', $course['areas']) : $course['areas'] }}"
                                    @if(old('course') == $course['id'] || (isset($courseId) && $courseId == $course['id'])) selected
                                    @endif>
                                    {{ $course['titulo'] }}
                                </option>

                            @endforeach


                        </select>



                        <a href="javascript:void(0);" id="toggle-capacitacion">Crear Capacitación</a>
                        <a href="javascript:void(0);" id="defaultFeaturesLink">Características por defecto</a>
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
                                <option value="{{ $person->nombre_p }} {{ $person->apellido }}"
                                    @if(old('trainer') == $person->nombre_p . ' ' . $person->apellido) selected @endif>
                                    {{ $person->apellido }} {{ $person->nombre_p }}
                                </option>
                            @endforeach
                        </select>
                        <a href="javascript:void(0);" id="anotherTrainerLink">Otro capacitador</a>
                        <a href="javascript:void(0);" id="closeTrainerLink">Cerrar</a>

                    </div>
                </div>

                <div id="anotherTrainerInput" class="display-none">
                    <label for="another_trainer"><b>Escribe el nombre del capacitador</b></label>
                    <input type="text" class="form-control" id="another_trainer" name="another_trainer" maxlength="60"
                        value="{{ old('another_trainer', $anotherTrainer ?? '') }}">
                </div>

        </div>


        <div class="row mt-0">

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
                        <!-- Campos ocultos en la vista principal para almacenar los valores -->
                        <input type="hidden" id="end_date_main" name="end_date">
                        <input type="hidden" id="hour_main" name="hour">
                        <input type="hidden" id="quota_main" name="quota">
                        <input type="hidden" id="modality_main" name="modality">
                        <input type="hidden" id="code_main" name="code">
                        <input type="hidden" id="place_main" name="place">
                        <input type="hidden" id="exam_main" name="exam">
                        <input type="hidden" id="certificate_main" name="certificate">
                        <input type="hidden" id="status_main" name="status">
                        <input type="hidden" id="version_main" name="version">
                        <input type="hidden" name="annexes_main" id="annexes_main">





                        <input type="hidden" name="flag" id="flagInput" value="0">
                        <div id="enroll-buttons">
                            <a href="{{ route('cursos.index') }}" class="btn btn-secondary" id="asignar-btn">Cancelar</a>
                            <button type="submit" class="btn btn-primary" id="asignar-btn" onclick="setFlag(0)">Crear
                                Instancia</button>
                            <button type="submit" class="btn btn-primary" id="asignar-btn" onclick="setFlag(1)">Crear y
                                Agregar Nueva Instancia</button>
                        </div>
                        <!-- Modal con contenido dinámico -->
                        <div class="modal fade" id="defaultFeaturesModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" id="modal-content">
                                    <!-- El contenido llega por AJAX -->
                                </div>
                            </div>
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
                        <input type="hidden" id="another_trainer_2" name="another_trainer">
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
                            <button type="submit" id="asignar-btn" class="captureData"
                                onclick="sessionStorage.setItem('modoEdicion', 'true'); setFlag2(2)">Editar
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
    </div>



@endsection



    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


        <!--OBTENER EL VALOR DE ID DEL CURSO SELECCIONADO PARA PASARLO A LA MODAL Y OBTENER VALORES DEL FORM DE LA MODAL -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const select = document.getElementById('course');
                const link = document.getElementById('defaultFeaturesLink');

                link.addEventListener('click', function () {
                    const selectedCourseId = select.value;
                    if (!selectedCourseId) {
                        alert('Por favor seleccioná una capacitación primero.');
                        return;
                    }

                    fetch(`{{ route('cursos.defaultFeatures') }}?course_id=${selectedCourseId}`)
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('modal-content').innerHTML = html;
                            $('#defaultFeaturesModal').modal('show');

                            // Esperar a que el contenido se inserte antes de asignar el evento
                            setTimeout(() => {
                                $('#cargar').off('click').on('click', function (e) {
                                    e.preventDefault();  // Evita el envío del formulario
                                    console.log('El botón Cargar fue presionado');

                                    // Obtener los valores del formulario de la modal
                                    const endDate = $('#end_date').val();
                                    const hour = $('#hour').val();
                                    const quota = $('#quota').val();
                                    const modality = $('#modality').val();
                                    const code = $('#code').val();
                                    const place = $('#place').val();
                                    const exam = $('#examInput').val();
                                    const certificate = $('input[name="certificate"]:checked').val();
                                    const status = $('#status').val();
                                    const version = $('#version').val();

                                    // Obtener anexos seleccionados como array y unirlos con coma
                                    const annexes = $('input[id^="anexo_"]:checked')
                                        .map(function () {
                                            return this.value;
                                        }).get();

                                    // Asignar valores a los inputs ocultos de la vista principal
                                    $('#end_date_main').val(endDate);
                                    $('#hour_main').val(hour);
                                    $('#quota_main').val(quota);
                                    $('#modality_main').val(modality);
                                    $('#code_main').val(code);
                                    $('#place_main').val(place);
                                    $('#exam_main').val(exam);
                                    $('#certificate_main').val(certificate);
                                    $('#status_main').val(status);
                                    $('#version_main').val(version);
                                    $('#annexes_main').val(JSON.stringify(annexes));



                                    $('#defaultFeaturesModal').modal('hide');
                                });
                            }, 100); // Esperar 100ms para asegurar que el DOM se actualizó
                        })
                        .catch(error => {
                            console.error(error);
                            alert('Error al cargar la información.');
                        });
                });
            });
        </script>


        <!--ENVIAR DATOS DE FECHA INICIO Y CAPACITADORES AL CONTROLADOR-->
        <script>
            $(document).ready(function () {
                $('.captureData').click(function () {
                    // Capturamos los valores de los inputs de form1
                    var startDate = $('#start_date').val();
                    var trainer = $('#trainer').val();
                    var anotherTrainer = $('#another_trainer').val();

                    // Asignamos esos valores a los campos ocultos en form2
                    $('#start_date_2').val(startDate);
                    $('#trainer_2').val(trainer);
                    $('#another_trainer_2').val(anotherTrainer);

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



        <!--COMPLETAR DATOS AL SELECCIONAR CURSO y MANTENER DATOS EN LA VISTA AL EDITAR -->
        <script>
            $(document).ready(function () {
                const selectedCourseId = $('#course').val();
                const defaultCourseId = $('#course').data('default-id');
                const esEdicion = selectedCourseId !== '';

                //Restaurar checkboxes de personas SOLO si es edición
                if (esEdicion) {
                    const seleccionadas = JSON.parse(sessionStorage.getItem("personasSeleccionadas") || "[]");

                    seleccionadas.forEach(id => {
                        const checkbox = $('input[name="personas[' + id + ']"]');
                        if (checkbox.length) {
                            checkbox.prop('checked', true);
                        }
                    });

                    sessionStorage.removeItem("personasSeleccionadas");
                }

                //Guardar selección de personas en cada cambio
                $('.persona-checkbox').on('change', function () {
                    const seleccionados = [];

                    $('.persona-checkbox:checked').each(function () {
                        const nameAttr = $(this).attr('name');
                        const idMatch = nameAttr.match(/personas\[(\d+)\]/);
                        if (idMatch) {
                            seleccionados.push(parseInt(idMatch[1]));
                        }
                    });

                    sessionStorage.setItem("personasSeleccionadas", JSON.stringify(seleccionados));
                });

                //Función para autocompletar datos de curso
                function completarDatosCurso(courseId) {
                    if (courseId === "") {
                        $('input[name="area[]"]').prop('checked', false).prop('disabled', false);
                        $('#titulo').val('');
                        $('#update-areas button').hide();
                    } else {
                        $.ajax({
                            url: '/courses/json/' + courseId,
                            method: 'GET',
                            success: function (response) {
                                $('#titulo').val(response.course.titulo);
                                $('input[name="area[]"]').prop('checked', false).prop('disabled', false);

                                const marcarTodos = response.areas.some(area => area.id_a === "tod");

                                if (marcarTodos) {
                                    $('input[name="area[]"]').prop('checked', true).prop('disabled', true);
                                } else {
                                    response.areas.forEach(area => {
                                        $('#area_' + area.id_a).prop('checked', true).prop('disabled', true);
                                    });
                                }

                                // Prevenir que se desmarquen si están deshabilitados
                                $('input[name="area[]"]:checked').each(function () {
                                    $(this).on('click', function (e) {
                                        if ($(this).prop('disabled')) {
                                            e.preventDefault();
                                        }
                                    });
                                });

                                $('input[name="area[]"]:not(:checked)').prop('disabled', false);
                                $('#update-areas button').show();
                            },
                            error: function () {
                                alert('Error al cargar los detalles del curso.');
                            }
                        });
                    }
                }

                //Cambio manual del select de curso
                $('#course').change(function () {
                    const courseId = $(this).val();
                    if (courseId) {
                        completarDatosCurso(courseId);
                    }

                    sessionStorage.removeItem("personasSeleccionadas");
                });

                //Si se acaba de crear un curso nuevo, seleccionarlo y autocompletar
                if (selectedCourseId) {
                    completarDatosCurso(selectedCourseId);

                    //Si no hay curso pero hay uno por defecto (creación recién hecha)
                } else if (defaultCourseId) {
                    $('#course').val(defaultCourseId);
                    completarDatosCurso(defaultCourseId);
                }

                //Mostrar u ocultar input de "otro capacitador"
                if ($('#another_trainer').val().trim() !== '') {
                    $('#anotherTrainerInput').show();
                    $('#trainer').prop('disabled', true);
                    $('#anotherTrainerLink').hide();
                    $('#closeTrainerLink').show();
                } else {
                    $('#anotherTrainerInput').hide();
                    $('#closeTrainerLink').hide();
                }

                $('#anotherTrainerLink').click(function () {
                    $('#anotherTrainerInput').show();
                    $('#another_trainer').prop('required', true);
                    $('#trainer').prop('disabled', true);
                    $(this).hide();
                    $('#closeTrainerLink').show();
                });

                $('#closeTrainerLink').click(function () {
                    $('#anotherTrainerInput').hide();
                    $('#another_trainer').prop('required', false);
                    $('#trainer').prop('disabled', false);
                    $('#anotherTrainerLink').show();
                    $(this).hide();
                    $('#another_trainer').val('');
                });
                // Sincronizar cuando se cambia manualmente el select
                $('#course').on('change', function () {
                    $('#hidden-course').val($(this).val());
                });

                // También sincronizar al hacer submit por si el valor fue cargado dinámicamente
                $('form').on('submit', function () {
                    $('#hidden-course').val($('#course').val());
                });



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

                //Mostrar el input al recargar si tiene contenido
                if (otroCapacitadorInput.value.trim() !== '') {
                    inputOtroCapacitador.style.display = 'block';
                    selectCapacitador.disabled = true;
                    cerrarLink.style.display = 'inline';
                    otroLink.style.display = 'none';
                    document.body.style.overflowY = 'auto';
                }

                //Mostrar el input cuando se hace clic en el enlace "Otro"
                otroLink.addEventListener('click', function () {
                    selectCapacitador.value = ""; // Reiniciar select
                    inputOtroCapacitador.style.display = 'block';
                    selectCapacitador.disabled = true;
                    cerrarLink.style.display = 'inline';
                    otroLink.style.display = 'none';
                    document.body.style.overflowY = 'auto';
                    otroCapacitadorInput.value = ''; // Limpiar input
                });

                //Ocultar el input si se selecciona un capacitador del select
                selectCapacitador.addEventListener('change', function () {
                    if (selectCapacitador.value !== "") {
                        inputOtroCapacitador.style.display = 'none';
                        selectCapacitador.disabled = false;
                        cerrarLink.style.display = 'none';
                        otroLink.style.display = 'inline';
                    } else {
                        inputOtroCapacitador.style.display = 'none';
                        cerrarLink.style.display = 'none';
                    }
                });

                //Al hacer clic en "Cerrar"
                cerrarLink.addEventListener('click', function () {
                    inputOtroCapacitador.style.display = 'none';
                    selectCapacitador.disabled = false;
                    otroLink.style.display = 'inline';
                    cerrarLink.style.display = 'none';
                    otroCapacitadorInput.value = ''; // Limpiar el input también si querés
                });

                //Al enviar el formulario, tomar el valor del input si está visible
                document.querySelector('form').addEventListener('submit', function () {
                    if (inputOtroCapacitador.style.display === 'block' && otroCapacitadorInput.value.trim() !== "") {
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

                    // Filtrar solo sobre las filas visibles (que no fueron ocultadas por el filtro de área)
                    $('table tbody tr').each(function () {
                        // Si ya está oculto por área, lo dejamos oculto
                        if ($(this).data('hidden-by-area') === true) {
                            return;
                        }

                        var nombreApellido = $(this).find('td:nth-child(2)').text().toLowerCase();
                        var legajo = $(this).find('td:nth-child(1)').text().toLowerCase();
                        var area = $(this).find('td:nth-child(3)').text().toLowerCase();

                        if (
                            nombreApellido.includes(filtro) ||
                            legajo.includes(filtro) ||
                            area.includes(filtro)
                        ) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });

                // Filtro por curso (áreas)
                $('#course').on('change', function () {
                    let selectedOption = this.options[this.selectedIndex];
                    let selectedAreas = selectedOption.getAttribute("data-areas") ? selectedOption.getAttribute("data-areas").split(",") : [];
                    let mostrarTodas = selectedAreas.includes("Todas las Areas");

                    $('table tbody tr').each(function () {
                        let personArea = $(this).attr("data-area");

                        if (mostrarTodas || selectedAreas.includes(personArea) || selectedAreas.length === 0) {
                            $(this).show().data('hidden-by-area', false); // Mostrar y marcar como visible
                        } else {
                            $(this).hide().data('hidden-by-area', true); // Ocultar y marcar como ocultado por área
                        }
                    });

                    // Resetear el filtro de texto
                    $('#filtro').val("").trigger("input");
                });
            });

        </script>




        <!--COMPLETAR TABLA DE PERSONAS SEGUN EL AREA DEL CURSO-->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const courseSelect = document.getElementById("course");
                const rows = document.querySelectorAll("tbody tr");

                function aplicarFiltroPorCurso() {
                    let selectedOption = courseSelect.options[courseSelect.selectedIndex];
                    let selectedAreas = selectedOption.getAttribute("data-areas") ? selectedOption.getAttribute("data-areas").split(",") : [];

                    rows.forEach(row => {
                        let personArea = row.getAttribute("data-area");
                        if (selectedAreas.includes("Todas las Areas") || selectedAreas.length === 0) {
                            row.style.display = "";
                        } else {
                            row.style.display = selectedAreas.includes(personArea) ? "" : "none";
                        }
                    });
                }

                courseSelect.addEventListener("change", aplicarFiltroPorCurso);

                // Esperar a que el select esté bien cargado con la opción preseleccionada
                setTimeout(aplicarFiltroPorCurso, 100); // o usar requestAnimationFrame(aplicarFiltroPorCurso);
            });
        </script>




    @endpush