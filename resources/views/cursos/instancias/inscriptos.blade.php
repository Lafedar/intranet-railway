@extends('layouts.app')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscriptos en el Curso</title>

    <!-- Enlaces a fuentes y Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap&italic=true" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cursos.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> 
  



</head>

<body>
    <div class="container">

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
            <h1 id="titulo">Inscriptos a la Capacitación: {{ $curso->titulo }}</h1>

        </div>

        <br>
        <br>
        <!-- Si no hay inscriptos -->
        @if($inscriptos->isEmpty())
            <p><b>No hay inscriptos en esta capacitación.</b></p>
        @else
            <!-- Formulario para crear planilla -->
            @role(['administrador', 'Gestor-cursos'])
            <div id="contenedor-botones">
                <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" id="volver-instancias">Volver</a>
                <form
                
                    action="{{ route('exportarInscriptos', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia]) }}"
                    method="GET">
                    @csrf
                    <button type="submit" id="BI">Exportar a Excel</button>
                </form>
                @if($anexos != null)
                    <form
                        action="{{ route('verPlanilla', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia, 'tipo' => 'ane']) }}"
                        method="GET">
                        <button type="submit" id="BI">Ver Anexo</button>
                    </form>
                @else
                    <form action="#" method="GET">
                        <button type="submit" id="BI" disabled>Ver Anexo</button>
                    </form>

                @endif

                @if($cantAprobados > 0)
                    <form
                        action="{{ route('enviarMail', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia]) }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" id="BI">Enviar Certificado</button>
                    </form>
                @else
                    <form
                        action="{{ route('enviarMail', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia]) }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" id="BI" disabled>Enviar Certificado</button>
                    </form>
                @endif


                <form
                    action="{{ route('evaluarInstanciaTodos', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia, 'bandera' => 0]) }}"
                    method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" id="BI" style="margin-left: 480px">Aprobar a
                        todos</button>
                </form>

                <form
                    action="{{ route('evaluarInstanciaTodos', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia, 'bandera' => 1]) }}"
                    method="POST">
                    @csrf
                    <button type="submit" id="BI">Desaprobar a todos</button>
                </form>


            </div>
            @endrole

            <!-- Tabla de inscriptos -->
            <table>
                <thead>
                    <tr>
                        <th>Legajo</th>
                        <th>Nombre y Apellido</th>
                        <th>Fecha de Inscripción</th>
                        <th>Versión de Instancia</th>
                        <th>Evaluación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscriptos as $enrolamiento)
                        <tr>
                            <td>{{ $enrolamiento->persona->legajo }}</td>
                            <td>
                                @if ($enrolamiento->persona)
                                    {{ $enrolamiento->persona->nombre_p }} {{ $enrolamiento->persona->apellido }}
                                @else
                                    Persona no encontrada
                                @endif
                            </td>
                            <td>{{ $enrolamiento->fecha_enrolamiento ? $enrolamiento->fecha_enrolamiento->format('d/m/Y H:i') : 'No disponible' }}
                            </td>
                            <td>{{ $instancia->version ?? 'N/A' }}</td>
                            <td>{{ $enrolamiento->evaluacion }}</td>
                            <td>
                                @role(['administrador', 'Gestor-cursos'])
                                @if($enrolamiento->evaluacion == "No Aprobado")
                                    <form
                                        action="{{ route('desinscribir', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id]) }}"
                                        method="POST" id="form">
                                        @csrf
                                        <button type="submit" title="Desuscribir" id="icono"><img
                                                src="{{ asset('storage/cursos/desuscribir.png') }}" loading="lazy" alt="Desuscribir"
                                                id="img-icono"></button>
                                    </form>
                                @endif
                                @if($enrolamiento->evaluacion == "N/A")
                                    <form
                                        action="{{ route('evaluarInstancia', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'bandera' => 1]) }}"
                                        method="POST" id="form">
                                        @csrf
                                        <button type="submit" title="Desaprobar" id="icono"><img
                                                src="{{ asset('storage/cursos/exit.png') }}" loading="lazy" alt="Desaprobar"
                                                id="img-icono"></button>
                                    </form>
                                    <form
                                        action="{{ route('evaluarInstancia', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'bandera' => 0]) }}"
                                        method="POST" id="form">
                                        @csrf
                                        <button type="submit" title="Aprobar" id="icono"><img
                                                src="{{ asset('storage/cursos/aprobar.png') }}" loading="lazy" alt="Aprobar"
                                                id="img-icono"></button>
                                    </form>
                                @elseif($enrolamiento->evaluacion == "Aprobado") 
                                    <form
                                        action="{{ route('evaluarInstancia', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'bandera' => 1]) }}"
                                        method="POST" id="form">
                                        @csrf
                                        <button type="submit" title="Desaprobar" id="icono"><img
                                                src="{{ asset('storage/cursos/exit.png') }}" loading="lazy" alt="Desaprobar"
                                                id="img-icono"></button>
                                    </form>
                                @else
                                    <form
                                        action="{{ route('evaluarInstancia', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'bandera' => 0]) }}"
                                        method="POST" id="form">
                                        @csrf
                                        <button type="submit" title="Aprobar" id="icono"><img
                                                src="{{ asset('storage/cursos/aprobar.png') }}" loading="lazy" alt="Aprobar"
                                                id="img-icono"></button>
                                    </form>
                                @endif
                                @endrole

                                @role(['administrador', 'Gestor-cursos'])
                                @if($enrolamiento->evaluacion == "Aprobado")
                                    <form
                                        action="{{ route('generarCertificado', ['instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'personaId' => $enrolamiento->id_persona]) }}"
                                        method="POST" id="form">
                                        @csrf
                                        <button type="submit" title="Ver Certificado" id="icono"><img
                                                src="{{ asset('storage/cursos/documentos.png') }}" loading="lazy" alt="Documentos"
                                                id="img-icono"></button>
                                    </form>
                                @endif
                                @endrole
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Scripts -->
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