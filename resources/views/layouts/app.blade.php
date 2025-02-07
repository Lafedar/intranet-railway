<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<title>Intranet Lafedar</title>
	<link rel="icon" href="{{ asset('img/ico.png') }}" type="image/png">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

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
			<div class="dropdown">
				<button type="button" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Cerrar Sesión">
					<img src="{{ asset('storage/cursos/user.png') }}" alt="User" id="img-icono-user">
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdown">
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
			@yield('content')
		</div>
	</main>

	@stack('modales')

	<!-- Footer -->
	<footer class="text-center py-3">
		<p>Laboratorio Lafedar S.A. | Laboratorios Federales Argentinos S.A</p>
	</footer>


	<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>

	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>-->


	@stack('scripts')
</body>

</html>