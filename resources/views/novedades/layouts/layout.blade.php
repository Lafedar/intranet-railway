<!DOCTYPE html>
<html lang="es">

<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">

<script type="text/javascript" src="{{ URL::asset('/js/modal-jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-popper.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-bootstrap.min.js') }}"></script>

<head>

    <meta charset="UTF-8">

    <title>Intranet Lafedar</title>

    <link rel="icon" href="img/ico.png" type="image/png" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script language="JavaScript" src="{{ URL::asset('/js/jquery.dataTables.min.js') }}"
        type="text/javascript"></script>



    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/"> <img class="logo" src="{{ URL::to('/img/logo.png') }}" height="40"> </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar1">
            <ul class="navbar-nav ml-auto">
                @role('administrador')
                <li class="nav-item">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#crearNovedadModal">
                        Crear Novedad
                    </button>
                </li>
                @endrole

                @role('rrhh')
                <li class="nav-item">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#crearNovedadModal">
                        Crear Novedad
                    </button>
                </li>
                @endrole
                <li class="nav-item">
                    <form action="{{ url('/logout') }}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-separado"
                            style="display:inline; cursor:pointer">
                            Cerrar sesión
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <p></p>
</head>



<script type="text/javascript" src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>

<body>

    @yield('content')
    <!-- Modal para crear una novedad -->
    <div class="modal fade" id="crearNovedadModal" tabindex="-1" role="dialog" aria-labelledby="crearNovedadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearNovedadModalLabel">Crear Novedad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="novedadForm" action="{{ route('novedades.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" maxlength="100" required>
                            <small id="tituloCount" class="form-text text-muted">100 caracteres restantes</small>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion"
                                maxlength="65530"></textarea>
                            <small id="descripcionCount" class="form-text text-muted">65530 caracteres restantes</small>
                        </div>

                        <div class="form-group">
                            <label for="imagen_principal">Seleccionar Imagen Principal</label>
                            <input type="file" class="form-control" id="imagen_principal" name="imagen_principal"
                                accept=".jpg, .jpeg, .png">
                        </div>

                        <div class="form-group">
                            <label for="imagenes">Cargar Imágenes Secundarias (opcional)</label>
                            <input type="file" class="form-control" id="imagenes" name="imagenes[]"
                                accept=".jpg, .jpeg, .png" multiple>
                            <small id="error-message" class="text-danger d-none">Por favor, cargue solo imágenes (.jpg,
                                .jpeg, .png).</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Crear Novedad</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


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

<style>
    .btn-separado {
        margin-left: 15px;
    }
</style>