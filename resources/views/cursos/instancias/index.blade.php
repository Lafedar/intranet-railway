@extends('layouts.app')

@push('styles')
    <!-- Link de Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap&italic=true" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cursos.css') }}">

@endpush
@section('content')
<div id="container">
    <div id="modalContainer"></div>

    <!-- Mensajes de éxito y error -->
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




    <div id="encabezados">
        <h1 id="titulo">Capacitación: {{ $course->titulo }} </h1>
    </div>




    @role(['administrador', 'Gestor-cursos'])
    <a href="{{ route('cursos.instancias.create', ['instanciaId' => $amountInstances, 'curso' => $course->id]) }}"
        class="btn btn-warning btn-sm mb-3" id="btn-agregar">
        Crear Nueva Instancia
    </a>
    @endrole

    <a href="{{ route('cursos.index') }}" class="btn btn-secondary" id="asignar-btn">Volver</a>


<div id="table-container">
<table>
        <thead>
            <tr>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Hora</th>
                @role(['administrador', 'Gestor-cursos'])
                <th>Cupo</th>
                <th>Cupos Restantes</th>
                <th>Modalidad</th>
                <th>Capacitador</th>
                <th>Código</th>
                <th>Lugar</th>
                <th>Estado</th>
                <th>Version</th>
                <th>Examen</th>
                <th>% de Aprobación</th>
                <th>Certificado</th>
                @endrole
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($instancesEnrollment as $instance)
                        <tr>


                            <td>{{ $instance->formatted_start_date}}</td>
                            <td>{{ $instance->formatted_end_date}}</td>
                            <td>{{ $instance->formatted_hour}}</td>


                            @role(['administrador', 'Gestor-cursos'])
                            <td>
                               
                                @if($instance->quota === null || $instance->quota === '')
                                    <!-- Si cantInscriptos es null, mostramos 0 -->
                                    {{ $instance->amountRegistered ?? 0 }}
                                @else
                                    {{ $instance->quota }}
                                @endif
                            </td>
                            <td>
                                @if ($instance->remaining == null)
                                    <span class="badge bg-danger text-dark">
                                        <i class="bi bi-x-circle-fill"></i> completo
                                    </span>
                                @elseif ($instance->remaining === 0)
                                    <span class="badge bg-danger text-dark">
                                        <i class="bi bi-x-circle-fill"></i> completo
                                    </span>
                                @elseif ($instance->remaining < 0)
                                    <span class="badge bg-danger text-dark">
                                        <i class="bi bi-x-circle-fill"></i> completo
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill"></i> {{ $instance->remaining }} disponibles
                                    </span>
                                @endif
                            </td>
                            <td>{{ $instance->modalidad }}</td>
                            <td>{{ $instance->capacitador }}</td>
                            <td>{{ $instance->codigo }}</td>
                            <td>{{ $instance->lugar }}</td>
                            <td>{{ $instance->estado }}</td>
                            <td>{{ $instance->version }}</td>
                            <td>
                                @if(!empty($instance->examen))
                                <a href="{{ $instance->examen }}" target="_blank">
                                    <img src="{{ asset('storage/cursos/examen.png') }}" alt="Examen" id="img-icono">
                                </a>

                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ number_format($instance->percentageAPP, 2) }}%</td>
                            <td>{{ $instance->certificado}}</td>

                            @endrole
                            <td>
                                @role(['administrador', 'Gestor-cursos'])
                                @php
                                    // Verificar la disponibilidad de la instancia
                                    $availabilityItem = $availability->firstWhere('idInstance', $instance->id);
                                @endphp

                                @if ($availabilityItem)
                                    @if ($availabilityItem['enabled'])
                                        @if ($instance->remaining > 0)
                                            <a href="{{ route('cursos.instancias.personas', ['cursoId' => $course->id, 'instanceId' => $instance->id_instancia]) }}"
                                                style="margin: 5px" title="Inscribir personas">
                                                <img src="{{ asset('storage/cursos/inscribir.png') }}" alt="Inscribir" id="img-icono">
                                            </a>
                                        @else
                                            <!-- Botón deshabilitado cuando no hay quotas restantes -->
                                            <a href="javascript:void(0)" id="btn-disabled" title="Inscripción no disponible">
                                                <img src="{{ asset('storage/cursos/inscribir.png') }}" alt="Inscribir" id="img-icono">
                                            </a>
                                        @endif
                                    @else
                                        <!-- Botón deshabilitado cuando el ítem no está habilitado -->
                                        <a href="javascript:void(0)" id="btn-disabled" title="Inscripción no disponible">
                                            <img src="{{ asset('storage/cursos/inscribir.png') }}" alt="Inscribir" id="img-icono">
                                        </a>
                                    @endif
                                @endif

                                @if($instance->estado == "Activo")
                                    <a href="{{ route('cursos.instancias.edit', ['instancia' => $instance->id_instancia, 'cursoId' => $course->id]) }}"
                                        id="iconos-instancias" title="Editar Instancia" @if($instance->remaining == 0) disabled @endif>
                                        <img src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono">
                                    </a>
                                @else
                                    <button href="#" id="iconos-instancias" title="Edicion no disponible" disabled>
                                        <img src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono"
                                            id="iconos-disabled">
                                    </button>
                                @endif

                                @if ($instance->remaining == $instance->quota)
                                    <form
                                        action="{{ route('cursos.instancias.destroy', ['cursoId' => $course->id, 'instanciaId' => $instance->id_instancia]) }}"
                                        method="POST" id="btn-eliminar"
                                        onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta instancia?');" id="icono">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" id="icono" title="Eliminar Instancia" @if($instance->remaining != $instance->quota) disabled @endif>
                                            <img src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar" id="img-icono">
                                        </button>
                                    </form>
                                @else
                                    <button type="button" id="btn-disabled" title="Eliminación no disponible" disabled>
                                        <img src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar" id="img-icono">
                                    </button>

                                @endif

                                @if($instance->amountAnnexes == 0)
                                    <a href="javascript:void(0);" title="No hay documentos asociados" id="btn-disabled" disabled>
                                        <img src="{{ asset('storage/cursos/documentos.png') }}" alt="Documentos" id="img-icono"
                                            disabled>
                                    </a>
                                @else
                                    <a href="{{ route('verDocumentos', [$instance->id_instancia, $course->id]) }}" title="Ver Documentos"
                                        id="iconos-instancias" @if($instance->estado != 'Activo') disabled @endif>
                                        <img src="{{ asset('storage/cursos/documentos.png') }}" alt="Documentos" id="img-icono"
                                            @if($instance->estado != 'Activo') disabled @endif>
                                    </a>
                                @endif

                                @if($instance->amountRegistered > 0)
                                    <a href="{{ route('cursos.instancias.inscriptos', [$instance->id_instancia, $course->id, 'tipo' => 'ane']) }}"
                                        title="Ver Inscriptos" id="iconos-instancias" @if(!$instance->estado == 'Activo') disabled @endif>
                                        <img src="{{ asset('storage/cursos/inscriptos.png') }}" alt="Inscriptos" id="img-icono">
                                    </a>
                                @else
                                    <a>
                                        <img src="{{ asset('storage/cursos/inscriptos.png') }}" alt="Inscriptos" id="img-icono-disabled" title="No hay inscriptos">
                                    </a>
                                @endif
                                @if($instance->estado == "Activo")
                                    <a href="{{ route('cambiarEstado', ['instanciaId' => $instance->id_instancia, 'cursoId' => $course->id, 'bandera' => 'No']) }}"
                                        title="Cerrar Instancia" @if(!$instance->restantes > 0) disabled @endif>
                                        <img src="{{ asset('storage/cursos/cerrar.png') }}" alt="Cerrar" id="img-icono">
                                    </a>
                                @else
                                    <a href="{{ route('cambiarEstado', ['instanciaId' => $instance->id_instancia, 'cursoId' => $course->id, 'bandera' => 'Si']) }}"
                                        title="Abrir Instancia">
                                        <img src="{{ asset('storage/cursos/abrir.png') }}" alt="Abrir" id="img-icono">
                                    </a>
                                @endif
                                @endrole
                            </td>

                        </tr>
            @endforeach
        </tbody>
    </table>
</div>
    
</div>


@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            // Ocultar los mensajes de éxito y error después de 3 segundos
            setTimeout(function () {
                $('.alert').fadeOut('slow'); // 'slow' es la duración de la animación
            }, 3000); // 3000 milisegundos = 3 segundos
        });
    </script>

@endpush