@extends('novedades.layouts.layout')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novedades</title>
</head>
<div id="modalContainer"></div>

<body>

    <div class="container">
        @if(session('success'))
        <div class="alert alert-success" id="success-message">
            {{ session('success') }}
        </div>
        @endif

        <h1 class="titulo">Novedades</h1>
        <br>
        <br>

        <div class="row">
        @foreach($novedades as $novedad)
    <div class="col-md-4 mb-4">
        <div class="card">
            @if($novedad->imagen)
                @php
                    $imagenes = explode(',', $novedad->imagen);
                @endphp
                <div id="carousel{{ $novedad->id }}" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($imagenes as $key => $imagen)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $imagen) }}" class="d-block" alt="Imagen de {{ $novedad->titulo }}">
                            </div>
                        @endforeach
                    </div>
                    @if(count($imagenes) > 1)
                        <a class="carousel-control-prev" href="#carousel{{ $novedad->id }}" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel{{ $novedad->id }}" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    @endif
                </div>
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $novedad->titulo }}</h5>
                <h8 class="card-fecha">{{ \Carbon\Carbon::parse($novedad->created_at)->format('d/m/Y') }}</h8>
                <br>
                <a href="{{ route('novedades.show', $novedad->id) }}" class="btn btn-primary">Leer más</a>

                <a role="button" class="fa-solid fa-pen default mx-2" href="#" title="Editar" 
        data-toggle="modal" 
        data-id="{{ $novedad->id }}" 
        data-titulo="{{ $novedad->titulo }}" 
        data-descripcion="{{ $novedad->descripcion }}" 
        data-target="#editarNovedadModal">Editar
        </a>


                <a href="{{ route('novedades.delete', $novedad->id) }}" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta novedad?');">Eliminar</a>

            </div>
        </div>
    </div>
@endforeach
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                // Espera 3 segundos (3000 ms) y luego oculta el mensaje
                setTimeout(() => {
                    successMessage.style.transition = 'opacity 0.5s ease';
                    successMessage.style.opacity = '0';
                    
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 500);
                }, 3000); // Espera 3 segundos
            }
        });
    </script>
    

</body>
</html>

@endsection

<script>
    $(document).ready(function() {
        $('a[data-toggle="modal"]').on('click', function() {
            const id = $(this).data('id');
            const titulo = $(this).data('titulo');
            const descripcion = $(this).data('descripcion');

            // Asignar valores a los campos del modal
            $('#titulo').val(titulo);
            $('#descripcion').val(descripcion);
            $('#editarNovedadForm').attr('action', `{{ route('novedades.update', '') }}/${id}`);

            // Mostrar el modal
            $('#editarNovedadModal').modal('show');
        });
    });
</script>
<script>
    function openEditModal(novedad) {
        // Asignar los valores a los campos del formulario
        document.getElementById('titulo').value = novedad.titulo;
        document.getElementById('descripcion').value = novedad.descripcion;

        // Actualizar la acción del formulario con el ID de la novedad
        document.getElementById('editarNovedadForm').action = `{{ route('novedades.update', '') }}/${novedad.id}`;

        // Mostrar el modal
        $('#editarNovedadModal').modal('show');
    }
</script>



<style>
.card {
    width: 18rem; 
    height: 350px; 
    overflow: hidden; 
    border-radius: 40px; 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
    position: relative; 
}

.carousel-item img {
    width: 100%; 
    height: 185px; 
    object-fit: cover; 
    
}

.card-body {
    position: absolute; 
    bottom: 0; 
    width: 100%; 
    background: rgba(255, 255, 255, 0.8);
    padding: 10px; 
    box-sizing: border-box; 
}

.card-text {
    max-height: 60px; 
    overflow: hidden; 
    text-overflow: ellipsis; 
    white-space: nowrap; 
}

.titulo {
    margin:30px;
    text-align:center; 
    font-size: 80px; 
    font-weight:bold;
    color: #004a99;
}


</style>