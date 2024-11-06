@extends('cursos.layouts.layout')

@section('content')
<!-- Agregar el CSS de Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Agregar el JavaScript de Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Crear Curso</h1>
    <form id="cursoForm" action="{{ route('cursos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
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
    <label for="area">Áreas</label>

    <select name="area[]" class="form-control select2" multiple="multiple" required>
        @foreach($areas as $area)
            <option value="{{ $area->id_a }}">{{$area->nombre_a}}</option>
        @endforeach
    </select>
</div>
        <div class="form-group">
            <label for="codigo">Código</label>
            <input type="text" class="form-control" id="codigo" name="codigo" required>
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
    $(document).ready(function() {
    $('.select2').select2();
});
</script>
@endsection
