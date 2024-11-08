@extends('cursos.layouts.layout')
<div id="modalContainer"></div>
@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

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
<a href="{{ route('cursos.instancias.create', $curso->id) }}" class="btn btn-warning btn-sm">
    Crear Nueva Instancia
</a>

    <h1 class="mb-4">Instancias del Curso: {{ $curso->titulo }}</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Cupo</th>
                <th>Cupos Restantes</th>
                <th>Modalidad</th>
                <th>Capacitador</th>
                <th>Lugar</th>
                <th>Estado</th>
                <th>Version</th>
                <th>Acciones</th>

            </tr>
        </thead>
        <tbody>
            @foreach($instancesEnrollment as $instance)
            <tr>
                <td>{{ $instance->id_instancia }}</td>
                <td>{{ $instance->fecha_inicio->format('d/m/Y') }}</td>
                <td>{{ $instance->fecha_fin ? $instance->fecha_fin->format('d/m/Y') : 'N/A' }}</td>
                <td>
                    {{ $instance->cupo }}
                </td>
                <td>
                    
                    @if ($instance->restantes == null)
                        <span class="badge bg-danger text-dark">
                            <i class="bi bi-x-circle-fill"></i> completo
                        </span>

                    @elseif ($instance->restantes === 0)
                        <span class="badge bg-danger text-dark">
                            <i class="bi bi-x-circle-fill"></i> completo 
                        </span>
                    @else
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle-fill"></i> {{ $instance->restantes }} disponibles
                        </span>
                    @endif
                </td>
                <td>{{ $instance->modalidad }}</td>
                <td>{{ $instance->capacitador }}</td>
                <td>{{ $instance->lugar }}</td>
                <td>{{ $instance->estado }}</td>
                
                <td>{{ $instance->version }}</td>
                <td>
                @php
                        // Verificar la disponibilidad de la instancia
                        $availabilityItem = $availability->firstWhere('idInstance', $instance->id);
                    @endphp

                    @if ($availabilityItem)
                        @if ($availabilityItem['enabled'])

                            @if ($instance->isEnrolled)
                                Inscripto
                            @else
                                @if ($instance->restantes > 0)
                                    <a href="{{ route('cursos.instancias.personas', ['cursoId' => $curso->id, 'instanceId' => $instance->id_instancia]) }}" class="btn btn-primary btn-sm">Inscribir Personas</a>
                                @endif
                            @endif
                        
                      
                        @endif
                    @else
                     
                    @endif
                <a href="{{ route('cursos.instancias.edit', $instance->id) }}" class="btn btn-warning btn-sm">Editar</a>
                
                
                @if ($instance->restantes == $instance->cupo)
                    <form action="{{ route('cursos.instancias.destroy', ['cursoId' => $curso->id, 'instanciaId' => $instance->id_instancia]) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta instancia?');">
                        @csrf
                        @method('DELETE')  
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                @endif
               
                
                
                
                
                <a href="{{ route('cursos.instancias.inscriptos', [$instance->id_instancia, $curso->id]) }}" class="btn btn-secondary btn-sm">
                    Ver personas inscriptas
                </a>

                </td>
                                
            </tr>
            @endforeach
          
        </tbody>
       
    </table>

    
    <a href="{{ route('cursos.index') }}" class="btn btn-secondary">Regresar al listado de Cursos</a>
</div>


<div id="footer-lafedar"></div>
@endsection

<script>
    $(document).ready(function() {
        // Ocultar los mensajes de éxito y error después de 3 segundos
        $('.alert').each(function() {
            var alert = $(this);
            setTimeout(function() {
                alert.fadeOut('slow');
            }, 3000);
        });
    });
</script>