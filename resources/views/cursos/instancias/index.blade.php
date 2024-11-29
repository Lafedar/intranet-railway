<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instancias del Curso</title>
    <!-- Link de Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap&italic=true" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cursos.css') }}">


</head>

<body>

    <div class="container mt-5">
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
        <a href="{{ url('/home') }}" class="img-logo">
            <img src="{{ asset('storage/cursos/logo-cursos.png') }}" alt="Logo Cursos">
        </a>



        <div id="encabezados">
            <h1 id="titulo">Curso: {{ $curso->titulo }} <br> {{ $curso->created_at->format('d/m/Y') }}</h1>
        </div>




        @role(['administrador', 'Gestor-cursos'])
        <a href="{{ route('cursos.instancias.create', ['instanciaId' => $cantInstancias, 'curso' => $curso->id]) }}"
            class="btn btn-warning btn-sm mb-3" id="BCI">
            Crear Nueva Instancia
        </a>
        @endrole

        <a href="{{ route('cursos.index') }}" class="btn btn-secondary" id="volver">Volver</a>



        <table>
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
                                    <td>{{ $instance->cupo }}</td>
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
                                                    <a href="{{ route('cursos.instancias.personas', ['cursoId' => $curso->id, 'instanceId' => $instance->id_instancia]) }}"
                                                        style="margin: 5px" title="Inscribir personas">
                                                        <img src="{{ asset('storage/cursos/inscribir.png') }}" alt="Inscribir" id="img-icono">
                                                    </a>
                                                @endif
                                            @endif
                                        @else
                                        @endif
                                        @if($instance->estado == "Activo")
                                            <a href="{{ route('cursos.instancias.edit', ['instancia' => $instance->id_instancia, 'cursoId' => $curso->id]) }}"
                                                style="margin: 5px" title="Editar Instancia">
                                                <img src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono">
                                            </a>
                                        @endif

                                        @if ($instance->restantes == $instance->cupo)
                                            <form
                                                action="{{ route('cursos.instancias.destroy', ['cursoId' => $curso->id, 'instanciaId' => $instance->id_instancia]) }}"
                                                method="POST" style="display:inline;"
                                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta instancia?');"
                                                id="icono">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" id="icono" title="Eliminar Instancia">
                                                    <img src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar" id="img-icono">
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('verDocumentos', [$instance->id_instancia, $curso->id]) }}"
                                            title="Ver Documentos" style="margin: 5px;">
                                            <img src="{{ asset('storage/cursos/documentos.png') }}" alt="Inscriptos" id="img-icono">
                                        </a>

                                        <a href="{{ route('cursos.instancias.inscriptos', [$instance->id_instancia, $curso->id, 'tipo' => 'ane']) }}"
                                            title="Ver Inscriptos" style="margin: 5px;">
                                            <img src="{{ asset('storage/cursos/inscriptos.png') }}" alt="Inscriptos" id="img-icono">
                                        </a>
                                        @if($instance->estado == "Activo")
                                            <a href="{{ route('cambiarEstado', ['instanciaId' => $instance->id_instancia, 'cursoId' => $curso->id, 'bandera' => 'No']) }}"
                                                title="Cerrar Instancia">
                                                <img src="{{ asset('storage/cursos/cerrar.png') }}" alt="Cerrar" id="img-icono">
                                            </a>
                                        @else
                                            <a href="{{ route('cambiarEstado', ['instanciaId' => $instance->id_instancia, 'cursoId' => $curso->id, 'bandera' => 'Si']) }}"
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



    <!-- Scripts de Bootstrap y jQuery -->
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

</body>

</html>