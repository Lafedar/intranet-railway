<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novedades</title>

    <!-- Vinculamos el archivo CSS externo -->
    <link href="{{ asset('css/novedades.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">

    <script type="text/javascript" src="{{ URL::asset('/js/modal-jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('/js/modal-popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('/js/modal-bootstrap.min.js') }}"></script>

</head>

<body>

    <div id="modalContainer"></div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif
        <a href="{{ url('/home') }}" class="img-logo">
            <img src="{{ asset('storage/cursos/logo-cursos.png') }}" loading="lazy" alt="Logo Cursos">
        </a>
        <!-- Modal Crear Novedad -->
        <div class="modal fade" id="crearNovedadModal" tabindex="-1" role="dialog"
            aria-labelledby="crearNovedadModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content novedades-modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearNovedadModalLabel">Crear Novedad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body novedades-modal-body">
                        <form id="novedadForm" action="{{ route('novedades.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group novedades-form-group">
                                <label for="titulo">Título</label>
                                <input type="text" class="form-control novedades-form-control" id="titulo" name="titulo"
                                    maxlength="100" required>
                                <small id="tituloCount" class="form-text text-muted novedades-form-text">100 caracteres
                                    restantes</small>
                            </div>

                            <div class="form-group novedades-form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control novedades-form-control" id="descripcion"
                                    name="descripcion" maxlength="65530"></textarea>
                                <small id="descripcionCount" class="form-text text-muted novedades-form-text">65530
                                    caracteres restantes</small>
                            </div>

                            <div class="form-group novedades-form-group">
                                <label for="imagen_principal">Seleccionar Imagen Principal</label>
                                <input type="file" class="form-control novedades-form-control" id="imagen_principal"
                                    name="imagen_principal" accept=".jpg, .jpeg, .png">
                            </div>

                            <div class="form-group novedades-form-group">
                                <label for="imagenes">Cargar Imágenes Secundarias (opcional)</label>
                                <input type="file" class="form-control novedades-form-control" id="imagenes"
                                    name="imagenes[]" accept=".jpg, .jpeg, .png" multiple>
                                <small id="error-message" class="text-danger d-none novedades-form-text">Por favor,
                                    cargue
                                    solo imágenes (.jpg, .jpeg, .png).</small>
                            </div>

                            <button type="submit" class="btn btn-primary">Crear Novedad</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#crearNovedadModal"
            id="crear-novedad">
            Crear Novedad
        </button>
        <!-- Título de la página -->
        <h1 class="novedades-titulo">Novedades</h1>
        <br>
        <br>

        <div class="row">
            @foreach($novedades as $novedad)
                        <div class="col-md-4 mb-4">
                            <div class="card novedades-card">
                                @php
                                    $imagenes = [];
                                    if ($novedad->portada) {
                                        $imagenes[] = $novedad->portada;
                                    }
                                    if ($novedad->imagenes_sec) {
                                        $imagenes = array_merge($imagenes, explode(',', $novedad->imagenes_sec));
                                    }
                                @endphp

                                @if(count($imagenes) > 0)
                                    <div id="carousel{{ $novedad->id }}" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach($imagenes as $key => $imagen)
                                                <div class="carousel-item novedades-carousel-item {{ $key === 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/' . $imagen) }}" class="d-block"
                                                        alt="Imagen de {{ $novedad->titulo }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        @if(count($imagenes) > 1)
                                            <a class="carousel-control-prev" href="#carousel{{ $novedad->id }}" role="button"
                                                data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carousel{{ $novedad->id }}" role="button"
                                                data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                <div class="card-body novedades-card-body">
                                    <h5 class="card-title novedades-card-title">{{ $novedad->titulo }}</h5>
                                    <h8 class="card-fecha">{{ \Carbon\Carbon::parse($novedad->created_at)->format('d/m/Y') }}</h8>
                                    <br>
                                    <div class="novedades-botones-cards">
                                        <div>
                                            <a href="{{ route('novedades.show', $novedad->id) }}" class="btn">Leer más</a>
                                        </div>

                                        @role('administrador')
                                        <div>
                                            <a href="{{ route('novedades.edit', $novedad->id) }}" class="btn">Editar</a>
                                            <a href="{{ route('novedades.delete', $novedad->id) }}" class="btn"
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta novedad?');">Eliminar</a>
                                        </div>
                                        @role('rrhh')
                                        <div>
                                            <a href="{{ route('novedades.edit', $novedad->id) }}" class="btn">Editar</a>
                                            <a href="{{ route('novedades.delete', $novedad->id) }}" class="btn"
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta novedad?');">Eliminar</a>
                                        </div>
                                        @endrole
                                        @endrole
                                    </div>
                                </div>
                            </div>
                        </div>
            @endforeach
        </div>

    </div>
    <footer id="novedades-footer">
        <p>​LABORATORIOS LAFEDAR S.A | LABORATORIOS FEDERALES ARGENTINOS S.A</p>
    </footer>

    <!-- JavaScript para Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript para mostrar el modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transition = 'opacity 0.5s ease';
                    successMessage.style.opacity = '0';

                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 500);
                }, 3000);
            }
        });
    </script>
</body>

</html>


<script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap.bundle.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tituloInput = document.getElementById('titulo');
        const tituloCount = document.getElementById('tituloCount');
        const descripcionInput = document.getElementById('descripcion');
        const descripcionCount = document.getElementById('descripcionCount');

        function updateCounts() {
            const remainingTitulo = 100 - tituloInput.value.length;
            const remainingDescripcion = 65530 - descripcionInput.value.length;
            tituloCount.textContent = remainingTitulo + ' caracteres restantes';
            descripcionCount.textContent = remainingDescripcion + ' caracteres restantes';
        }

        tituloInput.addEventListener('input', updateCounts);
        descripcionInput.addEventListener('input', updateCounts);

        // Inicializar contadores al cargar la página
        updateCounts();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imagenesInput = document.getElementById('imagenes');
        const portadaInput = document.getElementById('imagen_principal');

        imagenesInput.addEventListener('change', function () {
            const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
            let valid = true;

            for (const file of imagenesInput.files) {
                if (!allowedExtensions.exec(file.name)) {
                    valid = false;
                    break;
                }
            }

            if (!valid) {
                alert('Solo se permiten imágenes en formato JPG, JPEG o PNG.');
                imagenesInput.value = ''; // Limpiar el input
            }
        });

        portadaInput.addEventListener('change', function () {
            const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
            let valid = true;

            for (const file of portadaInput.files) {
                if (!allowedExtensions.exec(file.name)) {
                    valid = false;
                    break;
                }
            }

            if (!valid) {
                alert('Solo se permiten imágenes en formato JPG, JPEG o PNG.');
                portadaInput.value = ''; // Limpiar el input
            }
        });
    });
</script>