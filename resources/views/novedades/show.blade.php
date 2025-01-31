@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> 


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $novedad->titulo }}</title>
</head>

<body>
    <div id="novedades-container">
        <div class="container mt-5">
            <h1 class="mb-4">{{ $novedad->titulo }}</h1>

            @if($novedad->portada)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <img src="{{ asset('storage/' . $novedad->portada) }}" class="img-fluid novedad-imagen rounded"
                            alt="Portada de {{ $novedad->titulo }}">
                    </div>
                </div>
            @endif

            <div class="mt-4 text-left">
                <p class="lead" id="novedades-descripcion" style="white-space: pre-wrap;">{{ $novedad->descripcion }}
                </p>
            </div>


            @if($novedad->imagenes_sec)
                        @php
                            $imagenesSecundarias = explode(',', $novedad->imagenes_sec);
                        @endphp

                        <div class="row d-flex flex-wrap">
                            @foreach($imagenesSecundarias as $imagen)
                                @if($imagen !== $novedad->portada) <!-- Asegura que la portada no se incluya -->
                                    <div class="col-md-4 mb-4 d-flex justify-content-center">
                                        <img src="{{ asset('storage/' . $imagen) }}" class="img-fluid novedad-imagen rounded"
                                            alt="Imagen secundaria de {{ $novedad->titulo }}" style="height: 200px; object-fit: cover;">
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
                <a href="{{ route('novedades.index') }}" class="btn btn-primary">Volver</a>
            </div>
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
        background: linear-gradient(90deg, rgba(199, 218, 233, 0.88) 0%, #5098CD 0.01%, #357AAB 46.5%, #3D83B5 85.5%);
    padding: 8px 12px 8px 12px;
    gap: 10px;
    border-radius: 8px;
    opacity: 0px;
    box-shadow: 0px 4px 4.6px 0px rgba(0, 0, 0, 0.25);
    border: none;
    font-family: Calibri, sans-serif;
    font-size: 20px;
    font-weight: 500;
    line-height: 16.8px;
    letter-spacing: -0.03em;
    text-align: center;
    text-underline-position: from-font;
    text-decoration-skip-ink: none;
    color: rgba(245, 245, 245, 1);
    }

    .btn-primary:hover {
        background: var(--HOVER, rgba(33, 102, 153, 1));
    }
.btn-primary:active{
    background: rgba(100, 161, 206, 1);
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