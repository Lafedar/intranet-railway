@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Novedad</h1>
    
    <form action="{{ route('novedades.update', $novedad->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo', $novedad->titulo) }}" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="5" required>{{ old('descripcion', $novedad->descripcion) }}</textarea>
        </div>

        <div class="form-group">
            <label for="imagenes">Imágenes</label>
            <input type="file" name="imagenes[]" id="imagenes" class="form-control" multiple>
            @if($novedad->imagen)
                <p>Imágenes actuales:</p>
                @foreach(explode(',', $novedad->imagen) as $imagen)
                    <img src="{{ asset('storage/' . $imagen) }}" alt="Imagen actual" style="max-width: 100px; max-height: 100px;">
                @endforeach
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('novedades.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection