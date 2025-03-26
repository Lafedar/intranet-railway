@extends('layouts.app')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert" id="errorMessage">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif
    <div class="container mt-5">
        <div id="cursos-instancias-create-container">
            <h1 class="mb-4 text-center">Crear Instancia</h1>
            <form id="cursoForm" action="{{ route('cursos.instancias.store', $course->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="start_date">Fecha inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>

                <div class="form-group">
                    <label for="end_date">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
                <div class="form-group">
                    <label for="hour">Hora</label>
                    <input type="time" class="form-control" id="hour" name="hour" required>
                </div>


                <div class="form-group">
                    <label for="quota">Cupos</label>
                    <input type="number" class="form-control" id="quota" name="quota" required min="0" max="999999999"
                        oninput="limitInputLength(this)">
                </div>
                <div class="form-group">
                    <label for="modality">Modalidad</label>
                    <select class="form-control" id="modality" name="modality">
                        <option value="">Seleccione una modalidad</option>
                        <option value="Presencial">Presencial</option>
                        <option value="Hibrido">Hibrido</option>
                        <option value="Remoto">Remoto</option>

                    </select>
                </div>
                <div class="form-group">
                    <label for="trainer">Capacitador</label>
                    <select class="form-control" id="trainer" name="trainer" required>
                        <option value="">Seleccione un capacitador</option>
                        @foreach($persons as $person)
                            <option value="{{ $person->nombre_p }} {{ $person->apellido }}">
                                {{ $person->apellido }} {{ $person->nombre_p }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <a href="javascript:void(0);" id="anotherTrainerLink">Otro capacitador</a>


                <a href="javascript:void(0);" id="closeTrainerLink" style="display: none;">Cerrar</a>

                <div id="anotherTrainerInput" style="display: none;">
                    <label for="another_trainer">Escribe el nombre del capacitador</label>
                    <input type="text" class="form-control" id="another_trainer" name="another_trainer" maxlength="60">
                </div>
                <div class="form-group">
                    <label for="code">Codigo</label>
                    <input type="text" class="form-control" id="code" name="code" maxlength="49">
                </div>
                <div class="form-group">
                    <label for="place">Lugar</label>
                    <input type="text" class="form-control" id="place" name="place" maxlength="100">
                </div>
                <div class="form-group">
                    <label for="annexes">Registros de Capacitación</label>
                    <div id="annexes">
                        @foreach($annexes as $form)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="annexes[]"
                                    id="anexo_{{ $form->formulario_id }}" value="{{ $form->formulario_id }}">
                                <p class="form-check-label" for="anexo_{{ $form->formulario_id }}">
                                    {{ $form->valor_formulario }} - {{ $form->valor2 }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label>Certificados</label><br>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="certificate" id="approval_certificate"
                            value="Aprobacion" required>
                        <label for="approval_certificate" style="font-weight: normal;">
                            Certificado de Aprobación
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="certificate" id="participation_certificate"
                            value="Participacion" required>
                        <label for="participation_certificate" style="font-weight: normal;">
                            Certificado de Participación
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exam">Examen (Insertar Link de Microsoft Form)</label>
                    <input type="text" name="exam" class="form-control" maxlength="200" id="examInput">
                </div>


                <div class="form-group">
                    <label for="status">Estado</label>
                    <select name="status" class="form-control" required>
                        <option value="" disabled selected>Selecciona una opción</option>
                        <option value="Activo">Activo</option>
                        <option value="No Activo">No Activo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="version">Version</label>
                    <input type="number" name="version" class="form-control" min="0" max="999999999"
                        oninput="limitInputLength(this)">

                </div>

                <a href="{{ route('cursos.instancias.index', ['cursoId' => $course->id]) }}" id="asignar-btn">Cancelar</a>
                <button type="submit" id="asignar-btn">Crear Instancia</button>


        </div>

    </div>
@endsection
@push('scripts')
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
    window.onload = function() {
        toggleExamenField();
    }
</script>

@endpush