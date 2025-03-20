@extends('layouts.app')

@push('styles')
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
        <script>
            setTimeout(function () {
                var errorMessage = document.getElementById('errorMessage');
                if (errorMessage) {
                    errorMessage.classList.remove('show');
                    errorMessage.classList.add('fade');
                }
            }, 3000);
        </script>
    @endif
    <div class="container mt-5">
        <div id="cursos-instancias-edit-container">
            <h1 class="mb-4 text-center">Editar Instancia</h1>
            <form id="courseForm"
                action="{{ route('cursos.instancias.update', ['instancia' => $instance->id_instancia, 'cursoId' => $course->id]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="start_date"><b>Fecha Inicio</b></label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="{{ $instance->fecha_inicio->format('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label for="end_date"><b>Fecha Fin</b></label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="{{ $instance->fecha_fin ? $instance->fecha_fin->format('Y-m-d') : '' }}">
                </div>
                <div class="form-group">
                    <label for="hour"><b>Hora</b></label>
                    <input type="time" class="form-control" id="hour" name="hour"
                        value="{{ \Carbon\Carbon::parse($instance->hora)->format('H:i') }}">
                </div>


                <div class="form-group">
                    <label for="quota"><b>Cupo</b></label>
                    <input type="number" class="form-control" id="quota" name="quota" value="{{ $instance->cupo }}" required
                        min="0" max="999999999" oninput="limitInputLength(this)">
                </div>

                <div class="form-group">
                    <label for="modality"><b>Modalidad</b></label>
                    <select class="form-control" id="modality" name="modality">
                        <option value="">Seleccione una modality</option>
                        <option value="Presencial" {{ old('modalidad', $modality) == 'Presencial' ? 'selected' : '' }}>
                            Presencial
                        </option>
                        <option value="Hibrido" {{ old('modalidad', $modality) == 'Hibrido' ? 'selected' : '' }}>Hibrido
                        </option>
                        <option value="Remoto" {{ old('modalidad', $modality) == 'Remoto' ? 'selected' : '' }}>Remoto
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="trainer"><b>Capacitador</b></label>
                    <select class="form-control" id="trainer" name="trainer" required>
                        <option value="">Seleccione un capacitador</option>
                        @foreach($persons as $person)
                            <option value="{{ $person->nombre_p }} {{ $person->apellido }}" {{ old('capacitador', $trainer) == $person->nombre_p . ' ' . $person->apellido ? 'selected' : '' }}>
                                {{ $person->apellido }} {{ $person->nombre_p }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <a href="javascript:void(0);" id="anotherTrainerLink">Otro capacitador</a>
                <a href="javascript:void(0);" id="closeTrainerLink" style="display: none;">Cerrar</a>
                <div id="anotherTrainerInput" style="display: none;">
                    <label for="another_trainer">Escribe el nombre del capacitador</label>
                    <input type="text" class="form-control" id="another_trainer" name="another_trainer"
                        value="{{ old('another_trainer', $trainer) }}" maxlength="60">
                </div>
                <div class="form-group">
                    <label for="code"><b>Codigo</b></label>
                    <input type="text" class="form-control" id="code" name="code" maxlength="49"
                        value="{{$instance->codigo }}">
                </div>
                <div class="form-group">
                    <label for="place"><b>Lugar</b></label>
                    <input type="text" class="form-control" id="place" name="place" value="{{ $instance->lugar }}"
                        maxlength="100">
                </div>
                <div class="form-group">
                    <label for="annexes"><b>Registros de Capacitación</b></label>
                    <div id="annexes">
                        @foreach($annexes as $form)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="annexes[]"
                                    id="anexo_{{ $form->formulario_id }}" value="{{ $form->formulario_id }}"
                                    @if(in_array($form->formulario_id, $selectedAnnexes->pluck('formulario_id')->toArray())) checked @endif>
                                <p class="form-check-label" for="anexo_{{ $form->formulario_id }}">
                                    {{ $form->valor_formulario }} - {{ $form->valor2 }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label><b>Certificados</b></label><br>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="certificate" id="approval_certificate"
                            value="Aprobacion" {{ old('certificado', $instance->certificado) == 'Aprobacion' ? 'checked' : '' }} required>
                        <label for="approval_certificate" style="font-weight: normal;">
                            Certificado de Aprobación
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="certificate" id="participation_certificate"
                            value="Participacion" {{ old('certificado', $instance->certificado) == 'Participacion' ? 'checked' : '' }} required>
                        <label for="participation_certificate" style="font-weight: normal;">
                            Certificado de Participación
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exam"><b>Examen (Insertar Link de Microsoft Form)</b></label>
                    <input type="text" name="exam" class="form-control" maxlength="200" value="{{ $instance->examen }}" id="examInput">
                </div>
               

                <div class="form-group">
                    <label for="status"><b>Estado</b></label>
                    <select name="status" class="form-control" required>
                        <option value="" disabled>Selecciona una opción</option>
                        <option value="Activo" {{ $instance->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="No Activo" {{ $instance->estado == 'No Activo' ? 'selected' : '' }}>No Activo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="version"><b>Version</b></label>
                    <input type="number" class="form-control" id="version" name="version" value="{{ $instance->version }}"
                        min="0" max="999999999" oninput="limitInputLength(this)">
                </div>
                <a href="{{ route('cursos.instancias.index', ['cursoId' => $course->id]) }}" id="asignar-btn">Cancelar</a>
                <button type="submit" id="asignar-btn">Guardar</button>


            </form>
        </div>

    </div>

@endsection
@push('scripts')
    <script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>

                document.addEventListener('DOMContentLoaded', function () {
                    const selectCapacitador = document.getElementById('trainer');
                const otroLink = document.getElementById('anotherTrainerLink');
                const cerrarLink = document.getElementById('closeTrainerLink');
                const inputOtroCapacitador = document.getElementById('anotherTrainerInput');
                const otroCapacitadorInput = document.getElementById('another_trainer');

                // Verificar si el capacitador seleccionado es "Otro" o no
                if (selectCapacitador.value === "") {
                    // Si no hay capacitador seleccionado, mostrar el input "Otro" y bloquear el select
                    inputOtroCapacitador.style.display = 'block';
                selectCapacitador.disabled = true;
                cerrarLink.style.display = 'inline';
                otroLink.style.display = 'none';
                            } else if (selectCapacitador.value === "otro") {
                    // Si el capacitador seleccionado es "Otro", mostrar el input "Otro" con el valor prellenado
                    inputOtroCapacitador.style.display = 'block';
                selectCapacitador.disabled = true;
                cerrarLink.style.display = 'inline';
                otroLink.style.display = 'none';
                otroCapacitadorInput.value = selectCapacitador.value; // Completa el valor con el capacitador ingresado
                            } else {
                    // Si hay un capacitador seleccionado, aseguramos que el input "Otro" esté oculto
                    inputOtroCapacitador.style.display = 'none';
                selectCapacitador.disabled = false;
                cerrarLink.style.display = 'none';
                otroLink.style.display = 'inline';
                            }

                // Mostrar el input "Otro" cuando se hace clic en el enlace "Otro"
                otroLink.addEventListener('click', function () {
                    selectCapacitador.value = ""; // Cambiar el valor del select a "Seleccione un capacitador"
                inputOtroCapacitador.style.display = 'block'; // Mostrar el input
                selectCapacitador.disabled = true; // Bloquear el select
                cerrarLink.style.display = 'inline'; // Mostrar el botón "Cerrar"
                otroLink.style.display = 'none'; // Ocultar el enlace "Otro"

                // Limpiar el campo de texto
                otroCapacitadorInput.value = '';
                            });

                // Cuando se hace clic en el botón "Cerrar"
                cerrarLink.addEventListener('click', function () {
                    inputOtroCapacitador.style.display = 'none'; // Ocultar el input de "Otro"
                selectCapacitador.disabled = false; // Habilitar el select
                otroLink.style.display = 'inline'; // Mostrar el enlace "Otro"
                cerrarLink.style.display = 'none'; // Ocultar el botón "Cerrar"

                // Limpiar el valor del input de "Otro" (si es necesario)
                otroCapacitadorInput.value = '';
                            });

                // Mostrar el input "Otro" cuando se selecciona un capacitador
                selectCapacitador.addEventListener('change', function () {
                            if (selectCapacitador.value !== "") {
                    inputOtroCapacitador.style.display = 'none'; // Ocultar el input si se elige un capacitador
                selectCapacitador.disabled = false; // Desbloquear el select
                cerrarLink.style.display = 'none'; // Ocultar el botón "Cerrar"
                otroLink.style.display = 'inline'; // Mostrar el enlace "Otro"
                            } else {
                    inputOtroCapacitador.style.display = 'none'; // Ocultar el input si no se elige un capacitador
                cerrarLink.style.display = 'none'; // Ocultar el botón "Cerrar"
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