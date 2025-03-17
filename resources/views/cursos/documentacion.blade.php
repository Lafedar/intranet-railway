@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cursos.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap&italic=true" rel="stylesheet">
@endpush

@section('content')


<div>

    <header id="encabezados">
        <h2 id="titulo">{{$course->titulo}}</h2>
        <h3 id="titulo">Fecha: {{$instance->fecha_inicio->format('d/m/Y')}}</h3>
    </header>

    <main>
        <a href="{{ route('cursos.instancias.index', ['cursoId' => $course->id]) }}" class="btn btn-secondary"
            id="asignar-btn">Volver</a>
        <table>
            <thead>
                <tr>
                    <th>Formulario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                    <tr>
                        <td>{{ $doc->formulario_id ?? "No hay anexos" }}</td>
                        <td>
                            @if($doc->formulario_id)
                                <!-- El formulario ahora tiene el formulario_id correspondiente a cada fila -->
                                <form
                                    action="{{ route('verPlanillaPrevia', ['formularioId' => $doc->formulario_id, 'cursoId' => $course->id, 'instanciaId' => $instance->id_instancia]) }}"
                                    method="GET">
                                    @csrf
                                    <button type="submit" style="border: none; background: none; padding: 0;"
                                        title="Ver Documento">
                                        <img src="{{ asset('storage/cursos/ver.png') }}" alt="Ver"
                                            style="width:30px; height:30px;">
                                    </button>

                                </form>
                            @else
                                <!-- Si no hay formulario_id, no se muestra el botón -->
                                <span>No disponible</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
@endsection