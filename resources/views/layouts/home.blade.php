<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ URL::to('/img/ico.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Intranet Lafedar</title>
    <link rel="stylesheet" href="{{ asset('css/principal-nueva.css') }}">
    <header class="page-header">
        <div class="logo">
            <a href="{{ route('home.inicio') }}">
                <img src="{{ asset('storage/Imagenes-principal-nueva/LOGO-LAFEDAR.png') }}" alt="Logo de la empresa">
            </a>
        </div>

        <div class="menu">
        </div>
        
        @auth
        <p style="color: white; display: inline; white-space: nowrap; font-weight: bold; margin-top: 35px;">{{ Auth::user()->name }}</p>
            <div class="dropdown">

                <button type="button" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Cerrar Sesion">
                    <img src="{{ asset('storage/cursos/user.png') }}" alt="User" id="img-icono-user">
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    
                    <li>
                        <form action="{{ url('/logout') }}" method="POST" id="logoutForm">
                            @csrf
                            <button type="submit" class="dropdown-item"
                                style="border: none; background: none; width: 100%; text-align: left;">
                                Cerrar Sesión
                            </button>
                        </form>
                    </li>


                </ul>
            </div>
        @endauth
    </header>
</head>

<body class="{{ Auth::check() ? 'authenticated' : '' }}">


    <main>
        @yield('content')
    </main>

    <footer>
        <p>Laboratorio Lafedar S.A. | Laboratorios Federales Argentinos S.A </p>
    </footer>


</body>

</html>