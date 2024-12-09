<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<title>Intranet Lafedar</title>
	<link rel="icon" href="img/ico.png" type="image/png">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<!-- Custom CSS -->
	<link rel="stylesheet" href="{{ asset('css/encabezadoFooter.css') }}">

	<!-- Scripts -->
	<script type="text/javascript" src="{{ URL::asset('/js/modal-jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/js/modal-popper.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/js/modal-bootstrap.min.js') }}"></script>




</head>

<body class="d-flex flex-column min-vh-100">
	<!-- Header -->
	<header class="page-header ">
		<div>
			<div class="logo">
				<a href="{{ route('home.inicio') }}">
					<img src="{{ asset('storage/Imagenes-principal-nueva/LOGO-LAFEDAR.png') }}"
						alt="Logo de la empresa">
				</a>
			</div>

		</div>
	</header>

	<!-- Main content -->
	<main class="flex-grow-1">
		<div class="container py-4">
			<div class="row">
				@yield('content')
			</div>
		</div>
	</main>

	<!-- Footer -->
	<footer>
		<p>Laboratorio Lafedar S.A. | Laboratorios Federales Argentinos S.A</p>
	</footer>
</body>

</html>