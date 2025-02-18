<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<title>Intranet Lafedar</title>
	<link rel="icon" href="{{ asset('img/ico.png') }}" type="image/png">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@200&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('css/encabezadoFooter.css') }}">
	<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">

	@stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">
	<!-- Header -->
	<header class="page-header d-flex justify-content-between align-items-center py-3">
		<div class="logo">
			<a href="{{ route('home.inicio') }}">
				<img src="{{ asset('storage/Imagenes-principal-nueva/LOGO-LAFEDAR.png') }}" alt="Logo de la empresa">
			</a>
		</div>


		@auth
			<div class="d-flex align-items-center" id="user-container">
				<p id="nombre-usuario">{{ Auth::user()->name }}</p>
				<div style="margin-top: -33px;">
					<button type="button" id="dropdown" title="Cerrar Sesion">
						<img src="{{ asset('storage/cursos/user.png') }}" alt="User" id="img-icono-user">
					</button>
					<ul class="dropdown-menu dropdown-menu-right" id="dropdown-menu">
						<li>
							<form action="{{ url('/logout') }}" method="POST" id="logoutForm">
								@csrf
								<button type="submit" class="dropdown-item" id="cerrar-sesion">
									Cerrar Sesión
								</button>
							</form>
						</li>
					</ul>
				</div>
			</div>
		@endauth
	</header>

	<!-- Main content -->
	<main class="flex-grow-1">
		<div class="container-fluid py-4">
			@yield('content')
		</div>
	</main>

	@stack('modales')

	<!-- Footer -->
	<footer class="text-center py-3">
		<p>Laboratorio Lafedar S.A. | Laboratorios Federales Argentinos S.A</p>
	</footer>

	<!--Script para desplegar el dropdown-->
	<script>
		// Obtener el botón y el menú del dropdown
		const dropdownBtn = document.getElementById('dropdown');
		const dropdownMenu = document.getElementById('dropdown-menu');

		// Añadir el evento click al botón
		dropdownBtn.addEventListener('click', function () {
			// Toggle para mostrar u ocultar el menú
			dropdownMenu.classList.toggle('show');
		});

		// Opcional: Cerrar el dropdown si se hace clic fuera de él
		document.addEventListener('click', function (e) {
			if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
				dropdownMenu.classList.remove('show');
			}
		});
	</script>

	@stack('scripts')
</body>

</html>