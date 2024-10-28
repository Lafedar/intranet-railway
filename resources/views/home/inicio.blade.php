
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
   
</head>
<body class="{{ Auth::check() ? 'authenticated' : '' }}">
<header>
    <div class="logo">
        <img src="{{ asset('storage/Imagenes principal-nueva/LOGO-LAFEDAR.png') }}" alt="Logo de la empresa">
    </div>
    
    <!--<input type="text" class="search-bar" placeholder="🔎 Buscar por palabra clave" id="search-input" {{ Auth::check() ? '' : 'disabled' }}>-->
    <!--<div class="btn-cerrar-sesion">
        @if (Auth::check())
            <form action="{{ url('/logout') }}" method="POST">
                {{ csrf_field() }}
                <button type="submit" class="btn-cs" style="display:inline;cursor:pointer">
                    Cerrar sesión
                </button>
            </form>
        @else
            <button class="btn-cs" disabled>Cerrar sesión</button>
        @endif
    </div>-->
  </header>
  
    <div id="results-dropdown" class="results-dropdown" style="display: none;">
        <ul id="results-list"></ul>
    </div>
    
    
    <nav>
      
        <a href="/internos" class="nav-btn" style="text-decoration: none;">Internos <span style="margin-left:130px;">></span></a>
        <a href="{{ route('eventos.index') }}" class="nav-btn" style="text-decoration: none;">Calendario <span style="margin-left:107px;">></span></a>
        <a href="/documentos" class="nav-btn" style="text-decoration: none;">Documentos<span style="margin-left:98px;">></span></a>
            
    </nav>

    <section class="container">
        <div id="toast" style="display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background-color: #f44336; color: white; padding: 16px; border-radius: 5px; z-index: 1000;">
            <span id="toast-message"></span>
        </div>  
        <div id="toast-success" style="display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background-color: #4CAF50; color: white; padding: 16px; border-radius: 5px; z-index: 1000;">
            <span id="toast-message-success"></span>
        </div>
        <section class="login-section" {{ Auth::check() ? 'style=display:none;' : '' }}>
    <div class="login">
        <h2>INICIO DE SESION</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="icono_usuario">
                <label>
                    <img src="{{ asset('storage/Imagenes principal-nueva/USUARIO.png') }}" style="width: 30px; height: auto; margin-right: 5px;">
                    <b>Ingresa tu Usuario</b>
                </label>
            </div>
            <div class="input_usuario">
                <input type="email" id="email" name="email" required {{ Auth::check() ? 'disabled' : '' }}>
            </div>
            <div class="icono_contraseña">
                <label>
                    <img src="{{ asset('storage/Imagenes principal-nueva/LLAVE.png') }}" style="width: 25px; height: auto; margin-right: 5px;">
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
                <a href="{{ route('solicitudes.index') }}" class="nav-btn">Solicitudes <span style="margin-left:107px;">></span></a>
                <a href="{{ route('permisos.index') }}" class="nav-btn">Permisos <span style="margin-left:120px;">></span></a>
            </div>
            <div class="nav-group">
                <a href="/persona" class="nav-btn">Recepcion <span style="margin-left:109px;">></span></a>
                <a href="/sistemas" class="nav-btn">Sistemas <span style="margin-left:123px;">></span></a>
            </div>
            <div class="nav-group">
                <a href="/mantenimiento" class="nav-btn">Mantenimiento <span style="margin-left:75px;">></span></a>
                <a href="/powerbis" class="nav-btn">Power BI <span style="margin-left:123px;">></span></a>
            </div>
            <div class="nav-group">
                <a href="/empleado" class="nav-btn">Personal <span style="margin-left:125px;">></span></a>
                <a href="/medico" class="nav-btn">Medico <span style="margin-left:135px;">></span></a>
                <a href="/visitas" class="nav-btn">Guardia <span style="margin-left:131px;">></span></a>
            </div>
        @else
            <div class="nav-group">
                <a href="{{ route('solicitudes.index') }}" class="nav-btn">Solicitudes <span style="margin-left:107px;">></span></a>
                @role(['jefe', 'rrhh'])
                <a href="{{ route('permisos.index') }}" class="nav-btn">Permisos <span style="margin-left:120px;">></span></a>
                @endrole
            </div>
            <div class="nav-group">
                @role(['recepcion', 'rrhh'])
                <a href="/persona" class="nav-btn">Recepcion <span style="margin-left:107px;">></span></a>
                @endrole
                @role('ingenieria')
                <a href="/sistemas" class="nav-btn">Sistemas <span style="margin-left:123px;">></span></a>
                @endrole
            </div>
            <div class="nav-group">
                
            <a href="/mantenimiento" class="nav-btn">Mantenimiento <span style="margin-left:75px;">></span></a>
                
                
            </div>
            <div class="nav-group">
                @role('rrhh')
                <a href="/empleado" class="nav-btn">Personal <span style="margin-left:125px;">></span></a>
                @endrole
                @role(['medico', 'rrhh'])
                <a href="/medico" class="nav-btn">Medico <span style="margin-left:135px;">></span></a>
                @endrole
                @role(['guardia', 'rrhh'])
                <a href="/visitas" class="nav-btn">Guardia <span style="margin-left:131px;">></span></a>
                @endrole
            </div>
        @endrole
        <div class="btn-cerrar-sesion">
        @if (Auth::check())
            <form action="{{ url('/logout') }}" method="POST">
                {{ csrf_field() }}
                <button type="submit" class="btn-cs" style="display:inline;cursor:pointer">
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
                <img src="{{ asset('storage/novedades/portada.jpg') }}" class="d-block w-100" alt="Imagen 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('storage/novedades/portada4.jpg') }}" class="d-block w-100" alt="Imagen 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('storage/novedades/AlbertoFernandezLafedar2.png') }}" class="d-block w-100" alt="Imagen 3">
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
                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
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
  
    <footer >
        <p>Laboratorio Lafedar S.A. | Laboratorios Federales Argentinos S.A    </p>
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



<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.btn-cs{
    height:45px;
    width:120px;
    margin-top:1px;
    border-radius:10px;
    background: linear-gradient(90deg, #FF5733 0.66%, #C70039 109.41%);
    color:white;
    border:none;
}

.btn-cs:hover{
    box-shadow: 0 20px 20px rgba(1, 1, 1, 0.6);
    color: black;
}
.titulo-novedades{
    text-decoration: none; 
    color: inherit;
}
body {
    font-family: Arial, sans-serif;
    background-color: white;
    /*overflow: hidden; /* Saco el scroll */
}

header {
    display: flex; 
    align-items: center; 
    padding: 20px; 
    background-color: white; 
}
.btn-cerrar-sesion {
    margin-left: 120px;
    
}

/* CONTAINER LOGIN Y NOVEDADES */
.container {
    display: flex; 
    justify-content: space-between; 
    align-items: flex-start; 
    margin: 40px 20px; 
    margin-top:75px;
}

/*MENSAJE DE ERROR LOGIN*/
#toast {
    transition: opacity 0.5s ease;
    text-align: center; 
    width: auto; 
    max-width: 90%; 
}

/* BARRA DE BUSQUEDA */
.search-bar {
    background-color: #1E78C8;
    margin: 0px 20px; 
    margin-left: 110px;
    padding: 10px; 
    border: none; 
    border-radius: 10px;
    font-family: 'Inter', sans-serif; 
    font-size: 30px; 
    flex: 1; 
    color: white; 
    height: 60px;
}

.search-bar::placeholder {
    color: white;
}

header .logo img {
    max-width: 400px;
}


/* NAV CON BOTONES */
nav {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin: 20px 0;
}

.nav-buttons {
    margin-top: -95px; 
    margin-left: -30px;
    
}

.nav-buttons a{
    text-decoration:none;
}
.nav-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 1px;
    justify-content: flex-start;
}

.nav-group {
    display: flex;
    flex-direction: column;
    gap: 1px;
}

.nav-btn {
    background: linear-gradient(90deg, #1C547C 0.66%, #3399E2 109.41%);
    color: white;
    border-radius: 10px;
    border: none;
    padding: 8px 16px;
    margin: 10px 0;
    cursor: pointer;
    width: 250px;
    height: 45px;
    box-shadow: 0 20px 20px rgba(1, 1, 1, 0.6);
    font-family: Inter;
    font-size: 18px;
    font-weight: 700;
    line-height: 22px;
    text-align: left;
    margin-left:54px;
}

.nav-btn:hover {
    background-color: white;
    color: #004a99;
}


/*LUEGO DE LOGUEARSE*/
.authenticated .nav-btn {
    margin-top: -5px; 
}
.authenticated .carousel-item img {
    height:180px;  
    object-fit: cover;  
     
}

.authenticated .novedades{
    margin-top: -45px;
}
.authenticated .card {
    width: 300px; 
    height: 345px; 
    border: 1px solid #ddd;   
    border-radius: 10px;      
    overflow: hidden;         
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.authenticated #main-carousel{
    margin-top:43px;
}
.authenticated #main-carousel img{
    height:320px;
}

/* LOGIN */   
.login {
    background-color: #E0E0E0BF;
    padding: 20px;
    max-width: 300px; 
    margin-right: 20px; 
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    height: 330px;
    width: 300px;
    margin-top:65px;
}

.login h2 {
    text-align: center;  
    margin-bottom: 20px; 
    font-size: 25px;  
    color: #1C547C;
    font-family: Arial; 
    font-weight: 900;
    line-height: 22.99px;
    text-align: center;


}

.login label {
    display: block;
    margin-bottom: 5px;
    color: rgba(28, 84, 124, 1);
    font-family: Inter;
font-size: 16px;
font-weight: 500;
line-height: 19.36px;
text-align: center;

}

.login input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.login button {
    width: 100%;
    padding: 10px;
    background: rgba(30, 120, 200, 1);
    color: white;
    border: none;
    cursor: pointer;
    margin-top:5px;
    width: 110px;

    height: 35px;
    top: 642px;
    left: 98px;
    gap: 0px;
    border-radius: 10px 10px 10px 10px;
    opacity: 0px;

    font-family: Inter;
    font-size: 16px;
    font-weight: 800;
    line-height: 19.36px;
    letter-spacing: 0.01em;
    text-align: center;

}

.login button:hover {
    background-color: #003a7a;
}


/*CAROUSEL*/
.carousel-container {
    position: absolute;  
    margin-top:-380px; 
    margin-left:382px;
    width: 77%; 
    max-height: 340px;
    height: 800px; 
    z-index: 2;
    overflow: hidden;  
    border-radius: 10px;
    
}


.carousel-item img {
    height: 340px;  
    object-fit: cover;  
    border-radius: 10px; 
}



.carousel-control-prev, 
.carousel-control-next {
    height: 30px; 
    top: 50%; 
    transform: translateY(-50%); 
}

/* NOVEDADES */
.novedades {
    display: flex;
    flex-direction: column; 
    align-items: center;      
    justify-content: flex-start;
    margin-top: -65px; 
    width: 100%; 
    max-width: 800px; 
}

.novedades h1 {
    margin-top:53px;/*53*/
    margin-bottom: 10px; 
    color: #196AB2;
    font-weight: 900;
    font-size: 45px;
    text-align: center; 
    width: 100%; 
    font-family: Arial;
    

}

.cards-contenedor {
    display: flex;              
    justify-content: space-between;  
    flex-wrap: nowrap;         
    gap: 100px; /* espacio entre las tarjetas */               
    margin-top: 10px;   
    margin-left: -150px;  
    width:110%;
}

.card {
    width: 300px; 
    height: 335px; 
    border: 1px solid #ddd;   
    border-radius: 10px;      
    overflow: hidden;         
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
   
}
.card-title {
    display: -webkit-box;             
    -webkit-box-orient: vertical;    
    -webkit-line-clamp: 2;  /* Número de líneas que se mostrarán */
    overflow: hidden;                 
    text-overflow: ellipsis;          
    max-width: 100%;                 
}

.card img {
    width:100%;
    max-width: 300px;           
    height: 170px; 
    border-radius: 10px 10px 0px 0px;            
}

/*BOTONES DE LAS CARD*/
.botones-cards {
    display: flex;
    gap: 5px; 
    
    
}

.botones-cards a {
    background: linear-gradient(90deg, #1C547C 0.66%, #3399E2 109.41%);
    color: white;
    padding: 10px;
    text-decoration: none; 
    height: 40px;
    
}

/* FOOTER */
footer {
    background: linear-gradient(90deg, #1C547C 0%, #1E78C8 100%);
    color: white; 
    text-align: center; 
    padding: 20px; 
    position: fixed; 
    width: 100%; 
    bottom: 0; 
    font-family: 'Inter', sans-serif; 
    font-weight: 200; 
}

footer p {
    margin: 0; 
    padding: 0;
}


/*DESPLEGABLE BARRA DE BUSQUEDA*/
.results-dropdown {
    position: absolute; 
    background: white; 
    border: 1px solid #ccc; 
    z-index: 1000; 
    width: 100%; 
    max-width: calc(100% - 2px); 
}

.results-dropdown ul {
    list-style-type: none; 
    padding: 0; 
    margin: 0; 
}

.results-dropdown li {
    padding: 10px; 
    cursor: pointer; 
}

.results-dropdown li:hover {
    background-color: #f0f0f0; 
}





/*RESPONSIVE*/
/*Pantallas 1366x768 */
@media (max-width: 1366px) {
    .container {
        display: flex;
        flex-direction: column; 
        align-items: flex-start; /* Alinea a la izquierda */
        margin: 40px 20px; 
    }

    .search-bar {
        width: 90%; 
        font-size: 18px; 
        margin: 10px 50px; 
    }

    nav {
        position: absolute; 
        left: -7px; 
        top: 100px; 
        display: flex; /*Flex para apilar botones */
        flex-direction: column; 
        align-items: flex-start; 
        margin-bottom: 20px; 
    }

    .nav-btn span {
    display: none; /* Oculta el contenido del span */
}

    .nav-btn {
        width: 250px; 
        margin-bottom: 10px; 
    }

    .login {
        position: absolute; 
        left: 20px; 
        top: 400px; 
    }

    .novedades {
        width: 100%; 
        text-align: center; 
        margin: 200px 350px 100px;
        display: flex; 
        flex-direction: column; 
        align-items: center; 
    }

    .novedades h1 {
        font-size: 28px; 
    }

    .cards-contenedor {
        display: flex; 
        flex-wrap: wrap;
        justify-content: center; 
        gap: 30px; 
        max-width: 1000px; 
        margin: 0 auto; 
        margin-bottom: 40px;
        
    }
    
    .card {
        width: calc(45% - 20px); 
        max-width: 300px; 
        margin: 10px; 
        width:280px;
        height: 400px;
    }
    .authenticated .cards-contenedor {
        gap: 50px; 
   
}

    .carousel-container {
    position: absolute;  
    top: 100px; 
    margin-left:320px;
    width: 62.5%; 
    max-height: 300px; 
    height: 800px; 
    z-index: 2;
    overflow: hidden;  
    border-radius: 10px;
}


.carousel-item img {
    height: 270px;  
    object-fit: cover;  
    border-radius: 10px; 
}


.carousel-control-prev, 
.carousel-control-next {
    height: 30px; 
    top: 50%; 
    transform: translateY(-50%); 
}

    .footer{
        position: relative;
    }
}

/*Pantallas 1360x768 */
@media (max-width: 1360px) {
    .container {
        display: flex;
        flex-direction: column; 
        align-items: flex-start; 
        margin: 40px 20px; 
    }

    .search-bar {
        width: 90%; 
        font-size: 18px; 
        margin: 10px 50px; 
    }

    nav {
        position: absolute; 
        left: -7px; 
        top: 100px; 
        display: flex; 
        flex-direction: column; 
        align-items: flex-start; 
        margin-bottom: 20px; 
    }

    .nav-btn span {
    display: none; 
}

    .nav-btn {
        width: 250px; 
        margin-bottom: 10px; 
    }

    .login {
        position: absolute; 
        left: 20px; 
        top: 400px; 
    }

    .novedades {
        width: 100%; 
        text-align: center; 
        margin: 200px 350px 100px;
        display: flex; 
        flex-direction: column; 
        align-items: center; 
    }

    .novedades h1 {
        font-size: 28px; 
    }

    .cards-contenedor {
        display: flex; 
        flex-wrap: wrap;
        justify-content: center; 
        gap: 30px; 
        max-width: 1000px; 
        margin: 0 auto; 
        margin-bottom: 40px;
        
    }
    
    .card {
        width: calc(45% - 20px); 
        max-width: 300px; 
        margin: 10px; 
        width:280px;
        height: 400px;
    }
    .authenticated .cards-contenedor {
        gap: 50px; 
   
}

    .carousel-container {
    position: absolute;  
    top: 100px; 
    margin-left:320px;
    width: 62.5%; 
    max-height: 300px; 
    height: 800px; 
    z-index: 2;
    overflow: hidden;  
    border-radius: 10px;
}


.carousel-item img {
    height: 270px;  
    object-fit: cover;  
    border-radius: 10px; 
}


.carousel-control-prev, 
.carousel-control-next {
    height: 30px; 
    top: 50%; 
    transform: translateY(-50%); 
}

    .footer{
        position: relative;
    }
}

/*Pantallas 1280x720 */
@media (max-width: 1280px) {
    .container {
        display: flex;
        flex-direction: column; 
        align-items: flex-start; 
        margin: 40px 20px; 
    }

    .search-bar {
        width: 90%; 
        font-size: 18px; 
        margin: 10px 50px; 
    }

    nav {
        position: absolute; 
        left: -7px; 
        top: 100px; 
        display: flex; 
        flex-direction: column; 
        align-items: flex-start; 
        margin-bottom: 20px; 
    }

    .nav-btn span {
    display: none; 
}

    .nav-btn {
        width: 250px; 
        margin-bottom: 10px; 
    }

    .login {
        position: absolute; 
        left: 20px; 
        top: 400px; 
    }

    .novedades {
        width: 100%; 
        text-align: center; 
        margin: 200px 350px 100px;
        margin-left: 300px;
        display: flex; 
        flex-direction: column; 
        align-items: center; 
    }

    .novedades h1 {
        font-size: 28px; 
    }

    .cards-contenedor {
        display: flex; 
        flex-wrap: wrap;
        justify-content: center; 
        gap: 30px; 
        max-width: 1000px; 
        margin: 0 auto; 
        margin-bottom: 40px;
        
    }
    
    .card {
        width: calc(45% - 20px); 
        max-width: 300px; 
        margin: 10px; 
        width:280px;
        height: 400px;
    }
    .authenticated .cards-contenedor {
        gap: 50px;
   }
   .authenticated .novedades{
    margin-top: 225px;
   }

    .carousel-container {
    position: absolute;  
    top: 100px; 
    margin-left:320px;
    width: 60%; 
    max-height: 300px; 
    height: 800px; 
    z-index: 2;
    overflow: hidden;  
    border-radius: 10px;
}


.carousel-item img {
    height: 270px;  
    object-fit: cover;  
    border-radius: 10px; 
}


.carousel-control-prev, 
.carousel-control-next {
    height: 30px; 
    top: 50%; 
    transform: translateY(-50%); 
}

    .footer{
        position: relative;
        font-family: Inter;
font-size: 16px;
font-weight: 500;
line-height: 19.36px;
text-align: left;

    }
}

</style>