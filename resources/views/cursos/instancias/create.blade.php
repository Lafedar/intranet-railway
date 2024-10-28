@extends('cursos.layouts.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">Crear Instancia</h1>
    <form id="cursoForm" action="{{ route('cursos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="fecha_inicio">Fecha inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
        </div>
        <div class="form-group">
            <label for="fecha_fin">Fecha Fin</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required></textarea>
        </div>
        <div class="form-group">
            <label for="cupo">Cupos</label>
            <input type="text" class="form-control" id="cupo" name="cupo" required></textarea>
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
            <input type="text" class="form-control" id="version" name="version">
        </div>
        <button type="submit" class="btn btn-primary">Crear Instancia</button>
    </form>
</div>
@endsection
