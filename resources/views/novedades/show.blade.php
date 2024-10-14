@extends('mantenimiento.layouts.layout')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novedad</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">{{ $novedad->titulo }}</h1>

        @if($novedad->imagen)
            @php
                $imagenes = explode(',', $novedad->imagen);  // Si las imágenes están en un string separado por comas
            @endphp

           
            <div class="row">
                <div class="col-md-12 mb-4">
                    <img src="{{ asset('storage/' . $imagenes[0]) }}" class="img-fluid novedad-imagen" alt="Imagen principal de {{ $novedad->titulo }}">
                </div>
            </div>

            
            <div class="mt-4 text-center">
                <p>{{ $novedad->descripcion }}</p>
            </div>

           
            <div class="row">
                @foreach(array_slice($imagenes, 1) as $imagen)
                    <div class="col-md-4 mb-4">
                        <img src="{{ asset('storage/' . $imagen) }}" class="img-fluid novedad-imagen" alt="Imagen de {{ $novedad->titulo }}">
                    </div>
                @endforeach
            </div>
        @endif
        
        <div class="mt-4">
            <ul class="no-bullets">
                <li><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($novedad->fecha)->format('d/m/Y') }}</li>
            </ul>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('novedades.index') }}" class="btn btn-secondary">Volver a Novedades</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<style>
    body {
        text-align: center;
    }

    
    .container {
        text-align: center;
    }

    .no-bullets {
        list-style-type: none;
        padding-left: 0;  
    }

   
    .mt-4.text-center {
        max-width: 100%;
        margin: 0 auto;
        word-wrap: break-word;
    }

   
    .novedad-imagen {
        width: 100%; 
        height: auto; 
        object-fit: cover; 
    }

</style>

@endsection
