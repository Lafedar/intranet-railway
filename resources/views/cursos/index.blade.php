@extends('cursos.layouts.layout')
@yield('modal')  <!-- Asegúrate de incluir esto -->
<script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>
<div id="modalContainer"></div>
@section('content')
@if (session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert" id="successMessage">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert" id="errorMessage">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
<div class="container mt-5 table-container">

<form action="{{ route('cursos.index') }}" method="GET" class="mb-4">
    <div class="row">
        <!-- Filtro por nombre del curso -->
        <div class="col-md-4">
            <input type="text" name="nombre_curso" class="form-control" placeholder="Buscar por nombre"
                value="{{ old('nombre_curso', $nombreCurso) }}">
        </div>
        <!-- Filtro por área -->
        <div class="col-md-4">
            <select name="area_id" class="form-control">
                <option value="" {{ old('area_id', $areaId) === null ? 'selected' : '' }}>Seleccionar un área</option>
                <option value="all" {{ old('area_id', $areaId) == 'all' ? 'selected' : '' }}>Todas las áreas</option> <!-- Opción para todas las áreas -->

                @foreach ($areas as $area)
                    <option value="{{ $area->id_a }}" {{ old('area_id', $areaId) == $area->id_a ? 'selected' : '' }}>
                        {{ $area->nombre_a }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
        </div>
    </div>
</form>

@role(['administrador', 'Gestor-cursos'])
    <a href="{{ route('cursos.create') }}" class="btn btn-warning btn-sm" id="BCC">
        Crear Curso
    </a>
@endrole

    <h1 class="mb-4 text-center">Listado de Cursos</h1>
    <div class="row justify-content-center">
        <div class="col-md-10"> 
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Obligatorio</th>
                        <th>Area</th>
                        <th>Fecha de Creación</th>
                        <th>Instancias</th>
                        @role(['administrador', 'Gestor-cursos'])
                            <th>Acciones</th>
                        @endrole
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($cursosData as $curso)
                    <tr>
                        <td>{{ $curso->titulo }}</td>
                        <td>{{ $curso->descripcion }}</td>
                        <td>{{ $curso->obligatorio ? 'Sí' : 'No' }}</td>
                        <td>
                        @if($curso->areas->isEmpty())
                            <span>N/A</span>
                        @else
                            
                            @if($curso->areas->count() == $totalAreas)
                                <span>Todas las áreas</span>
                            @else
                               
                                @foreach($curso->areas as $area)
                                    <span>{{ $area->nombre_a ?? 'N/A' }}/</span><br>
                                @endforeach
                            @endif
                        @endif
                    </td>
                        <td>{{ $curso->created_at->format('d/m/Y') }}</td>
                        <td>                            
                            <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" class="btn btn-primary btn-sm">
                                Ver Instancias
                            </a>
                        </td>
                        @role(['administrador', 'Gestor-cursos'])
                            <td>
                        
                                <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                                    @if($curso->cantInscriptos == 0)
                                    <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este curso y sus instancias?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                    @endif
                            

                            </td>
                        @endrole
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<div id="footer-lafedar"></div>
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
<style>
#BCC{
    margin-left: 95px;
}
</style>