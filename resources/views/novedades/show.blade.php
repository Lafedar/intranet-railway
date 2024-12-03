@extends('mantenimiento.layouts.layout')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $novedad->titulo }}</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">{{ $novedad->titulo }}</h1>

        @if($novedad->portada)
            <div class="row mb-4">
                <div class="col-md-12">
                    <img src="{{ asset('storage/' . $novedad->portada) }}" class="img-fluid novedad-imagen rounded" alt="Portada de {{ $novedad->titulo }}">
                </div>
            </div>
        @endif

        <div class="mt-4 text-left">
    <p class="lead" id="novedades-descripcion">{{ $novedad->descripcion }}</p>
</div>


        @if($novedad->imagenes_sec)
    @php
        $imagenesSecundarias = explode(',', $novedad->imagenes_sec);
    @endphp

    <div class="row d-flex flex-wrap">
        @foreach($imagenesSecundarias as $imagen)
            @if($imagen !== $novedad->portada) <!-- Asegura que la portada no se incluya -->
                <div class="col-md-4 mb-4 d-flex justify-content-center">
                    <img src="{{ asset('storage/' . $imagen) }}" class="img-fluid novedad-imagen rounded" alt="Imagen secundaria de {{ $novedad->titulo }}" style="height: 200px; object-fit: cover;">
                </div>
            @endif
        @endforeach
    </div>
@endif

        <div class="mt-4">
            <ul class="list-unstyled">
                <li><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($novedad->created_at)->format('d/m/Y') }}</li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="{{ route('novedades.index') }}" class="btn btn-primary">Volver a Novedades</a>
        </div>
    </div>

</body>
</html>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        color: #333;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    }

    .novedad-imagen {
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    h1 {
        font-size: 2.5rem;
        color: #2c3e50;
        font-weight: bold;
    }

    .lead {
        font-size: 1.1rem;
        color: #2c3e50;  
        word-wrap: break-word;
        white-space: normal;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .list-unstyled {
        list-style: none;
        padding-left: 0;
    }

    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
        padding: 10px 20px;
        font-size: 1rem;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .img-fluid {
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .mt-4 {
        margin-top: 30px;
    }

    .text-center {
        text-align: center;
    }

    .carousel-item img {
        width: 100%;
        height: auto; 
        object-fit: cover;
    }
</style>

@endsection