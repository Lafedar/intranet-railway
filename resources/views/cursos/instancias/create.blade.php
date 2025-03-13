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
            <form id="cursoForm" action="{{ route('cursos.instancias.store', $curso->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="fecha_inicio">Fecha inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                </div>

                <div class="form-group">
                    <label for="fecha_fin">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                </div>



                <div class="form-group">
                    <label for="cupo">Cupos</label>
                    <input type="number" class="form-control" id="cupo" name="cupo" required min="0" max="999999999"
                        oninput="limitInputLength(this)">
                </div>
                <div class="form-group">
                    <label for="modalidad">Modalidad</label>
                    <select class="form-control" id="modalidad" name="modalidad">
                        <option value="">Seleccione una modalidad</option>
                        <option value="Presencial">Presencial</option>
                        <option value="Hibrido">Hibrido</option>
                        <option value="Remoto">Remoto</option>

                    </select>
                </div>
                <div class="form-group">
                    <label for="capacitador">Capacitador</label>
                    <select class="form-control" id="capacitador" name="capacitador" required>
                        <option value="">Seleccione un capacitador</option>
                        @foreach($personas as $persona)
                            <option value="{{ $persona->nombre_p }} {{ $persona->apellido }}">
                                {{ $persona->apellido }} {{ $persona->nombre_p }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <a href="javascript:void(0);" id="otroCapacitadorLink">Otro capacitador</a>


                <a href="javascript:void(0);" id="cerrarCapacitadorLink" style="display: none;">Cerrar</a>

                <div id="otroCapacitadorInput" style="display: none;">
                    <label for="otro_capacitador">Escribe el nombre del capacitador</label>
                    <input type="text" class="form-control" id="otro_capacitador" name="otro_capacitador" maxlength="60">
                </div>
                <div class="form-group">
                    <label for="codigo">Codigo</label>
                    <input type="text" class="form-control" id="codigo" name="codigo" maxlength="49">
                </div>
                <div class="form-group">
                    <label for="lugar">Lugar</label>
                    <input type="text" class="form-control" id="lugar" name="lugar" maxlength="100">
                </div>
                <div class="form-group">
                    <label for="anexos">Registros de Capacitación</label>
                    <div id="anexos">
                        @foreach($anexos as $formulario)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="anexos[]"
                                    id="anexo_{{ $formulario->formulario_id }}" value="{{ $formulario->formulario_id }}">
                                <p class="form-check-label" for="anexo_{{ $formulario->formulario_id }}">
                                    {{ $formulario->valor_formulario }} - {{ $formulario->valor2 }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label for="examen">Examen (Insertar Link de Microsoft Form)</label>
                    <input type="text" name="examen" class="form-control" maxlength="200">
                </div>

                <div class="form-group">
                    <label>Certificados</label><br>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="certificado" id="certificado_aprobacion"
                            value="Aprobacion" required>
                        <label for="certificado_aprobacion" style="font-weight: normal;">
                            Certificado de Aprobación
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="certificado" id="certificado_participacion"
                            value="Participacion" required>
                        <label for="certificado_participacion" style="font-weight: normal;">
                            Certificado de Participación
                        </label>
                    </div>
                </div>


                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" class="form-control" required>
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

                <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" id="asignar-btn">Cancelar</a>
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
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFin = document.getElementById('fecha_fin');

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
            const selectCapacitador = document.getElementById('capacitador');
            const otroLink = document.getElementById('otroCapacitadorLink');
            const cerrarLink = document.getElementById('cerrarCapacitadorLink');
            const inputOtroCapacitador = document.getElementById('otroCapacitadorInput');
            const otroCapacitadorInput = document.getElementById('otro_capacitador');

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
@endpush