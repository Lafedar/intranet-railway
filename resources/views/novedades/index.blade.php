@extends('novedades.layouts.layout')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title style="width: 50px">Novedades</title>
    
</head>
<body>

    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <h1 style="text-align:center; font-size: 50px; font-weight: bold">Novedades</h1>

        <div class="row">
        @foreach($novedades as $novedad)
    <div class="col-md-4 mb-4">
        <div class="card">
            @if($novedad->imagen)
                <img src="{{ asset('storage/' . $novedad->imagen) }}" class="card-img-top" alt="Imagen de {{ $novedad->titulo }}">
            @else
                <img src="{{ asset('images/default-image.jpg') }}" class="card-img-top" alt="Sin imagen">
            @endif
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $novedad->titulo }}</h5>
                <p class="card-text">{{ $novedad->descripcion }}</p>
                <a href="#" class="btn btn-primary mt-auto">Leer más</a>
            </div>
        </div>
    </div>
@endforeach
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
@endsection


<style>
    .card {
    height: 100%; /* Asegura que la tarjeta ocupe todo el espacio disponible */
}

.card-img-top {
    height: 200px; /* Altura fija para las imágenes */
    object-fit: cover; /* Asegura que la imagen se recorte y se ajuste al contenedor */
}
</style>