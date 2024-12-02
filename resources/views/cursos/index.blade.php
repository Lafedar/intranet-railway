<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos</title>
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cursos.css') }}">
</head>
<body>
@if(session('success'))
    <div class="alert alert-success" id="success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" id="danger">
        {{ session('error') }}
    </div>
@endif

<a href="{{ url('/home') }}" class="img-logo">
    <img src="{{ asset('storage/cursos/logo-cursos.png') }}" loading="lazy" alt="Logo Cursos">
</a>

<div class="container">
    @role(['administrador', 'Gestor-cursos'])
    <form action="{{ route('cursos.index') }}" method="GET" class="mb-4">
        <div class="filter-container">
            <div class="filter-item">
                <input type="text" name="nombre_curso" class="form-control" placeholder="Buscar por título"
                    value="{{ old('nombre_curso', $nombreCurso) }}">
            </div>
            <div class="filter-item">
                <select name="area_id" class="form-control">
                    <option value="" {{ old('area_id', $areaId) === null ? 'selected' : '' }}>Seleccionar un área</option>
                    <!-- Mostrar el área 'Todas las Áreas' primero -->
                    @foreach ($areas->sortBy(function($area) { return $area->id_a === 'tod' ? -1 : 1; }) as $area)
                        <option value="{{ $area->id_a }}" {{ old('area_id', $areaId) == $area->id_a ? 'selected' : '' }}>
                            {{ $area->nombre_a }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="filter-item">
                <button type="submit">Filtrar</button>
            </div>
        </div>
    </form>
    @endrole

    <a href="{{ route('cursos.create') }}" id="BCC">
        Crear Curso
    </a>

    <div>
        <div> 
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Obligatorio</th>
                        @role(['administrador', 'Gestor-cursos'])
                        <th>Codigo</th>
                        <th>Area</th>
                        @endrole
                        <th>Fecha de Creación</th>
                        @role(['administrador', 'Gestor-cursos'])
                        <th>Cant. Inscriptos</th>
                        <th>% Aprobados</th>
                        @endrole
                        @if(!Auth::user()->hasRole('administrador') && !Auth::user()->hasRole('Gestor-cursos'))
                        <th>Evaluación</th>
                        <th>Certificado</th>
                        @else
                        <th>Instancias</th>
                        @endrole
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
                        @role(['administrador', 'Gestor-cursos'])
                        <td>{{ $curso->codigo ?? 'N/A'}}</td>
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
                        @endrole
                        <td>{{ $curso->created_at->format('d/m/Y') }}</td>
                        @role(['administrador', 'Gestor-cursos'])
                        <td>{{ $curso->cantInscriptos}}</td>
                        <td>{{ number_format($curso->porcentajeAprobados, 2) }}%</td>
                        @endrole

                        @if(!Auth::user()->hasRole('administrador') && !Auth::user()->hasRole('Gestor-cursos'))
                        <td>
                            {{$curso->evaluacion}}
                        </td>
                        @endif

                        <td>            
                        @role(['administrador', 'Gestor-cursos'])                
                            <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" class="btn" title="Ver Instancia">
                                <img src="{{ asset('storage/cursos/tocar.png') }}"  loading="lazy" alt="Ver Instancia">
                            </a>
                        @endrole
                        @if(Auth::user()->dni == $personaDni->dni && $curso->evaluacion == "Aprobado") 
                            @if(!Auth::user()->hasRole('administrador') && !Auth::user()->hasRole('Gestor-cursos'))
                            <form action="{{ route('generarCertificado', ['cursoId' => $curso->id, 'personaId' => $personaDni->id_p]) }}" method="POST" title="Ver Certificado">
                                @csrf
                                <button type="submit" id="icono"><img src="{{ asset('storage/cursos/ver.png') }}" alt="Ver" id="img-icono"></button>
                            </form>
                            @endif
                        @endif
                        </td>

                        @role(['administrador', 'Gestor-cursos'])
                        <td>
                            <a href="{{ route('cursos.edit', $curso->id) }}" title="Editar Curso">
                                <img src="{{ asset('storage/cursos/editar.png') }}" loading="lazy" alt="Editar">
                            </a>
                            @if($curso->cantInscriptos == 0)
                                <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST" id="icono" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este curso y sus instancias?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"  title="Eliminar Curso" id="icono" >
                                        <img src="{{ asset('storage/cursos/eliminar.png') }}" loading="lazy" alt="Eliminar" id="icono">
                                    </button>
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

<footer>
    <p>​LABORATORIOS LAFEDAR S.A | LABORATORIOS FEDERALES ARGENTINOS S.A</p>
</footer> 

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Ocultar los mensajes de éxito y error después de 3 segundos
    setTimeout(function() {
        $('.alert').fadeOut('slow'); // 'slow' es la duración de la animación
    }, 3000); // 3000 milisegundos = 3 segundos
});
</script>
</body>
</html>
