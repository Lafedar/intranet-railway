@extends('cursos.layouts.layout')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
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
    <h1 class="mb-4 text-center">Crear Instancia</h1>
    <form id="cursoForm" action="{{ route('cursos.instancias.store', $curso->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="fecha_inicio">Fecha inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
        </div>
        <div class="form-group">
            <label for="fecha_fin">Fecha Fin</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
        </div>
        <div class="form-group">
            <label for="cupo">Cupos</label>
            <input type="number" class="form-control" id="cupo" name="cupo" required>
        </div>
        <div class="form-group">
            <label for="modalidad">Modalidad</label>
            <input type="text" class="form-control" id="modalidad" name="modalidad">
        </div>
        <div class="form-group">
            <label for="capacitador">Capacitador</label>
            <input type="text" class="form-control" id="capacitador" name="capacitador">
        </div>
        <div class="form-group">
            <label for="lugar">Lugar</label>
            <input type="text" class="form-control" id="lugar" name="lugar">
        </div>
        <div class="form-group">
            <label for="estado">Estado</label>
            <select name="estado" class="form-control" required>
                <option value="" disabled selected>Selecciona una opción</option>
                <option value="Activo">Activo</option>
                <option value="No Activo">No Activo</option>
            </select>
        </div>
        <div class="form-group">
            <label for="version">Version</label>
            <input type="number" name="version" class="form-control" required>

        </div>
        
        
        <button type="submit" class="btn btn-primary">Crear Instancia</button>
        <button type="button" class="btn btn-secondary" onclick="window.history.back();">Volver</button>

</div>
@endsection
<script>
    $(document).ready(function() {
        // Ocultar el mensaje de éxito después de 3 segundos
        setTimeout(function() {
            $('#successMessage').fadeOut('slow');
        }, 3000);

        // Ocultar el mensaje de error después de 3 segundos
        setTimeout(function() {
            $('#errorMessage').fadeOut('slow');
        }, 3000);
    });
</script>