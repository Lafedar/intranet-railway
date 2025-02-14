@extends('layouts.app')

@push('styles')
    <!-- Link de Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap&italic=true" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cursos.css') }}">

@endpush

@section('content')

    <div id="container-ver-cursos" class="container mt-5">
        <div class="text-center">
            <h1 id="h1-ver-cursos" class="mb-4">Datos de la capacitación</h1>
        </div>

        <form action="{{ route('cursos.update', $curso->id) }}" method="POST" class="bg-light p-4 rounded shadow"
            id="form-group-ver-cursos">
            @csrf
            @method('PUT')


            <div class="form-group">
                <label id="label-ver-cursos" for="titulo"><b>Título:</b></label>
                <p id="p-ver-cursos">{{ $curso->titulo }}</p>
            </div>


            <div class="form-group">
                <label id="label-ver-cursos" for="descripcion"><b>Descripcion:</b></label>
                <p id="p-ver-cursos">{{ !empty($curso->descripcion) ? $curso->descripcion : 'N/A' }}</p>
            </div>


            <div class="form-group">
                <label id="label-obligatorio-ver-cursos"><b>Obligatorio:</b></label>
                <p id="p-ver-cursos">{{ $curso->obligatorio == 1 ? 'SI' : 'NO' }}</p>
            </div>


            <div class="form-group">
                <label id="label-ver-cursos" for="area"><b>Áreas:</b></label>
                <p id="p-area-ver-cursos">
                    @if($areas && $areas->isNotEmpty())
                        @foreach($areas as $area)
                            {{$area->nombre_a}}/
                        @endforeach
                    @else
                        <span id="p-no-data-ver-cursos">N/A</span>
                    @endif
                </p>
            </div>


            <div class="form-group">
                <label id="label-ver-cursos" for="codigo"><b>Código:</b></label>
                <p id="p-codigo-ver-cursos">{{ !empty($curso->codigo) ? $curso->codigo : 'N/A' }}</p>
            </div>


            <div class="form-group">
                <label id="label-ver-cursos"><b>Tipo:</b></label>
                <p id="p-ver-cursos">{{ $curso->tipo }}</p>
            </div>

            <br>


            <a href="{{ route('cursos.index') }}" id="asignar-btn">Volver</a>

        </form>
    </div>

@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {

            $('.select2').select2();
        });
    </script>
@endpush