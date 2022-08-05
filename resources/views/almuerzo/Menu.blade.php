@extends('almuerzo.layouts.layout')
@section('seccion')

<head>
<title>Almuerzo</title>
<link rel="stylesheet" type="text/css" href="/css/almuerzo.css">
</head>

<body>
 	<div class="container">
 		<div class="row">
 			<div class="col-md-12">
 				<div class="page-header">
 					<h1 class="menu text-center"><b><u>
 						Menu para almuerzo
 					</b></u></h1>
 						<form class="row g-3">
 						<div class="col-md-6">
 						<select class="form-control" name="id_f" id="id_f">
 							<option value="" > selecciones una fecha</option> 
 							@foreach($comidam as $item)
 							<option value="{{$item->id}}"><?php echo date("d-m-Y ",strtotime(str_replace('/','-',$item->fecha_desde))); ?> </option>
 							@endforeach
						</select>
						</div>
						<div class="col-md-6">
							<input type="button" class="btn btn-primary d-print-none" value="Imprimir" onClick="window.print()">
						
							<a type="button" class="btn btn-success" href="/almuerzo">Inicio</a>
						</div>
						</form>	
					<h4>
						<h3 class="menu"><u><b>Menu Tradicional (1)</b></u></h3>
					
					<label class="texto">Lunes : </label>	
					<label class="texto" id="tlun" name="tlun"></label>
					<br>
					<label class="texto">Martes : </label>
					<label class="texto" id="tmar" name="tmar"></label>
					<br>
					<label class="texto">Miercoles :</label>
					<label class="texto" id="tmier" name="tmier"></label>
					<br>
					<label class="texto">Jueves :</label>
					<label class="texto" id="tjue" name="tjue"></label>
					<br>
					<label class="texto">Viernes :</label>
					<label class="texto" id="tvie" name="tvie"></label>
					<br>
						<h3 class="menu"><u><b>Menu Bajas Calorias (2)</b></u></h3>
					<label class="texto">Lunes : </label>
					<label class="texto" id="bclun" name="bclun"></label>
					<br>
					<label class="texto">Martes : </label>
					<label class="texto" id="bcmar" name="bcmar"></label>
					<br>
					<label class="texto">Miercoles : </label>
					<label class="texto" id="bcmier" name="bcmier"></label>
					<br>
					<label class="texto">Jueves : </label>
					<label class="texto" id="bcjue" name="bcjue"></label>
					<br>
					<label class="texto">Viernes : </label>
					<label class="texto" id="bcvie" name="bcvie"></label>	
					<br>
					<h3 class="menu"><u><b>Menu de verano (3):</b></u></h3>
					<label class="texto">Lunes : </label>
					<label class="texto" id="mlun" name="mlun"></label>
					<br>
					<label class="texto">Martes : </label>
					<label class="texto" id="mmar" name="mmar"></label>
					<br>
					<label class="texto">Miercoles : </label>
					<label class="texto" id="mmier" name="mmier"></label>
					<br>
					<label class="texto">Jueves : </label>
					<label class="texto" id="mjue" name="mjue"></label>
					<br>
					<label class="texto">Virenes : </label>
					<label class="texto" id="mvie" name="mvie"></label>
					<br>
						<h3 class="menu"><u><b>Yogurt y Fruta (4):</b></u></h3>
					<!--<label id="y_y_f" name="y_y_f"></label>-->
					<br>
					<h3 class="menu"><u><b>Ensaladas (5): </b></u></h3>
					<label class="texto">Lunes : </label>	
					<label class="texto" id="elun" name="elun"></label>
					<br>
					<label class="texto">Martes : </label>
					<label class="texto" id="emar" name="emar"></label>
					<br>
					<label class="texto">Miercoles : </label>
					<label class="texto" id="emier" name="emier"></label>
					<br>
					<label class="texto">Jueves : </label>
					<label class="texto" id="ejue" name="ejue"></label>
					<br>
					<label class="texto">Viernes : </label>
					<label class="texto" id="evie" name="evie"></label>
					<br>
						<h3 class="menu"><u><b>Colacion (6):</b></u></h3>
					<label class="texto" id="colacion" name="colacion"></label>
					<br>
					<h3 class="menu"><u><b>Merienda (7):</b></u></h3>
					<label class="texto" id="merienda" name="merienda"></label>
						
						

					</h4>
 					
 				</div>
 				
 			</div>
 			
 		</div>
 		
 	</div>
</body>

<script type="text/javascript" src="/js/oldies.js"></script>

@endsection