@extends('cursos.layouts.layout')

@section('content')
@if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert" id="errorMessage">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
<div class="container mt-5">
    <h1 class="mb-4 text-center">Editar Instancia</h1>
    <form id="cursoForm" action="{{ route('cursos.instancias.update', ['instancia' => $instancia->id_instancia, 'cursoId' => $curso->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT') <!-- Asegúrate de usar el método PUT para la actualización -->
        
        <div class="form-group">
            <label for="fecha_inicio">Fecha inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $instancia->fecha_inicio->format('Y-m-d') }}" required>
        </div>
        <div class="form-group">
    <label for="fecha_fin">Fecha Fin</label>
    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ $instancia->fecha_fin ? $instancia->fecha_fin->format('Y-m-d') : '' }}">
</div>

        <div class="form-group">
            <label for="cupo">Cupos</label>
            <input type="number" class="form-control" id="cupo" name="cupo" value="{{ $instancia->cupo }}" required min="0" max="999999999" oninput="limitInputLength(this)" >
        </div>
        
        <div class="form-group">
    <label for="modalidad">Modalidad</label>
    <select class="form-control" id="modalidad" name="modalidad">
        <option value="">Seleccione una modalidad</option>
        <option value="Presencial" {{ old('modalidad', $modalidad) == 'Presencial' ? 'selected' : '' }}>Presencial</option>
        <option value="Hibrido" {{ old('modalidad', $modalidad) == 'Hibrido' ? 'selected' : '' }}>Hibrido</option>
        <option value="Remoto" {{ old('modalidad', $modalidad) == 'Remoto' ? 'selected' : '' }}>Remoto</option>
    </select>
</div>

        <div class="form-group">
            <label for="capacitador">Capacitador</label>
            <select class="form-control" id="capacitador" name="capacitador" required>
                <option value="">Seleccione un capacitador</option>
                @foreach($personas as $persona)
                    <option value="{{ $persona->nombre_p }} {{ $persona->apellido }}"
                        {{ old('capacitador', $capacitador) == $persona->nombre_p . ' ' . $persona->apellido ? 'selected' : '' }}>
                        {{ $persona->nombre_p }} {{ $persona->apellido }}
                    </option>
                @endforeach
            </select>
        </div>
        <a href="javascript:void(0);" id="otroCapacitadorLink">Otro capacitador</a>
        <a href="javascript:void(0);" id="cerrarCapacitadorLink" style="display: none;">Cerrar</a>
        <div id="otroCapacitadorInput" style="display: none;">
            <label for="otro_capacitador">Escribe el nombre del capacitador</label>
            <input type="text" class="form-control" id="otro_capacitador" name="otro_capacitador" 
                value="{{ old('otro_capacitador', $capacitador) }}" maxlength="60">
        </div>

        <div class="form-group">
            <label for="lugar">Lugar</label>
            <input type="text" class="form-control" id="lugar" name="lugar" value="{{ $instancia->lugar }}" maxlength="100">
        </div>
        <div class="form-group">
    <label for="anexos">Anexos</label>
    <div id="anexos">
        @foreach($anexos as $formulario)
            <div class="form-check">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    name="anexos[]" 
                    id="anexo_{{ $formulario->formulario_id }}" 
                    value="{{ $formulario->formulario_id }}"
                    @if(in_array($formulario->formulario_id, $selectedAnexos->pluck('formulario_id')->toArray())) checked @endif
                >
                <label class="form-check-label" for="anexo_{{ $formulario->formulario_id }}">
                    {{ $formulario->valor2 }}
                </label>
            </div>
        @endforeach
    </div>
</div>

        <div class="form-group">
            <label for="estado">Estado</label>
            <select name="estado" class="form-control" required>
                <option value="" disabled>Selecciona una opción</option>
                <option value="Activo" {{ $instancia->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="No Activo" {{ $instancia->estado == 'No Activo' ? 'selected' : '' }}>No Activo</option>
            </select>
        </div>
        <div class="form-group">
            <label for="version">Version</label>
            <input type="number" class="form-control" id="version" name="version" value="{{ $instancia->version }}" min="0" max="999999999" oninput="limitInputLength(this)" >
        </div>
        <button type="submit" class="btn btn-primary">Editar Instancia</button>
        <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" class="btn btn-secondary">Volver</a>

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const selectCapacitador = document.getElementById('capacitador');
    const otroLink = document.getElementById('otroCapacitadorLink');
    const cerrarLink = document.getElementById('cerrarCapacitadorLink');
    const inputOtroCapacitador = document.getElementById('otroCapacitadorInput');
    const otroCapacitadorInput = document.getElementById('otro_capacitador');

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
        otroCapacitadorInput.value = selectCapacitador.value;  // Completa el valor con el capacitador ingresado
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
@endsection
