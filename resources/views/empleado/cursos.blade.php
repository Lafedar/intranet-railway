@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<div class="container-fluid" id="empleados-cursos-conteiner">
    <h1 id="titulo-cursos-empleado">Listado de Cursos y Evaluaciones de: {{$persona->nombre_p}} {{$persona->apellido}}
    </h1>
    <form action="{{ route('exportarCursos', ['personaId' => $persona->id_p]) }}" method="GET">
        @csrf
        <button type="submit" id="asignar-btn">Exportar a Excel</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Curso</th>
                <th>Fecha</th>
                <th>Capacitador</th>
                <th>Modalidad</th>
                <th>Tipo</th>
                <th>Evaluación</th>
                <th>Obligatorio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cursosConDetalles as $curso)
                <tr>
                    <td>{{ $curso->titulo}}</td>

                    <td>{{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') ?? 'N/A' }}</td>

                    <td>{{ $curso->capacitador ?? 'N/A' }}</td>
                    <td>{{ $curso->modalidad ?? 'N/A' }}</td>
                    <td>{{$curso->tipo}}</td>
                    <td>{{ $curso->pivot->evaluacion ?? 'N/A' }}</td>
                    <td>{{ $curso->obligatorio == 1 ? 'Sí' : 'No' }}</td>
                    <td>
                        @if($curso->pivot->evaluacion == "Aprobado")
                            <form
                                action="{{ route('generarCertificado', ['instanciaId' => $curso->instancia, 'cursoId' => $curso->id, 'personaId' => $persona->id_p]) }}"
                                method="POST" id="form">
                                @csrf
                                <button type="submit" title="Ver Certificado" id="icono"><img
                                        src="{{ asset('storage/cursos/documentos.png') }}" loading="lazy" alt="Documentos"
                                        id="img-icono"></button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>