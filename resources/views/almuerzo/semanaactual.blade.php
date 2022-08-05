@extends('almuerzo.layouts.layout')
@section('seccion')


 <div class="table">


	<head>
		<link rel="stylesheet" type="text/css" href="/css/almuerzo.css">
		<script type="text/javascript">
			window  .history.forward();
            function sinVueltaAtras(){ window.history.forward(); }
		</script>
	</head>

	<body onload="sinVueltaAtras();" onpageshow="if (event.persisted) sinVueltaAtras();" onunload="">
		
	<tr>
		@foreach($arreglo as $arreglo)
		<h1>
		<LABEL class="menu"><u>{{$arreglo[0]}}</u></LABEL>
		</h1>
	</br>
	<br/>
 <h3>
	<label class="texto"><b><i><u>Lunes :</u></i></b> {{$arreglo[1]}}</label>
	</br>
	<label class="texto"><b><i><u>Martes : </u></i></b>{{$arreglo[2]}}</label>
	</br>
	<label class="texto"><b><i><u>Miercoles :</u></i></b> {{$arreglo[3]}}</label>
	</br>
	<label class="texto"><b><i><u>Jueves :</u></i></b> {{$arreglo[4]}}</label>
	</br>
	<label class="texto"><b><i><u>Viernes :</u></i></b> {{$arreglo[5]}}</label>
	</br>
	</h3>
	</tr>
	<br>
	<br>	
	<form action= "{{action('AlmuerzoController@elegir')}}" method="POST">
    @csrf
    
	<input type="text" name="dni" id="dni" autocomplete="off"  hidden="true" value={{$arreglo[6]}}>
	
	<div class="container">
		<div class="row">
			<div class="col-4">
	<button type="submit" class="btn btn-primary btn-block">Volver</button>
			</div>
			<div class="col-4">
	<a class="btn btn-secondary btn-block" href="/clog">Salir</a>
			</div>
		</div>
	</div>
	</form>
	@endforeach
	<br>
	
	</body>
	
</div>


@endsection