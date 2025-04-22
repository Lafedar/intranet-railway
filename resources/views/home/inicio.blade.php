@extends('layouts.home')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ URL::to('/img/ico.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Intranet Lafedar</title>
    <link rel="stylesheet" href="{{ asset('css/principal-nueva.css') }}">
</head>
<body class="{{ Auth::check() ? 'authenticated' : '' }}">
<header class="page-header">
    <div class="logo">
        <img src="{{ asset('storage/Imagenes-principal-nueva/Logo-sinfondo.png') }}" alt="Logo de la empresa">
    </div>
    
</header>


    <div id="results-dropdown" class="results-dropdown">
        <ul id="results-list"></ul>
    </div>
    <nav>
        @if( !Auth::check())
            <a href="{{ route('eventos.index') }}" class="nav-btn" >Calendario <span id="calendario-principal">></span></a>
            <a href="/documentos" class="nav-btn">Documentos<span id="documentos-principal">></span></a>
            <a href="/internos" class="nav-btn">Internos <span id="internos-principal">></span></a>
            <a href="{{ route('novedades.index') }}" class="nav-btn">Novedades<span id="novedades-principal">></span></a>
        @else
            <a href="{{ route('eventos.index') }}" class="nav-btn" >Calendario <span id="calendario-principal">></span></a>
            <a href="/capacitaciones" class="nav-btn">Capacitaciones <span id="capacitacion-principal">></span></a>
            <a href="/documentos" class="nav-btn">Documentos<span id="documentos-principal">></span></a>
            @role(['guardia', 'rrhh', 'administrador'])
                <a href="/visitas" class="nav-btn">Guardia <span id="guardia-principal2">></span></a>
            @else 
                <a href="/internos" class="nav-btn">Internos <span id="internos-principal">></span></a>
            @endrole   
        @endif
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
            <div class="nav-group">
                <a href="/internos" class="nav-btn">Internos <span id="internos-principal">></span></a>
                <a href="/mantenimiento" class="nav-btn">Mantenimiento <span id="mantenimiento-principal">></span></a>
                <a href="/medications" class="nav-btn">Medicamentos<span id="medicamentos-principal">></span></a>
                <a href="/medico" class="nav-btn">Medico <span id="medico-principal">></span></a>
                <a href="{{ route('novedades.index') }}" class="nav-btn">Novedades<span id="novedades-principal">></span></a>
                <a href="{{ route('permisos.index') }}" class="nav-btn">Permisos <span id="permisos-principal">></span></a>
                <a href="/empleado" class="nav-btn">Personal <span id="empleado-principal">></span></a>
              
                <a href="/persona" class="nav-btn">Recepcion <span id="recepcion-principal">></span></a>
                <a href="/sistemas" class="nav-btn">Sistemas <span id="sistemas-principal">></span></a>
            </div>

        @else
            <div class="nav-group">
                @role(['guardia', 'rrhh', 'administrador'])
                    <a href="/internos" class="nav-btn">Internos <span id="internos-principal">></span></a>
                @endrole
                <a href="/mantenimiento" class="nav-btn">Mantenimiento <span id="mantenimiento-principal2">></span></a>
                @role(['medico', 'rrhh'])
                <a href="/medico" class="nav-btn">Medico <span id="medico-principal2">></span></a>
                @endrole
                <a href="{{ route('novedades.index') }}" class="nav-btn">Novedades<span id="novedades-principal">></span></a>
                @role(['jefe', 'rrhh'])
                <a href="{{ route('permisos.index') }}" class="nav-btn">Permisos <span id="permisos-principal2">></span></a>
                @endrole
                @role('rrhh')
                <a href="/empleado" class="nav-btn">Personal <span id="empleado-principal2">></span></a>
                @endrole
                @role(['recepcion', 'rrhh'])
                <a href="/persona" class="nav-btn">Recepcion <span id="recepcion-principal2">></span></a>
                @endrole
                @role('ingenieria')
                <a href="/sistemas" class="nav-btn">Sistemas <span id="sistemas-principal2">></span></a>
                @endrole
            </div>
            
        @endrole
       
    </nav>
</section>
<!-- Carousel -->
<section class="carousel-container">
    <div id="main-carousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('storage/Imagenes-principal-nueva/carousel1.jpg') }}" class="d-block w-100" alt="Imagen 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('storage/Imagenes-principal-nueva/5.png') }}" class="d-block w-100" alt="Imagen 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('storage/Imagenes-principal-nueva/AlbertoFernandezLafedar2.png') }}" class="d-block w-100" alt="Imagen 3">
            </div>
        </div>
        
    </div>
</section>
        <div class="novedades">
        <h1><a href="{{ route('novedades.index') }}" class="titulo-novedades">NOVEDADES____________________________________________________</a></h1>
    <div class="cards-contenedor">
    @foreach($novedades as $novedad)
    <div class="col-md-4 mb-4">
        <div class="card" >
            @php
            $imagenes = [];
            if ($novedad->portada) {
            $imagenes[] = $novedad->portada;
            }
            if ($novedad->imagenes_sec) {
            $imagenes = array_merge($imagenes, explode(',', $novedad->imagenes_sec));
            }

            // Verificar si existe la cookie para esta novedad
            $cookieName = 'like_novedad_' . $novedad->id;
            $userLike = Cookie::get($cookieName) ? true : false; // Si la cookie existe, el usuario ha dado like
            @endphp

            @if(count($imagenes) > 0)
            <div id="carousel{{ $novedad->id }}" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner" >
            @foreach($imagenes as $key => $imagen)
            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}" >
                <img src="{{ asset('storage/' . $imagen) }}" class="d-block w-100 img-thumbnail" id="img-carousel" alt="Imagen de {{ $novedad->titulo }}">
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

            <div class="card-body" >
            <h5 class="card-title">{{ $novedad->titulo }}</h5>

            
            <div class="d-flex justify-content-between align-items-center mb-2">
                <!-- Fecha -->
                <h8 class="card-fecha mb-0">{{ \Carbon\Carbon::parse($novedad->created_at)->format('d/m/Y') }}</h8>

                <!-- Botón de Like y la cantidad de Likes -->
                <div class="d-flex align-items-center">
                <form action="{{ $userLike ? route('novedades.unlike', $novedad->id) : route('novedades.like', $novedad->id) }}" method="POST">
                    @csrf
                    @if ($userLike)
                    <button type="submit" style="border:none;" id="likes">
                    <p>❤️</p>
                    </button>
                    @else
                    <button type="submit" style="border:none;" id="likes2">
                    <p>🤍</p>
                    </button>
                    @endif
                </form>

                <!-- Mostrar la cantidad de likes -->
                <span class="ms-2" id="cont-likes">{{ $novedad->likes_count }} Likes</span>
                </div>
            </div>

            <br>

            <div class="botones-cards">
                <div>
                <a href="{{ route('novedades.show', $novedad->id) }}" class="btn">LEER</a>
                </div>

                @role('administrador')
                <div>
                    <a href="{{ route('novedades.edit', $novedad->id) }}" class="btn">EDITAR</a>
                    <a href="{{ route('novedades.delete', $novedad->id) }}" class="btn" onclick="return confirm('¿Estás seguro de que deseas eliminar esta novedad?');">ELIMINAR</a>
                </div>
                @else
                @role('rrhh')
                    <div>
                    <a href="{{ route('novedades.edit', $novedad->id) }}" class="btn">EDITAR</a>
                    <a href="{{ route('novedades.delete', $novedad->id) }}" class="btn" onclick="return confirm('¿Estás seguro de que deseas eliminar esta novedad?');">ELIMINAR</a>
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
  
   
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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