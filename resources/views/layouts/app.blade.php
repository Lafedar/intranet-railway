<!DOCTYPE html>
<html lang="es">



<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">

<link rel="stylesheet" href="{{ asset('css/styles.css') }}">


<script type="text/javascript" src="{{ URL::asset('/js/modal-popper.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-bootstrap.min.js') }}"></script>

<head>

	<meta charset="UTF-8">

	<title>Intranet Lafedar</title>

	<link rel="icon" href="img/ico.png" type="image/png" />

	<meta name="csrf-token" content="{{ csrf_token() }}">

	<header class="page-header">
		<div class="logo">
			<a href="{{ route('home.inicio') }}">
				<img src="{{ asset('storage/Imagenes-principal-nueva/LOGO-LAFEDAR.png') }}" alt="Logo de la empresa"
					style="margin-left: 0px;">
			</a>
		</div>

		<div class="menu">
		</div>
	</header>
	</nav>
</head>

<body>
	<div class="row">
		@yield('content')
	</div>
</body>
<footer>
	<p>Laboratorio Lafedar S.A. | Laboratorios Federales Argentinos S.A </p>
</footer>

</html>