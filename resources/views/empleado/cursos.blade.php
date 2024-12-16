@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<div class="container-fluid" id="empleados-cursos-conteiner">
    <h1 id="titulo-cursos-empleado">Listado de Cursos y Evaluaciones de: {{$persona->nombre_p}} {{$persona->apellido}}
    </h1>

    <table>
        <thead>
            <tr>
                <th>Curso</th>
                <th>Instancia</th>
                <th>Area/s</th>
                <th>Fecha</th>
                <th>Capacitador</th>
                <th>Modalidad</th>
                <th>Tipo</th>
                <th>Evaluación</th>
                <th>Obligatorio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cursosConDetalles as $curso)
                <tr>
                    <td>{{ $curso->titulo}}</td>
                    <td>{{ $curso->pivot->id_instancia }}</td>
                    <td> @if($curso->areas->isEmpty())
                        <span>N/A</span>
                    @else

                        @foreach($curso->areas->take(5) as $area)
                            <span>{{ $area->nombre_a ?? 'N/A' }}/</span><br>
                        @endforeach
                        @if($curso->areas->count() > 5)
                            <span>...</span>
                        @endif
                    @endif

                    </td>
                    <td>{{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') ?? 'N/A' }}</td>

                    <td>{{ $curso->capacitador ?? 'N/A' }}</td>
                    <td>{{ $curso->modalidad ?? 'N/A' }}</td>
                    <td>{{$curso->tipo}}</td>
                    <td>{{ $curso->pivot->evaluacion ?? 'N/A' }}</td>
                    <td>{{ $curso->obligatorio == 1 ? 'Sí' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>