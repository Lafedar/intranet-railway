
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ URL::to('/img/ico.png') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Intranet Lafedar</title>
    <link rel="stylesheet" href="{{ asset('css/principal-nueva.css') }}">
</head>
<body class="{{ Auth::check() ? 'authenticated' : '' }}">
<header>
    <div class="logo">
        <img src="{{ asset('storage/Imagenes-principal-nueva/LOGO-LAFEDAR.png') }}" alt="Logo de la empresa">
    </div>
    

  </header>
  
    <div id="results-dropdown" class="results-dropdown">
        <ul id="results-list"></ul>
    </div>
    
    
    <nav>
      
        <a href="/internos" class="nav-btn">Internos <span id="internos-principal">></span></a>
        <a href="{{ route('eventos.index') }}" class="nav-btn" >Calendario <span id="calendario-principal">></span></a>
        <a href="/documentos" class="nav-btn">Documentos<span id="documentos-principal">></span></a>
            
    </nav>

    <section class="container">
        <div id="toast">
            <span id="toast-message"></span>
        </div>  
        <div id="toast">
            <span id="toast-message-success"></span>
        </div>
        <section class="login-section" {{ Auth::check() ? 'style=display:none;' : '' }}>
    <div class="login">
        <h2>INICIO DE SESION</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="icono_usuario">
                <label>
                    <img src="{{ asset('storage/Imagenes-principal-nueva/USUARIO.png') }}" id="img-usuario">
                    <b>Ingresa tu Usuario</b>
                </label>
            </div>
            <div class="input_usuario">
                <input type="email" id="email" name="email" required {{ Auth::check() ? 'disabled' : '' }}>
            </div>
            <div class="icono_contraseña">
                <label>
                    <img src="{{ asset('storage/Imagenes-principal-nueva/LLAVE.png') }}" id="img-contraseña">
                    <b>Ingresa tu Contraseña</b>
                </label>
            </div>
            <div class="input_contraseña">
                <input type="password" id="password" name="password" required {{ Auth::check() ? 'disabled' : '' }}>
                <a href="{{ route('password.request') }}" style="color:blue;">¿Olvidaste tu contraseña?</a>
            </div>
            <div class="btn-iniciar-sesion">
                <button type="submit" style="color:white" {{ Auth::check() ? 'disabled' : '' }}>INGRESAR</button>
            </div>
        </form>
    </div>
</section>

<section class="nav-buttons" {{ Auth::check() ? '' : 'style=display:none;' }}>
    <nav class="nav-grid">
        @role('administrador')
            <!--dejo el css inline para distanciar los > de las palabras-->
            <div class="nav-group">
            <a href="{{ route('powerbis.index') }}" class="nav-btn">Power BI<span id="powerbi-principal">></span></a>

                <a href="{{ route('permisos.index') }}" class="nav-btn">Permisos <span id="permisos-principal">></span></a>
            </div>
            <div class="nav-group">
                <a href="/persona" class="nav-btn">Recepcion <span id="recepcion-principal">></span></a>
                <a href="/sistemas" class="nav-btn">Sistemas <span id="sistemas-principal">></span></a>
            </div>
            <div class="nav-group">
                <a href="/mantenimiento" class="nav-btn">Mantenimiento <span id="mantenimiento-principal">></span></a>
                
            </div>
            <div class="nav-group">
                <a href="/empleado" class="nav-btn">Personal <span id="empleado-principal">></span></a>
                <a href="/medico" class="nav-btn">Medico <span id="medico-principal">></span></a>
                <a href="/visitas" class="nav-btn">Guardia <span id="guardia-principal">></span></a>
                <a href="/cursos" class="nav-btn">Cursos <span id="cursos-principal">></span></a>
            </div>
        @else
            <div class="nav-group">
            <a href="{{ route('powerbis.index') }}" class="nav-btn">Power BI<span id="powerbi-principal">></span></a>

                @role(['jefe', 'rrhh'])
                <a href="{{ route('permisos.index') }}" class="nav-btn">Permisos <span id="permisos-principal2">></span></a>
                @endrole
            </div>
            <div class="nav-group">
                @role(['recepcion', 'rrhh'])
                <a href="/persona" class="nav-btn">Recepcion <span id="recepcion-principal2">></span></a>
                @endrole
                @role('ingenieria')
                <a href="/sistemas" class="nav-btn">Sistemas <span id="sistemas-principal2">></span></a>
                @endrole
            </div>
            <div class="nav-group">
                
            <a href="/mantenimiento" class="nav-btn">Mantenimiento <span id="mantenimiento-principal2">></span></a>
                
                
            </div>
            <div class="nav-group">
                @role('rrhh')
                <a href="/empleado" class="nav-btn">Personal <span id="empleado-principal2">></span></a>
                @endrole
                @role(['medico', 'rrhh'])
                <a href="/medico" class="nav-btn">Medico <span id="medico-principal2">></span></a>
                @endrole
                @role(['guardia', 'rrhh'])
                <a href="/visitas" class="nav-btn">Guardia <span id="guardia-principal2">></span></a>
                @endrole
            </div>
        @endrole
        <div class="btn-cerrar-sesion">
        @if (Auth::check())
            <form action="{{ url('/logout') }}" method="POST">
                {{ csrf_field() }}
                <button type="submit" class="btn-cs">
                    Cerrar sesión
                </button>
            </form>
        @else
            <button class="btn-cs" disabled>Cerrar sesión</button>
        @endif
    </div>
    </nav>
</section>

<!-- Carousel -->
<section class="carousel-container">
    <div id="main-carousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('storage/Imagenes-principal-nueva/portada.jpg') }}" class="d-block w-100" alt="Imagen 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('storage/Imagenes-principal-nueva/portada2.jpg') }}" class="d-block w-100" alt="Imagen 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('storage/Imagenes-principal-nueva/AlbertoFernandezLafedar2.png') }}" class="d-block w-100" alt="Imagen 3">
            </div>
        </div>
        
    </div>
</section>

        <div class="novedades">
        <h1><a href="{{ route('novedades.index') }}" class="titulo-novedades">____________________NOVEDADES____________________</a></h1>

    <div class="cards-contenedor">
    @foreach($novedades as $novedad)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @php
                        $imagenes = [];
                        if ($novedad->portada) {
                            $imagenes[] = $novedad->portada; // Agrega la portada al array
                        }
                        if ($novedad->imagenes_sec) {
                            $imagenes = array_merge($imagenes, explode(',', $novedad->imagenes_sec)); // Agrega imágenes secundarias
                        }
                    @endphp

                    @if(count($imagenes) > 0)
                    <div id="carousel{{ $novedad->id }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($imagenes as $key => $imagen)
                                <div id="carousel-item-chico" class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $imagen) }}" class="d-block w-100" alt="Imagen de {{ $novedad->titulo }}">
                                </div>
                            @endforeach
                        </div>
                        @if(count($imagenes) > 1)
                            <a class="carousel-control-prev" href="#carousel{{ $novedad->id }}" role="button" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carousel{{ $novedad->id }}" role="button" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </a>
                        @endif
                    </div>
                    @endif

                    <div class="card-body">
                            <h5 class="card-title">{{ $novedad->titulo }}</h5>
                            <h8 class="card-fecha">{{ \Carbon\Carbon::parse($novedad->created_at)->format('d/m/Y') }}</h8>
                            <br>
                            <div class="botones-cards">
                                <div >
                                    <a href="{{ route('novedades.show', $novedad->id) }}" class="btn">Leer más</a>
                                </div>
                                

                                @role('administrador')
                                    <div>
                                        <a href="{{ route('novedades.edit', $novedad->id) }}" class="btn">Editar</a>
                                        <a href="{{ route('novedades.delete', $novedad->id) }}" class="btn" onclick="return confirm('¿Estás seguro de que deseas eliminar esta novedad?');">Eliminar</a>
                                    </div>
                                @else
                                    @role('rrhh')
                                        <div >
                                            <a href="{{ route('novedades.edit', $novedad->id) }}" class="btn">Editar</a>
                                            <a href="{{ route('novedades.delete', $novedad->id) }}" class="btn" onclick="return confirm('¿Estás seguro de que deseas eliminar esta novedad?');">Eliminar</a>
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
</section>
  
    <footer>
        <p>Laboratorio Lafedar S.A. | Laboratorios Federales Argentinos S.A </p>
    </footer> 
</body>
</html>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Mensaje de error
    @if ($errors->any())
        document.getElementById('toast-message').innerText = "{{ $errors->first('email') ?: $errors->first('password') ?: __('Las credenciales son incorrectas.') }}";
        document.getElementById('toast').style.display = 'block';
        setTimeout(function() {
            document.getElementById('toast').style.display = 'none';
        }, 3000);
    @endif

    // Mensaje de éxito
    @if (session('success'))
        document.getElementById('toast-message-success').innerText = "{{ session('success') }}";
        document.getElementById('toast-success').style.display = 'block';
        setTimeout(function() {
            document.getElementById('toast-success').style.display = 'none';
        }, 3000);
    @endif
</script>

<script> //filtro general
    const searchInput = document.getElementById('search-input');
    const resultsDropdown = document.getElementById('results-dropdown');
    const resultsList = document.getElementById('results-list');

    const shortcuts = [
        { name: 'Internos', url: '/internos' },
        { name: 'Permisos', url: '/permisos' },
        { name: 'Documentos', url: '/documentos' },
        { name: 'Recepcion', url: '/persona' },
        { name: 'Sistemas', url: '/sistemas' },
        { name: 'QAD', url: '/qad' },
        { name: 'Eventos', url: '/eventos' },
        { name: 'Mantenimiento', url: '/mantenimiento' },
        { name: 'PowerBI', url: '/powerbis' },
        { name: 'Empleado', url: '/empleado' },
        { name: 'Medico', url: '/medico' },
        { name: 'Visitas', url: '/visitas' },
        { name: 'Parametros Mantenimiento', url: '/parametros_mantenimiento' },
        { name: 'Equipos', url: '/equipos_mant' },
        { name: 'Solicitudes', url: '/solicitudes' },
        { name: 'Usuarios', url: '/usuarios' },
        { name: 'Roles', url: '/roles' },
        { name: 'Busca IP', url: '/listado_ip' },
        { name: 'Parametros Sistemas', url: '/parametros_gen_sistemas' },
        { name: 'Puestos de trabajo', url: '/puestos' },
        { name: 'Incidentes', url: '/incidentes' },
        { name: 'Software', url: '/Software' },
        { name: 'Software Instalado', url: '/Instalado' },
        { name: 'Novedades', url: '/novedades' },
    ];

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        resultsList.innerHTML = ''; // Limpiar resultados previos

        if (searchTerm) {
            const filteredShortcuts = shortcuts.filter(shortcut =>
                shortcut.name.toLowerCase().includes(searchTerm)
            );

            if (filteredShortcuts.length > 0) {
                filteredShortcuts.forEach(shortcut => {
                    const li = document.createElement('li');
                    li.textContent = shortcut.name;
                    li.onclick = () => {
                        window.location.href = shortcut.url;
                    };
                    resultsList.appendChild(li);
                });
                resultsDropdown.style.display = 'block'; 
            } else {
                resultsDropdown.style.display = 'none';
            }
        } else {
            resultsDropdown.style.display = 'none'; 
        }
    });

    // Ocultar el dropdown si se hace clic fuera de él
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !resultsDropdown.contains(event.target)) {
            resultsDropdown.style.display = 'none';
        }
    });
</script>


