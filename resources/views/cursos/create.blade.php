@extends('cursos.layouts.layout')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

@if ($errors->any())
    <div class="alert alert-danger" style="text-align: center;">
        <ul style="list-style-type: none; padding: 0; text-align: center;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container mt-5">
    <h1 class="mb-4 text-center">Crear Curso</h1>
    <form id="cursoForm" action="{{ route('cursos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="252">
            <small id="titulo-count" class="form-text text-muted">Quedan 252 caracteres</small>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required maxlength="252"></textarea>
            <small id="descripcion-count" class="form-text text-muted">Quedan 252 caracteres</small>
        </div>

        <div class="form-group">
            <label>Obligatorio</label>
            <select name="obligatorio" class="form-control" required>
                <option value="">Selecciona una opción</option>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>


        <div class="form-group">
            <label for="area">Áreas</label><br>
            @foreach($areas as $area)
                @if($area->id_a == 'tod')
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="area_{{ $area->id_a }}" name="area[]"
                            value="{{ $area->id_a }}">
                        <label class="form-check-label" for="area_{{ $area->id_a }}">{{ $area->nombre_a }}</label>
                    </div>
                @endif
            @endforeach


            @foreach($areas as $area)
                @if($area->id_a != 'tod')
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input area-checkbox" id="area_{{ $area->id_a }}" name="area[]"
                            value="{{ $area->id_a }}">
                        <label class="form-check-label" for="area_{{ $area->id_a }}">{{ $area->nombre_a }}</label>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="form-group">
            <label for="codigo">Código</label>
            <input type="text" class="form-control" id="codigo" name="codigo">
        </div>

        <div class="form-group">
            <label>Tipo</label>
            <select name="tipo" class="form-control" required>
                <option value="">Selecciona una opción</option>
                <option value="Interna">Interna</option>
                <option value="Externa">Externa</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Crear Curso</button>
        <a href="{{ route('cursos.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>

<script>
    $(document).ready(function () {
        // Inicializar el select2 si lo necesitas
        $('.select2').select2();

        // Obtener el checkbox "Todas las Áreas"
        const selectAllCheckbox = document.querySelector('input[type="checkbox"][value="tod"]');

        // Obtener todos los checkboxes de áreas
        const areaCheckboxes = document.querySelectorAll('.area-checkbox');

        // Evento cuando se cambia el estado del checkbox "Todas las Áreas"
        selectAllCheckbox.addEventListener('change', function () {
            // Si se selecciona "Todas las Áreas", seleccionar todos y deshabilitar los demás checkboxes
            if (this.checked) {
                areaCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = true; // Marcar todos
                    checkbox.disabled = true; // Deshabilitar los demás
                });
            } else {
                areaCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = false; // Deseleccionar todos
                    checkbox.disabled = false; // Habilitar los demás
                });
            }
        });
    });
</script>

@endsection