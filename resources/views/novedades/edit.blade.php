@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> 

<div class="container" class="container-fluid">
    <div id="novedades-edit-container">
        <h1 style="text-align:center">Editar Novedad</h1>

        <form action="{{ route('novedades.update', $novedad->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="titulo_edit"><b>Título</b></label>
                <input type="text" name="titulo" id="titulo_edit" class="form-control"
                    value="{{ old('titulo', $novedad->titulo) }}" required maxlength="100">
                <small id="tituloCountEdit" class="form-text text-muted">Restan <span
                        id="tituloRemainingEdit">100</span> caracteres.</small>
            </div>

            <div class="form-group">
                <label for="descripcion_edit"><b>Descripción</b></label>
                <textarea name="descripcion" id="descripcion_edit" class="form-control" rows="5" required
                    maxlength="65530">{{ old('descripcion', $novedad->descripcion) }}</textarea>
                <small id="descripcionCountEdit" class="form-text text-muted">Restan <span
                        id="descripcionRemainingEdit">65530</span> caracteres.</small>
            </div>

            <div class="form-group">
                <label for="nueva_imagen"><b>Cambiar Imagen Principal (opcional)</b></label>
                <input type="file" name="nueva_imagen" id="nueva_imagen" class="form-control" accept=".jpg,.jpeg,.png">
                <p>Imagen principal actual:</p>
                @if($novedad->portada)
                    <img src="{{ asset('storage/' . $novedad->portada) }}" alt="Imagen principal actual"
                        style="max-width: 100px; max-height: 100px;">
                @endif
            </div>

            <div class="form-group">
                <label for="imagenes"><b>Imágenes Secundarias (opcional)</b></label>
                <input type="file" name="imagenes[]" id="imagenes" class="form-control" multiple>
                <p>Imágenes secundarias actuales:</p>
                @if($novedad->imagenes_sec)
                    @foreach(explode(',', $novedad->imagenes_sec) as $imagen)
                        @if($imagen !== $novedad->portada)
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ asset('storage/' . $imagen) }}" alt="Imagen secundaria actual"
                                    style="max-width: 100px; max-height: 100px;" class="me-2">
                                <input type="checkbox" name="delete_images[]" value="{{ $imagen }}"> Borrar
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>

            <button type="submit" class="btn btn-primary" id="asignar-btn">Guardar Cambios</button>
            <a href="{{ route('novedades.index') }}" class="btn btn-secondary" id="asignar-btn">Cancelar</a>
        </form>
    </div>

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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imagenesInput = document.getElementById('imagenes');
        const portadaInput = document.getElementById('nueva_imagen');

        imagenesInput.addEventListener('change', function () {
            const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
            let valid = true;

            for (const file of imagenesInput.files) {
                if (!allowedExtensions.exec(file.name)) {
                    valid = false;
                    break;
                }
            }

            if (!valid) {
                alert('Solo se permiten imágenes en formato JPG, JPEG o PNG.');
                imagenesInput.value = ''; // Limpiar el input
            }
        });
        portadaInput.addEventListener('change', function () {
            const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
            let valid = true;

            for (const file of portadaInput.files) {
                if (!allowedExtensions.exec(file.name)) {
                    valid = false;
                    break;
                }
            }

            if (!valid) {
                alert('Solo se permiten imágenes en formato JPG, JPEG o PNG.');
                portadaInput.value = ''; // Limpiar el input
            }
        });
    });
</script>