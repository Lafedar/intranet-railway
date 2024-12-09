
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