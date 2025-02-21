@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

@section('content')
    <div id="puestos-create">
        <form action="{{ route('puestos.store') }}" method="POST">
            @csrf <!-- Esto es para proteger el formulario contra CSRF -->

            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                        <label for="desc_puesto">Nombre de puesto:</label>
                        <input type="text" name="desc_puesto" class="form-control" id="desc_puesto" autocomplete="off"
                            minlength="1" maxlength="100" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="area">Área:</label>
                        <select class="form-control" name="area" id="area" required>
                            <option value="" disabled selected>Seleccione un área</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id_a }}">{{ $area->nombre_a }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="location">Localización:</label>
                        <select class="form-control" name="location" id="location" required>
                            <option value="" disabled selected>Seleccione</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" data-area="{{ $loc->id_area }}">{{ $loc->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="persona">Persona:</label>
                        <select class="form-control" name="persona" id="persona">
                            <option value="" disabled selected>Seleccione una persona</option>
                            @foreach($personas as $persona)
                                <option value="{{ $persona->id_p }}">{{ $persona->apellido }} {{ $persona->nombre_p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="obs">Observación:</label>
                        <input type="text" name="obs" class="form-control" id="obs" autocomplete="off" maxlength="100">
                    </div>
                </div>

            </div>

            <div id="btn-modal">
                <button type="button" id="asignar-btn" onclick="window.history.back();">Cancelar</button>

                <button type="submit" id="asignar-btn">Crear</button>
            </div>

        </form>

    </div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            // Escuchar el cambio en el select de áreas
            $('#area').change(function () {
                var selectedAreaId = $(this).val();

                // Ocultar todas las opciones de localización
                $('#location option').hide();

                // Mostrar solo las localizaciones que pertenecen al área seleccionada
                $('#location option[data-area="' + selectedAreaId + '"]').show();

                // Resetear el valor seleccionado en el select de localización
                $('#location').val('');
            });
        });
    </script>
@endpush