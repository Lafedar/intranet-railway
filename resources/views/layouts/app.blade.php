<head>
	<meta charset="UTF-8">
	<title>Intranet Lafedar</title>
	<link rel="icon" href="img/ico.png" type="image/png">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Custom CSS -->
	<link rel="stylesheet" href="{{ asset('css/encabezadoFooter.css') }}">

	

</head>

<body class="d-flex flex-column min-vh-100">
	<!-- Header -->
	<header class="page-header d-flex justify-content-between align-items-center">
		<div class="logo">
			<a href="{{ route('home.inicio') }}">
				<img src="{{ asset('storage/Imagenes-principal-nueva/LOGO-LAFEDAR.png') }}" alt="Logo de la empresa">
			</a>
		</div>

		@auth
			<div class="dropdown">
				<button type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"
					style="border: none; background: none; padding: 0; cursor: pointer;">
					<img src="{{ asset('storage/cursos/user.png') }}" alt="User" id="img-icono-user">
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<!-- Cerrar Sesión: Este enlace ahora envía el formulario de logout -->
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

	<!-- Main content -->
	<main class="flex-grow-1">
		<div class="container-fluid py-4">
			<div class="row">
				@yield('content')
			</div>
		</div>
	</main>
	@stack('modales') 
	<!-- Footer -->
	<footer>
		<p>Laboratorio Lafedar S.A. | Laboratorios Federales Argentinos S.A</p>
	</footer>

	<!-- Bootstrap JS (ensure Popper.js and Bootstrap JS are loaded) -->
	
</body>

</html>