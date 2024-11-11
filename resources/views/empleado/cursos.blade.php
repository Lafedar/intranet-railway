@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Listado de Cursos y Evaluaciones de: {{$persona->nombre_p}} {{$persona->apellido}}</h1>

    <table class="table table-bordered table-striped text-center">
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
                    <td>@foreach ($curso->areas as $area)
                            <span>{{ $area->nombre_a }}</span><br>
                        @endforeach
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
@endsection

