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
<body>
<header>
    <div class="logo">
        <img src="{{ asset('storage/Imagenes principal-nueva/LOGO-LAFEDAR.png') }}" alt="Logo de la empresa">
    </div>
    
    <input type="text" class="search-bar" placeholder="Buscar por palabra clave" id="search-input" {{ Auth::check() ? '' : 'disabled' }}>
    <div class="btn-cerrar-sesion">
        @if (Auth::check())
            <form action="{{ url('/logout') }}" method="POST">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-danger" style="display:inline;cursor:pointer">
                    Cerrar sesión
                </button>
            </form>
        @else
            <button class="btn btn-danger" disabled>Cerrar sesión</button>
        @endif
    </div>
  </header>
  
    <div id="results-dropdown" class="results-dropdown" style="display: none;">
        <ul id="results-list"></ul>
    </div>
    
    
    <nav>
      
        <a href="/internos" class="nav-btn" style="text-decoration: none;">Internos</a>
        @if(Auth::check())
    <!-- Si el usuario está autenticado, el enlace será visible y habilitado -->
    <a href="{{ route('solicitudes.index') }}" class="nav-btn" style="text-decoration: none;">Solicitudes</a>
@else
    <!-- Si el usuario no ha iniciado sesión, el enlace estará deshabilitado -->
    <a href="#" class="nav-btn" style="text-decoration: none; pointer-events: none; opacity: 0.5;" title="Debes iniciar sesión para acceder">Solicitudes</a>
@endif
        <a href="/documentos" class="nav-btn" style="text-decoration: none;">Documentos</a>
        
    </nav>

    <section class="container">
        <div id="toast" style="display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background-color: #f44336; color: white; padding: 16px; border-radius: 5px; z-index: 1000;">
            <span id="toast-message"></span>
        </div>  
        <div id="toast-success" style="display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background-color: #4CAF50; color: white; padding: 16px; border-radius: 5px; z-index: 1000;">
            <span id="toast-message-success"></span>
        </div>
        <div class="login" {{ Auth::check() ? 'style=opacity:0.5; pointer-events:none;' : '' }}>
          <h2>INICIO DE SESION</h2>
            <form method="POST" action="{{ route('login') }}">
              @csrf
              <div class="icono_usuario">
                  <label>Usuario</label>
              </div>
              <div class="input_usuario">
                  <input type="email" id="email" name="email" required {{ Auth::check() ? 'disabled' : '' }}>
            </div>

                <div class="icono_contraseña">
                  <label>Contraseña</label>
                </div>
                <div class="input_contraseña">
                  <input type="password" id="password" name="password" required {{ Auth::check() ? 'disabled' : '' }}>
                  <a href="{{ route('password.request') }}" style="color:blue;">
                      ¿Olvidaste tu contraseña?
                  </a>
                </div>

                <div class="btn-iniciar-sesion">
                  <button type="submit" style="color:white" {{ Auth::check() ? 'disabled' : '' }}>INICIAR SESION</button>
                </div>
            </form>
        </div>
    
        <div class="novedades">
    <h1>____________________NOVEDADES____________________</h1>
    <div class="cards-contenedor">
        @foreach ($novedades as $novedad)
        
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
                    
                    <a href="{{ route('novedades.index', $novedad->id) }}" class="btn btn-primary">Ver más</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
</section>
  
    <footer >
        <p>​Laboratorios Lafedar S.A.<br>
              Paraná, Entre Rios.<br>
              ​0343- 4363000 <br>
              ​​www.lafedar.com</p>
    </footer> 
</body>
</html>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
    font-size: 16px; 
    height: 65px; 
    width: 90px; 
    border-radius: 5px; 
    border: none; 
    cursor: pointer; 
}

/* CONTAINER LOGIN Y NOVEDADES */
.container {
    display: flex; 
    justify-content: space-between; 
    align-items: flex-start; 
    margin: 40px 20px; 
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

.nav-btn {
    background-color: #004a99;
    color: white;
    border-radius: 10px;
    border: none;
    padding: 10px 20px;
    margin: 20px 30px; 
    margin-left: 57px; 
    cursor: pointer;
    width: 250px; 
    height: 50px;
    text-align: center;
    box-shadow: 0 20px 20px rgba(1, 1, 1, 0.6);
}

.nav-btn:hover {
    background-color: white;
    color: #004a99;
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
}

.login h2 {
    text-align: center;  
    margin-bottom: 20px; 
    font-size: 25px;     
    color: #1C547C;
    font-weight: bold;
}

.login label {
    display: block;
    margin-bottom: 5px;
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
    background-color: #003a7a;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top:5px;
}

.login button:hover {
    background-color: #003a7a;
}

/* NOVEDADES */
.novedades {
    display: flex;
    flex-direction: column; 
    align-items: center;      
    justify-content: flex-start;
    margin-top: -90px; 
    width: 100%; 
    max-width: 800px; 
}

.novedades h1 {
    margin-top: 0; 
    margin-bottom: 20px; 
    color: #196AB2;
    font-weight: 1000;
    font-size: 40px;
    text-align: center; 
    width: 100%; 
}

.cards-contenedor {
    display: flex;              
    justify-content: space-between;  
    flex-wrap: nowrap;         
    gap: 70px; /* espacio entre las tarjetas */               
    margin-top: 10px;   
    margin-left: 400px;       
}

.card {
    width: 300px; 
    height: 350px; 
    border: 1px solid #ddd;   
    border-radius: 10px;      
    overflow: hidden;         
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card img {
    width:100%;
    max-width: 300px;           
    height: 170px;             
}

/* FOOTER */
footer {
    background-color: #1E78C8; 
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
        gap: 20px; 
        max-width: 1000px; 
        margin: 0 auto; 
        margin-bottom: 40px;
    }
    
    .card {
        width: calc(45% - 20px); 
        max-width: 300px; 
        margin: 10px; 
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
        gap: 20px; 
        max-width: 1000px; 
        margin: 0 auto; 
        margin-bottom: 40px;
    }
    
    .card {
        width: calc(45% - 20px); 
        max-width: 300px; 
        margin: 10px; 
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
        gap: 20px; 
        max-width: 1000px; 
        margin: 0 auto; 
        margin-bottom: 40px;
    }
    
    .card {
        width: calc(45% - 20px); /*Dos tarjetas por fila */
        max-width: 300px; 
        margin: 10px; 
    }

    .footer{
        position: relative;
    }

}

</style>