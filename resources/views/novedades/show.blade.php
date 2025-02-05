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

            <!-- Carousel de imágenes -->
            <div id="novedadesCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    @if($novedad->portada)
                        <div class="carousel-item active">
                            <img src="{{ asset('storage/' . $novedad->portada) }}"
                                class="d-block w-100 novedad-imagen rounded" alt="Portada de {{ $novedad->titulo }}"
                                data-toggle="modal" data-target="#imageModal"
                                data-img="{{ asset('storage/' . $novedad->portada) }}">
                        </div>
                    @endif

                    @if($novedad->imagenes_sec)
                                        @php
                                            $imagenesSecundarias = explode(',', $novedad->imagenes_sec);
                                        @endphp

                                        @foreach($imagenesSecundarias as $imagen)
                                            @if($imagen !== $novedad->portada) <!-- Asegura que la portada no se incluya -->
                                                <div class="carousel-item">
                                                    <img src="{{ asset('storage/' . $imagen) }}" class="d-block w-100 novedad-imagen rounded"
                                                        alt="Imagen secundaria de {{ $novedad->titulo }}" data-toggle="modal"
                                                        data-target="#imageModal" data-img="{{ asset('storage/' . $imagen) }}">
                                                </div>
                                            @endif
                                        @endforeach
                    @endif
                </div>
                <a class="carousel-control-prev" href="#novedadesCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#novedadesCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

            <!-- Texto de la novedad -->
            <div class="mt-4 text-left">
                <p class="lead" id="novedades-descripcion" style="white-space: pre-wrap;">{{ $novedad->descripcion }}
                </p>
            </div>

            <!-- Fecha de la novedad -->
            <div class="mt-4">
                <ul class="list-unstyled">
                    <li><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($novedad->created_at)->format('d/m/Y') }}</li>
                </ul>
            </div>

            <!-- Botón para volver -->
            <div class="mt-4">
                <a href="{{ route('novedades.index') }}" class="btn btn-primary">Volver</a>
            </div>
        </div>
    </div>
    <!-- Modal -->
   <!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Controles para las imágenes -->
                <button id="prevImage" class="btn btn-secondary" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                
                <img id="modalImage" src="" alt="Imagen ampliada" class="img-fluid" />
                
                <button id="nextImage" class="btn btn-secondary" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
</div>


</body>

<!-- Scripts de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    var currentIndex = 0;
    var imageUrls = []; // Array para almacenar las URLs de las imágenes del carrusel

    // Función que inicializa las imágenes en el carrusel
    function initializeCarouselImages() {
        $('#novedadesCarousel img').each(function(index) {
            imageUrls.push($(this).attr('src')); // Guardamos las URLs de las imágenes
        });
    }

    // Al hacer clic en una imagen del carrusel
    $('#novedadesCarousel img').on('click', function () {
        currentIndex = imageUrls.indexOf($(this).attr('src')); // Establecer el índice de la imagen clickeada
        showImageInModal(currentIndex); // Mostrar la imagen en la modal
    });

    // Mostrar la imagen en la modal
    function showImageInModal(index) {
        $('#modalImage').attr('src', imageUrls[index]); // Actualizar la imagen de la modal
        $('#imageModal').modal('show'); // Mostrar la modal
    }

    // Pasar a la imagen anterior
    $('#prevImage').on('click', function () {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = imageUrls.length - 1; // Ir a la última imagen si estamos en la primera
        }
        showImageInModal(currentIndex);
    });

    // Pasar a la siguiente imagen
    $('#nextImage').on('click', function () {
        if (currentIndex < imageUrls.length - 1) {
            currentIndex++;
        } else {
            currentIndex = 0; // Volver a la primera imagen si estamos en la última
        }
        showImageInModal(currentIndex);
    });

    // Cuando la modal se cierra, resetear la imagen
    $('#imageModal').on('hidden.bs.modal', function () {
        $('#modalImage').attr('src', ''); 
    });

    // Inicializar el carrusel al cargar la página
    $(document).ready(function () {
        initializeCarouselImages();
    });
</script>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        color: #333;
    }

    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.7) !important;
        /* Control de opacidad del fondo */
        transition: opacity 0.3s ease;
        /* Añadir una transición suave al fondo */
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
        opacity: 0.9;
        box-shadow: 0px 4px 4.6px 0px rgba(0, 0, 0, 0.25);
        border: none;
        font-family: Calibri, sans-serif;
        font-size: 20px;
        font-weight: 500;
        line-height: 16.8px;
        letter-spacing: -0.03em;
        text-align: center;
        color: rgba(245, 245, 245, 1);
    }

    .btn-primary:hover {
        background: var(--HOVER, rgba(33, 102, 153, 1));
    }

    .btn-primary:active {
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
    #novedadesCarousel {
        height: 500px; 
        overflow: hidden;  
    }

    .modal-dialog {
        max-width: 70%;
        margin: auto;
        
    }

    /* Estilo para la imagen dentro de la modal */
    .modal-body img {
        width: 100%;
        height: auto;
        max-height: 90vh;
        object-fit: contain;
    }
</style>