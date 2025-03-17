@extends('layouts.app')

@push('styles')

    <!-- Enlaces a fuentes y Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap&italic=true" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/cursos.css') }}">

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


        <div id="encabezados">
            <h1 id="titulo">Inscriptos a la Capacitación: {{ $course->titulo }}</h1>

        </div>

        <br>
        <br>
        <!-- Si no hay inscriptos -->
        @if($registered->isEmpty())
            <p><b>No hay inscriptos en esta capacitación.</b></p>
        @else
            <!-- Formulario para crear planilla -->
            @role(['administrador', 'Gestor-cursos'])
            <div id="contenedor-botones">
                <a href="{{ route('cursos.instancias.index', ['cursoId' => $course->id]) }}" id="volver-instancias">Volver</a>
                <form
                    action="{{ route('exportarInscriptos', ['cursoId' => $course->id, 'instanciaId' => $instance->id_instancia]) }}"
                    method="GET">
                    @csrf
                    <button type="submit" id="BI">Exportar a Excel</button>
                </form>

                @if(!is_null($annexed))
                    <form
                        action="{{ route('verPlanilla', ['cursoId' => $course->id, 'instanciaId' => $instance->id_instancia, 'tipo' => 'ane']) }}"
                        method="GET">
                        <button type="submit" id="BI">Ver Registro</button>
                    </form>
                @else

                    <form action="#" method="GET">
                        <button type="submit" id="BI" disabled>Ver Registro</button>
                    </form>

                @endif

                @if($amountApproved > 0)
                    <form action="{{ route('enviarMail', ['cursoId' => $course->id, 'instanciaId' => $instance->id_instancia]) }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" id="BI">Enviar Certificado</button>
                    </form>
                @elseif($instance->certificado == "Participacion")
                    <form action="{{ route('enviarMail', ['cursoId' => $course->id, 'instanciaId' => $instance->id_instancia]) }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" id="BI">Enviar Certificado</button>
                    </form>
                @else
                    <form action="{{ route('enviarMail', ['cursoId' => $course->id, 'instanciaId' => $instance->id_instancia]) }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" id="BI" disabled>Enviar Certificado</button>
                    </form>

                @endif


                @if($instance->certificado != "Participacion")
                    <form
                        action="{{ route('evaluarInstanciaTodos', ['cursoId' => $course->id, 'instanciaId' => $instance->id_instancia, 'bandera' => 0]) }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" id="BI" style="margin-left: 480px">Aprobar a
                            todos</button>
                    </form>

                    <form
                        action="{{ route('evaluarInstanciaTodos', ['cursoId' => $course->id, 'instanciaId' => $instance->id_instancia, 'bandera' => 1]) }}"
                        method="POST">
                        @csrf
                        <button type="submit" id="BI">Desaprobar a todos</button>
                    </form>
                @endif


            </div>
            @endrole

            <div id="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Legajo</th>
                            <th>Apellido y Nombre</th>
                            <th>Fecha de Inscripción</th>
                            <th>Versión de Instancia</th>
                            <th>Evaluación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registered as $enlistment)
                            <tr>
                                <td>{{ $enlistment->persona->legajo }}</td>
                                <td>
                                    @if ($enlistment->persona)
                                        {{ $enlistment->persona->apellido }} {{ $enlistment->persona->nombre_p }}
                                    @else
                                        Persona no encontrada
                                    @endif
                                </td>
                                <td>{{ $enlistment->fecha_enrolamiento ? $enlistment->fecha_enrolamiento->format('d/m/Y H:i') : 'No disponible' }}
                                </td>
                                <td>{{ $instance->version ?? 'N/A' }}</td>
                               
                                <td>{{ $enlistment->evaluacion }}</td>
                           
                                <td>
                                    @role(['administrador', 'Gestor-cursos'])
                                    @if($enlistment->evaluacion == "No Aprobado" | $instance->certificado == "Participacion")
                                        <form
                                            action="{{ route('desinscribir', ['userId' => $enlistment->id_persona, 'instanciaId' => $instance->id_instancia, 'cursoId' => $course->id]) }}"
                                            method="POST" id="form">
                                            @csrf
                                            <button type="submit" title="Desuscribir" id="icono"><img
                                                    src="{{ asset('storage/cursos/desuscribir.png') }}" loading="lazy" alt="Desuscribir"
                                                    id="img-icono"></button>
                                        </form>
                                    @endif
                                    @if($instance->certificado != "Participacion")
                                        @if($enlistment->evaluacion == "N/A")
                                            <form
                                                action="{{ route('evaluarInstancia', ['userId' => $enlistment->id_persona, 'instanciaId' => $instance->id_instancia, 'cursoId' => $course->id, 'bandera' => 1]) }}"
                                                method="POST" id="form">
                                                @csrf
                                                <button type="submit" title="Desaprobar" id="icono"><img
                                                        src="{{ asset('storage/cursos/exit.png') }}" loading="lazy" alt="Desaprobar"
                                                        id="img-icono"></button>
                                            </form>
                                            <form
                                                action="{{ route('evaluarInstancia', ['userId' => $enlistment->id_persona, 'instanciaId' => $instance->id_instancia, 'cursoId' => $course->id, 'bandera' => 0]) }}"
                                                method="POST" id="form">
                                                @csrf
                                                <button type="submit" title="Aprobar" id="icono"><img
                                                        src="{{ asset('storage/cursos/aprobar.png') }}" loading="lazy" alt="Aprobar"
                                                        id="img-icono"></button>
                                            </form>
                                        @elseif($enlistment->evaluacion == "Aprobado") 
                                            <form
                                                action="{{ route('evaluarInstancia', ['userId' => $enlistment->id_persona, 'instanciaId' => $instance->id_instancia, 'cursoId' => $course->id, 'bandera' => 1]) }}"
                                                method="POST" id="form">
                                                @csrf
                                                <button type="submit" title="Desaprobar" id="icono"><img
                                                        src="{{ asset('storage/cursos/exit.png') }}" loading="lazy" alt="Desaprobar"
                                                        id="img-icono"></button>
                                            </form>
                                        @else
                                            <form
                                                action="{{ route('evaluarInstancia', ['userId' => $enlistment->id_persona, 'instanciaId' => $instance->id_instancia, 'cursoId' => $course->id, 'bandera' => 0]) }}"
                                                method="POST" id="form">
                                                @csrf
                                                <button type="submit" title="Aprobar" id="icono"><img
                                                        src="{{ asset('storage/cursos/aprobar.png') }}" loading="lazy" alt="Aprobar"
                                                        id="img-icono"></button>
                                            </form>
                                        @endif
                                        @endrole
                                    @endif

                                    @role(['administrador', 'Gestor-cursos'])
                                    @if($enlistment->evaluacion == "Aprobado")
                                        <form
                                            action="{{ route('generarCertificado', ['cursoId' => $course->id, 'personaId' => $enlistment->id_persona, 'id_instancia' => $instance->id_instancia ]) }}"
                                            method="POST" id="form">
                                            @csrf

                                            <button type="submit" title="Ver Certificado" id="icono"><img
                                                    src="{{ asset('storage/cursos/documentos.png') }}" loading="lazy" alt="Documentos"
                                                    id="img-icono"></button>
                                        </form>
                                    @endif
                                    @if($instance->certificado == "Participacion")
                                        <form
                                            action="{{ route('generarCertificado', ['cursoId' => $course->id, 'personaId' => $enlistment->id_persona, 'id_instancia' => $instance->id_instancia ]) }}"
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
            </div>

        @endif
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