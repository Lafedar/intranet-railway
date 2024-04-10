<!DOCTYPE html>
<html lang="es">

{{--<META HTTP-EQUIV="Refresh" CONTENT="600;URL=http://intranet.lafedar">--}}

<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">

<script type="text/javascript" src="{{ URL::asset('/js/modal-jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-popper.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-bootstrap.min.js') }}"></script>

<head>

	<meta charset="UTF-8">

	<title>Intranet Lafedar</title>

	<link  rel="icon"   href="img/ico.png" type="image/png" />

	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		
		<a class="navbar-brand" href="/"> <img class="logo" src="{{ URL::to('/img/logo.png') }}" height="40"> </a>
		

		<div class="collapse navbar-collapse" id="navbar1">
			<ul class="navbar-nav ml-auto"> &nbsp
				<div id="notificacion_off" style="display: none">
					<a> <img  src="{{ URL::to('/img/campana.png') }}"  height="40" ></a>
				</div>
				
				<div  id="notificacion_on" style="display: none">
					<a  href="#" data-toggle="modal" data-target="#novedades"> <img  src="{{ URL::to('/img/CampanaRoja.png') }}" height="40" ></a>
				</div>
			</ul>

		</div>
		
	</nav>                 
</head>

<body>	
	<div class ="row">
	@yield('content')
	</div>
</body>
 @include('home.novedades')
</html>
