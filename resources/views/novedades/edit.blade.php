@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Novedad</h1>
    
    <form action="{{ route('novedades.update', $novedad->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="titulo_edit">Título</label>
            <input type="text" name="titulo" id="titulo_edit" class="form-control" value="{{ old('titulo', $novedad->titulo) }}" required maxlength="100">
            <small id="tituloCountEdit" class="form-text text-muted">Restan <span id="tituloRemainingEdit">100</span> caracteres.</small>
        </div>

        <div class="form-group">
            <label for="descripcion_edit">Descripción</label>
            <textarea name="descripcion" id="descripcion_edit" class="form-control" rows="5" required maxlength="65530">{{ old('descripcion', $novedad->descripcion) }}</textarea>
            <small id="descripcionCountEdit" class="form-text text-muted">Restan <span id="descripcionRemainingEdit">65530</span> caracteres.</small>
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


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tituloInput = document.getElementById('titulo_edit');
        const descripcionInput = document.getElementById('descripcion_edit');
        const tituloRemaining = document.getElementById('tituloRemainingEdit');
        const descripcionRemaining = document.getElementById('descripcionRemainingEdit');

        // Función para actualizar los contadores
        function updateCounts() {
            const tituloLength = tituloInput.value.length;
            const descripcionLength = descripcionInput.value.length;

            tituloRemaining.textContent = 100 - tituloLength;
            descripcionRemaining.textContent = 65530 - descripcionLength;
        }

        // Agregar event listeners
        tituloInput.addEventListener('input', updateCounts);
        descripcionInput.addEventListener('input', updateCounts);

        // Inicializar contadores al cargar la página
        updateCounts();
    });
</script>

@endsection
