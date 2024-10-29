@extends('cursos.layouts.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">Editar Instancia</h1>
    <form id="cursoForm" action="{{ route('cursos.instancias.update', $instancia->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT') <!-- Asegúrate de usar el método PUT para la actualización -->
        
        <div class="form-group">
            <label for="fecha_inicio">Fecha inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $instancia->fecha_inicio->format('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label for="fecha_fin">Fecha Fin</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ $instancia->fecha_fin->format('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label for="cupo">Cupos</label>
            <input type="number" class="form-control" id="cupo" name="cupo" value="{{ $instancia->cupo }}" required>
        </div>
        <div class="form-group">
            <label for="modalidad">Modalidad</label>
            <input type="text" class="form-control" id="modalidad" name="modalidad" value="{{ $instancia->modalidad }}">
        </div>
        <div class="form-group">
            <label for="capacitador">Capacitador</label>
            <input type="text" class="form-control" id="capacitador" name="capacitador" value="{{ $instancia->capacitador }}">
        </div>
        <div class="form-group">
            <label for="lugar">Lugar</label>
            <input type="text" class="form-control" id="lugar" name="lugar" value="{{ $instancia->lugar }}">
        </div>
        <div class="form-group">
            <label for="estado">Estado</label>
            <select name="estado" class="form-control" required>
                <option value="" disabled>Selecciona una opción</option>
                <option value="Activo" {{ $instancia->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="No Activo" {{ $instancia->estado == 'No Activo' ? 'selected' : '' }}>No Activo</option>
            </select>
        </div>
        <div class="form-group">
            <label for="version">Version</label>
            <input type="number" class="form-control" id="version" name="version" value="{{ $instancia->version }}">
        </div>
        <button type="submit" class="btn btn-primary">Editar Instancia</button>
    </form>
</div>
@endsection
