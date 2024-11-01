@extends('cursos.layouts.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">Editar Curso</h1>
    <form action="{{ route('cursos.update', $curso->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo', $curso->titulo) }}" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required>{{ old('descripcion', $curso->descripcion) }}</textarea>
        </div>

        <div class="form-group">
    <label>Obligatorio</label>
    <select name="obligatorio" class="form-control" required>
        <option value="1" {{ $curso->obligatorio == 1 ? 'selected' : '' }}>Sí</option>
        <option value="0" {{ $curso->obligatorio == 0 ? 'selected' : '' }}>No</option>
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
@endsection
