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
    <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo', $curso->titulo) }}" required maxlength="252">
    <small id="titulo-count" class="form-text text-muted">Quedan 252 caracteres</small>
</div>

<div class="form-group">
    <label for="descripcion">Descripción</label>
    <textarea class="form-control" id="descripcion" name="descripcion"  maxlength="252">{{ old('descripcion', $curso->descripcion) }}</textarea>
    <small id="descripcion-count" class="form-text text-muted">Quedan 252 caracteres</small>
</div>

        <div class="form-group">
    <label>Obligatorio</label>
    <select name="obligatorio" class="form-control" required>
        <option value="1" {{ $curso->obligatorio == 1 ? 'selected' : '' }}>Sí</option>
        <option value="0" {{ $curso->obligatorio == 0 ? 'selected' : '' }}>No</option>
    </select>
</div>
<!--<div class="form-group">                 //input para areas con filtro
        <label for="area">Áreas</label>
        <select name="area[]" class="form-control select2" multiple="multiple" required>
            @foreach($areas as $area)
                <option value="{{ $area->id_a }}" 
                    @if($curso->areas->contains('id_a', $area->id_a)) selected @endif>
                    {{ $area->nombre_a }}
                </option>
            @endforeach
        </select>
    </div>-->
    <div class="form-group">
        <input type="checkbox" id="selectAll" class="form-check-input">
        <label for="selectAll" class="form-check-label">Todas las áreas</label>
    </div>

<div class="form-group">
    <label for="area">Áreas</label><br>
    @foreach($areas as $area)
        <div class="form-check">
            <input 
                type="checkbox" 
                class="form-check-input" 
                id="area_{{ $area->id_a }}" 
                name="area[]" 
                value="{{ $area->id_a }}"
                @if($curso->areas->contains('id_a', $area->id_a)) checked @endif
            >
            <label class="form-check-label" for="area_{{ $area->id_a }}">{{ $area->nombre_a }}</label>
        </div>
    @endforeach
</div>
<div class="form-group">
    <label for="anexos">Anexos</label>
    <select name="anexos[]" class="form-control select2" multiple="multiple">
        @foreach($anexos as $formulario)
            <option value="{{ $formulario->formulario_id }}"
                @if(in_array($formulario->formulario_id, $selectedAnexos)) selected @endif>
                {{ $formulario->formulario_id }}
            </option>
        @endforeach
    </select>
</div>
        <div class="form-group">
            <label for="codigo">Código</label>
            <input type="text" class="form-control" id="codigo" name="codigo" value="{{ old('codigo', $curso->codigo) }}" required>
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
    $(document).ready(function() {
    $('.select2').select2();
});
</script>
<script>
    // Marcar/desmarcar todos los checkboxes cuando se seleccione "Todas las áreas"
    $('#selectAll').change(function() {
        if ($(this).prop('checked')) {
            $('input[name="area[]"]').prop('checked', true);  // Marca todos los checkboxes
        } else {
            $('input[name="area[]"]').prop('checked', false); // Desmarca todos los checkboxes
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
    document.getElementById("titulo").addEventListener("input", function() {
        updateCharacterCount("titulo", "titulo-count");
    });

    // Escuchamos eventos para el campo de descripción
    document.getElementById("descripcion").addEventListener("input", function() {
        updateCharacterCount("descripcion", "descripcion-count");
    });

    // Inicializar los contadores al cargar la página
    updateCharacterCount("titulo", "titulo-count");
    updateCharacterCount("descripcion", "descripcion-count");
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection
