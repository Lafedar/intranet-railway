@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/cursos.css') }}" rel="stylesheet">

@endpush

@section('content')

<div id="container">
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

    @role(['administrador', 'Gestor-cursos'])
    <form action="{{ route('cursos.index') }}" method="GET" class="mb-4">
    <div class="filter-container">
        <!-- Filtro para Nombre -->
        <div class="filter-item">
            <div class="input-wrapper">
                <input 
                    type="text" 
                    name="nombre_curso" 
                    class="form-control" 
                    placeholder="Buscar por título"
                    value="{{ old('nombre_curso', $nombreCurso) }}">
            </div>
        </div>

        <!-- Filtro para Área -->
        <div class="filter-item">
            <div class="select-wrapper">
                <select 
                    name="area_id" 
                    class="form-control">
                    <option value="" {{ old('area_id', $areaId) === null ? 'selected' : '' }}>Seleccionar un área</option>
                    <!-- Mostrar el área 'Todas las Áreas' primero -->
                    @foreach ($areas->sortBy(function($area) { return $area->id_a === 'tod' ? -1 : 1; }) as $area)
                        <option value="{{ $area->nombre_a}}" {{ old('area_id', $areaId) == $area->id_a ? 'selected' : '' }}>
                            {{ $area->nombre_a }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="filter-item">
            <button type="submit" id="btn-filtrar">Filtrar</button>
        </div>
    </div>
</form>



    @endrole
    

    <a href="{{ route('cursos.create') }}" id="BCC">
        Crear Capacitación
    </a>
    <a href="{{ route('empleado.cursos.dni', Auth::user()->dni) }}" title="Capacitaciones de: {{ Auth::user()->name }}"><img
      src="{{ asset('storage/cursos/ver.png') }}" alt="Ver Capacitaciones" id="img-icono" style="margin-top: -75px;"></a>


    @if(!Auth::user()->hasRole('administrador') && !Auth::user()->hasRole('Gestor-cursos'))
        <br>
        <br>
        <h1 style="text-align: center;">Capacitaciones de: {{ Auth::user()->name }}</h1>
    @endif
    
        <div id="table-container"> 
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Obligatorio</th>
                        @role(['administrador', 'Gestor-cursos'])
                        <th>Area</th>
                        @endrole
                        <th>Fecha de Creación</th>
                        @role(['administrador', 'Gestor-cursos'])
                        <th>Cant. Instancias</th>
                        <th>Cant. Inscriptos</th>
                        <th>% Aprobados</th>
                        @endrole
                        @if(!Auth::user()->hasRole('administrador') && !Auth::user()->hasRole('Gestor-cursos'))
                        <th>Examen</th>
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
                    
                    @foreach($cursosPaginated as $curso)
                    <tr>
                        <td>{{ $curso->titulo }}</td>
                        <td>{{ $curso->descripcion }}</td>
                        <td>{{ $curso->obligatorio ? 'Sí' : 'No' }}</td>
                        @role(['administrador', 'Gestor-cursos'])
                        
                        <td>
                            
                        {{ $curso->areas }}
                        </td>

                        @endrole
                        <td>{{ $curso->created_at }}</td>
                        @role(['administrador', 'Gestor-cursos'])
                        <td>{{$curso->cantInstancias}}</td>
                        <td>{{ $curso->cantInscriptos}}</td>
                        <td>{{ number_format($curso->porcentajeAprobacion, 2) }}%</td>
                        @endrole
                        
                        @if(!Auth::user()->hasRole('administrador') && !Auth::user()->hasRole('Gestor-cursos'))
                            <td>
                                
                               
                                            @php
                                                $instance = $curso->instancia;
                                                $persona = $curso->persona;
                                                $enrolamiento = $curso->enrolamiento;
                                           
                                            @endphp
                                           
  
                                            @if(!empty($instance->examen))
                                                <a href="{{ $instance->examen }}" target="_blank">
                                                    <img src="{{ asset('storage/cursos/examen.png') }}" alt="Examen" id="img-icono">
                                                </a>

                                            @else
                                                N/A
                                            @endif
                                           
                                            
                                  
                                    
                            </td>
                            <td>
                                {{ $enrolamiento->evaluacion }}
                                
                            </td>
                       
                        @endif

                        <td>            
                        @role(['administrador', 'Gestor-cursos'])            
                          
                            <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" class="btn" title="Ver Instancia">
                                <img src="{{ asset('storage/cursos/tocar.png') }}"  loading="lazy" alt="Ver Instancia">
                            </a>
                        @endrole
                        @if(!Auth::user()->hasRole('administrador') && !Auth::user()->hasRole('Gestor-cursos'))

                        @if(Auth::user()->dni == $personaDni->dni && $enrolamiento->evaluacion == "Aprobado") 
                            
                            
                            <form action="{{ route('generateCertificate', ['cursoId' => $curso->id, 'personaId' => $personaDni->id_p, 'id_instancia' => $instance->id_instancia]) }}" method="POST" title="Ver Certificado">
                                @csrf
                                <button type="submit" id="icono"><img src="{{ asset('storage/cursos/ver.png') }}" alt="Ver" id="img-icono"></button>
                            </form>
                            
                        @elseif(Auth::user()->dni == $personaDni->dni && $enrolamiento->evaluacion == "Participacion")
                            @if(!Auth::user()->hasRole('administrador') && !Auth::user()->hasRole('Gestor-cursos'))
                                
                                <form action="{{ route('generateCertificate', ['cursoId' => $curso->id, 'personaId' => $personaDni->id_p, 'id_instancia' => $instance->id_instancia ]) }}" method="POST" title="Ver Certificado">
                                    @csrf
                                    <button type="submit" id="icono"><img src="{{ asset('storage/cursos/ver.png') }}" alt="Ver" id="img-icono"></button>
                                </form>
                            @endif
                        @endif
                        @endif
                        
                      
                        </td>

                        @role(['administrador', 'Gestor-cursos'])
                        <td id="acciones-cursos">
                        <div class="acciones-cursos">
                        <a href="{{ route('cursos.edit', $curso->id) }}" title="Editar capacitación">
                                <img src="{{ asset('storage/cursos/editar.png') }}" loading="lazy" alt="Editar" id="icono">
                            </a>

                            @if($curso->cantInscriptos == 0)
                                <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST"  onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta capacitación y sus instancias?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"  title="Eliminar capacitación" id="icono" >
                                        <img src="{{ asset('storage/cursos/eliminar.png') }}" loading="lazy" alt="Eliminar" >
                                    </button>
                                </form>
                            @else
                            <button type="submit"  title="Eliminacion no disponible" id="btn-disabled" disabled>
                                        <img src="{{ asset('storage/cursos/eliminar.png') }}" loading="lazy" alt="Eliminar" >
                                    </button>
                            @endif
                            <a href="{{ route('cursos.verCurso', $curso->id) }}" title="Ver datos de la capacitación" id="icono">
                                <img src="{{ asset('storage/cursos/ver.png') }}" loading="lazy" alt="Ver" id="img-icono">
                            </a>
                        </div>
                            
                        </td>
                        @endrole
                    </tr>
                    @endforeach
                    

                </tbody>
            </table>
            {{ $cursosPaginated->links('pagination::bootstrap-4') }}
        </div>
        
</div>



@endsection
@push('scripts')
    
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
@endpush
