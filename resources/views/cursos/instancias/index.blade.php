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
@role(['administrador', 'Gestor-cursos'])
<a href="{{ route('cursos.instancias.create', ['instanciaId' => $cantInstancias, 'curso' => $curso->id]) }}" class="btn btn-warning btn-sm">
    Crear Nueva Instancia
</a>
@endrole
    <h1 class="mb-4">Instancias del Curso: {{ $curso->titulo }}</h1>
    <a href="{{ route('cursos.index') }}" class="btn btn-secondary">Regresar al listado de Cursos</a>
    <br><br>
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                @role(['administrador', 'Gestor-cursos'])
                    <th>Cupo</th>
                    <th>Cupos Restantes</th>
                    <th>Modalidad</th>
                    <th>Capacitador</th>
                    <th>Lugar</th>
                    <th>Estado</th>
                    <th>Version</th>
                @endrole
                    <th>Acciones</th>
                

            </tr>
        </thead>
        <tbody>
            @foreach($instancesEnrollment as $instance)
            <tr>
                <td>{{ $instance->id_instancia }}</td>
                <td>{{ \Carbon\Carbon::parse($instance->fecha_inicio)->format('d/m/Y') }}</td>

                <td>{{ \Carbon\Carbon::parse($instance->fecha_inicio)->format('d/m/Y') }}</td>
                @role(['administrador', 'Gestor-cursos'])
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
                @endrole
                <td>
                    @role(['administrador', 'Gestor-cursos'])
                        @php
                                // Verificar la disponibilidad de la instancia
                                $availabilityItem = $availability->firstWhere('idInstance', $instance->id);
                            @endphp

                            @if ($availabilityItem)
                                @if ($availabilityItem['enabled'])

                                    
                                        @if ($instance->restantes > 0)
                                            <a href="{{ route('cursos.instancias.personas', ['cursoId' => $curso->id, 'instanceId' => $instance->id_instancia]) }}" class="btn btn-primary btn-sm" style="margin: 3px">Inscribir Personas</a>
                                        @endif
                                @endif
                            @else
                            
                            @endif
                        <a href="{{ route('cursos.instancias.edit', ['instancia' => $instance->id_instancia, 'cursoId' => $curso->id]) }}" class="btn btn-warning btn-sm" style="margin: 3px">Editar</a>
                        
                        
                        @if ($instance->restantes == $instance->cupo)
                            <form action="{{ route('cursos.instancias.destroy', ['cursoId' => $curso->id, 'instanciaId' => $instance->id_instancia]) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta instancia?');">
                                @csrf
                                @method('DELETE')  
                                <button type="submit" class="btn btn-danger btn-sm" style="margin: 3px">Eliminar</button>
                            </form>
                        @endif
                    
                        <a href="{{ route('verDocumentos', [$instance->id_instancia, $curso->id]) }}" class="btn btn-primary btn-sm">
                            Ver documentos
                        </a>

                    
                    <a href="{{ route('cursos.instancias.inscriptos', [$instance->id_instancia, $curso->id, 'tipo'=> 'ane']) }}" class="btn btn-secondary btn-sm">
                            Ver personas inscriptas
                    </a>
                    @endrole
                    @if(Auth::user()->dni == $persona->dni && $evaluacion == "Aprobado") 
                        @if(!Auth::user()->hasRole('administrador') && !Auth::user()->hasRole('Gestor-cursos'))
                            <form action="{{ route('generarCertificado', ['instanciaId' => $instance->id_instancia, 'cursoId' => $curso->id, 'personaId' => $persona->id_p]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Certificado</button>
                                </form>
                        @endif
                            
                    @endif
                </td>             
            </tr>
            @endforeach
          
        </tbody>
       
    </table>

    
   
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