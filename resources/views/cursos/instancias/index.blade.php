@extends('cursos.layouts.layout')
<div id="modalContainer"></div>
@section('content')
<div class="container mt-5">
<a href="{{ route('cursos.instancias.create', $curso->id) }}" class="btn btn-primary">
    Crear Nueva Instancia
</a>

    <h1 class="mb-4">Instancias del Curso: {{ $curso->titulo }}</h1>
    
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Cupo</th>
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
                <td>{{ $instance->id }}</td>
                <td>{{ $instance->fecha_inicio->format('d/m/Y') }}</td>
                <td>{{ $instance->fecha_fin }}</td>
                <td>@if ($instance->cupo === 0)
                        <span class="badge bg-danger text-dark">
                            <i class="bi bi-x-circle-fill"></i> completo
                        </span>
                    @else
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle-fill"></i> {{ $instance->cupo }} disponibles
                        </span>
                    @endif
                </td>
                <td>{{ $instance->modalidad }}</td>
                <td>{{ $instance->capacitador }}</td>
                <td>{{ $instance->lugar }}</td>
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
                                @if ($instance->cupo > 0)
                                    <a href="{{ route('cursos.instancias.inscription', ['cursoId' => $curso->id, 'instanciaId' => $instance->id]) }}" class="btn btn-primary btn-sm">Inscribirse</a>
                                @endif
                            @endif
                        
                        @else
                            <!-- Mostrar como Inactivo si no está habilitado -->
                            Inactivo
                        @endif
                    @else
                        Inactivo
                    @endif
                </td>
                <td>{{ $instance->version }}</td>
                <td>
            
                <a href="{{ route('cursos.instancias.edit', $instance->id) }}" class="btn btn-warning btn-sm">Editar</a>
                <form action="{{ route('cursos.instancias.destroy', $instance->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta instancia?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                </form>

                </td>
                                
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Botón para regresar al listado de cursos -->
    <a href="{{ route('cursos.index') }}" class="btn btn-secondary">Regresar al listado de Cursos</a>
</div>


<div id="footer-lafedar"></div>
@endsection