@extends('cursos.layouts.layout')

@section('content')
<!-- Agregar el CSS de Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Agregar el JavaScript de Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Editar Curso</h1>
    <form action="{{ route('cursos.update', $curso->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo"
                value="{{ old('titulo', $curso->titulo) }}" required maxlength="252">
            <small id="titulo-count" class="form-text text-muted">Quedan 252 caracteres</small>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion"
                maxlength="252">{{ old('descripcion', $curso->descripcion) }}</textarea>
            <small id="descripcion-count" class="form-text text-muted">Quedan 252 caracteres</small>
        </div>

        <div class="form-group">
            <label>Obligatorio</label>
            <select name="obligatorio" class="form-control" required>
                <option value="1" {{ $curso->obligatorio == 1 ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ $curso->obligatorio == 0 ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <!-- Áreas -->
        <div class="form-group">
            <label for="area">Áreas</label><br>

            @foreach($areas as $area)
                @if($area->id_a == 'tod')
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="area_{{ $area->id_a }}" name="area[]"
                            value="{{ $area->id_a }}" @if($curso->areas->contains('id_a', $area->id_a)) checked @endif>
                        <label class="form-check-label" for="area_{{ $area->id_a }}">{{ $area->nombre_a }}</label>
                    </div>
                @endif
            @endforeach

            @foreach($areas as $area)
                @if($area->id_a != 'tod')
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input area-checkbox" id="area_{{ $area->id_a }}" name="area[]"
                            value="{{ $area->id_a }}" @if($curso->areas->contains('id_a', $area->id_a)) checked @endif>
                        <label class="form-check-label" for="area_{{ $area->id_a }}">{{ $area->nombre_a }}</label>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="form-group">
            <label for="codigo">Código</label>
            <input type="text" class="form-control" id="codigo" name="codigo"
                value="{{ old('codigo', $curso->codigo) }}">
        </div>

        <div class="form-group">
            <label>Tipo</label>
            <select name="tipo" class="form-control" required>
                <option value="">Selecciona una opción</option>
                <option value="Interna" {{ $curso->tipo == 'Interna' ? 'selected' : '' }}>Interna</option>
                <option value="Externa" {{ $curso->tipo == 'Externa' ? 'selected' : '' }}>Externa</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('cursos.index') }}" class="btn btn-secondary">Volver</a>

    </form>
</div>

<script>
    $(document).ready(function () {
        // Inicializar select2 si es necesario
        $('.select2').select2();
    });

    // Función para manejar la lógica de "Seleccionar todas las áreas"
    const selectAllCheckbox = document.querySelector('input[name="area[]"][value="tod"]');
    const areaCheckboxes = document.querySelectorAll('input[name="area[]"]:not([value="tod"])');

    // Función para activar/desactivar los checkboxes de áreas
    selectAllCheckbox.addEventListener('change', function () {
        const isChecked = selectAllCheckbox.checked;

        areaCheckboxes.forEach(function (checkbox) {
            checkbox.checked = isChecked;  // Marcar o desmarcar todos los checkboxes de área
            checkbox.disabled = isChecked; // Deshabilitar o habilitar los demás checkboxes
        });

        if (isChecked) {
            // Si "Todas las Áreas" está seleccionada, desmarcar "indeterminate"
            selectAllCheckbox.indeterminate = false;
        } else {
            // Si "Todas las Áreas" no está seleccionada, marcar "indeterminate" en caso de que algunos checkboxes estén seleccionados
            selectAllCheckbox.indeterminate = Array.from(areaCheckboxes).some(cb => cb.checked);
        }
    });

    // Si los checkboxes individuales se seleccionan, también tenemos que ajustar "Todas las Áreas"
    areaCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const allChecked = Array.from(areaCheckboxes).every(function (cb) {
                return cb.checked;
            });

            // Si todos los checkboxes están seleccionados, marca "Todas las Áreas"
            selectAllCheckbox.checked = allChecked;
        });
    });

    // Al cargar la página, verificamos si "Todas las Áreas" está seleccionada
    window.addEventListener('DOMContentLoaded', (event) => {
        if (selectAllCheckbox.checked) {
            areaCheckboxes.forEach(function (checkbox) {
                checkbox.disabled = true; // Deshabilitamos los demás checkboxes si "Todas las Áreas" está seleccionada
            });
        }
    });
</script>

<script>
    // Función para actualizar el contador de caracteres
    function updateCharacterCount(inputId, countId) {
        const inputElement = document.getElementById(inputId);
        const countElement = document.getElementById(countId);
        const maxLength = inputElement.getAttribute("maxlength");
        const currentLength = inputElement.value.length;
        const remaining = maxLength - currentLength;

        countElement.textContent = `Quedan ${remaining} caracteres`;
    }

    // Escuchamos eventos para el campo de título
    document.getElementById("titulo").addEventListener("input", function () {
        updateCharacterCount("titulo", "titulo-count");
    });

    // Escuchamos eventos para el campo de descripción
    document.getElementById("descripcion").addEventListener("input", function () {
        updateCharacterCount("descripcion", "descripcion-count");
    });

    // Inicializar los contadores al cargar la página
    updateCharacterCount("titulo", "titulo-count");
    updateCharacterCount("descripcion", "descripcion-count");
</script>

@endsection