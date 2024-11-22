@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger text-center">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1>Inscriptos en el Curso:</h1>
    <h3>{{ $curso->titulo }}</h3>
    <br>
    
    <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" class="btn btn-secondary" style="margin-bottom: 10px;">Volver</a>

    @if($inscriptos->isEmpty())
        <p>No hay inscriptos en este curso.</p>
    @else
        <!-- Formulario para crear planilla -->
        
        @role(['administrador', 'Gestor-cursos'])
            <!-- Botones adicionales debajo del formulario -->
            <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                <form action="{{ route('exportarInscriptos', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia]) }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-success">Exportar a Excel</button>
                </form>

                <form action="{{ route('evaluarInstanciaTodos', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia, 'bandera' => 0]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Aprobar a todos</button>
                </form>

                <form action="{{ route('evaluarInstanciaTodos', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia, 'bandera' => 1]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Desaprobar a todos</button>
                </form>
                
                @if($anexos != null)
                    <form action="{{ route('verPlanilla', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia, 'tipo' => 'ane' ]) }}" method="GET" style="margin-bottom: 20px;">
                        <button type="submit" class="btn btn-success">Ver Anexo</button>
                    </form>
                @else
                    Agregue un Anexo para ver la planilla
                @endif
            
            </div>
        @endrole

        <!-- Tabla de inscriptos -->
        <table class="table table-bordered table-striped text-center">
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
                        <td>{{ $enrolamiento->fecha_enrolamiento ? $enrolamiento->fecha_enrolamiento->format('d/m/Y H:i') : 'No disponible' }}</td>
                        <td>{{ $instancia->version ?? 'N/A' }}</td>
                        <td>{{ $enrolamiento->evaluacion }}</td>
                        <td>
                        @role(['administrador', 'Gestor-cursos'])
                            <form action="{{ route('desinscribir', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Desuscribir</button>
                            </form>
                            @if($enrolamiento->evaluacion == "Aprobado") 
                                <form action="{{ route('evaluarInstancia', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'bandera' => 1]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Desaprobar</button>
                                </form>

                                
                            @else
                                <form action="{{ route('evaluarInstancia', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'bandera' => 0]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Aprobar</button>
                                </form>
                            @endif
                        @endrole
                       

                        @if(Auth::user()->dni == $enrolamiento->persona->dni && $enrolamiento->evaluacion == "Aprobado") 
    <form action="{{ route('generarCertificado', ['instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'personaId' => $enrolamiento->id_persona]) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Certificado</button>
    </form>
@endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#success-message').fadeOut('slow');
            }, 3000);

            setTimeout(function() {
                $('#error-message').fadeOut('slow');
            }, 3000);
        });
    </script>
@endsection

@endsection
