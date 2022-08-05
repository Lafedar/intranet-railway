@extends('almuerzo.layouts.layout')
@section('seccion')


<head>
<title>Almuerzo Lafedar SA</title>
<script type="text/javascript">
  window  .history.forward();
    function sinVueltaAtras(){ window.history.forward(); }
</script>
<script src="/scripts/snippet-javascript-console.min.js?v=1"></script>

</head>

<body onload="sinVueltaAtras();" onpageshow="if (event.persisted) sinVueltaAtras();" onunload="">
 
 <div class="row">
    <div class="col-sm-6">
      <h3 class="mb-6 fst-italic border-bottom">
        seleccion de almuerzo
      </h3>
      
 <div class="container-fluid">	 
 <nav>
 <div class="col-md-12 ml-12">

	<center><h4>Tradicional(1)</h4></center>
  <div class="form-inline pull-Rigth">
	<div>	
	<p> <b>Lunes:</b> {{$comidam->tlun}}</p>
	
	<p> <b>Martes:</b> {{$comidam->tmar}}</p>
	
	<p> <b>Miercoles:</b> {{$comidam->tmier}}</p>
	
	<p> <b>Jueves:</b> {{$comidam->tjue}}</p>
	
	<p> <b>Viernes:</b> {{$comidam->tvie}}</p>
	
	</div>
  </div>

  <center><h4>Bajas calorias (2)</h4></center>
  <div class="form-inline pull-Rigth">
	<div>	
	<p> <b>Lunes:</b> {{$comidam->bclun}}</p>
	
	<p> <b>Martes:</b> {{$comidam->bcmar}}</p>
	
	<p> <b>Miercoles:</b>{{$comidam->bcmier}}</p>
	
	<p> <b>Jueves:</b> {{$comidam->bcjue}}</p>
	
	<p> <b>Viernes:</b> {{$comidam->bcvie}}</p>
	
	</div>
  </div>

  <center><h4>Merienda (3)</h4></center>
  <div class="form-inline pull-Rigth">
	<div>	
	<p> <b>Lunes:</b> {{$comidam->mlun}}</p>
	
	<p> <b>Martes:</b> {{$comidam->mmar}}</p>
	
	<p> <b>Miercoles:</b> {{$comidam->mmier}}.</p>
	
	<p> <b>Jueves:</b> {{$comidam->mjue}}</p>
	
	<p> <b>Viernes:</b> {{$comidam->mvie}}</p>
	
	</div>
  </div>

  <center><h4>Yogurt y Frutas (4)</h4></center>
  &nbsp<br/>
  <center><h4>Ensaladas (5)</h4></center>
  <div class="form-inline pull-Rigth">
	<div>	
	<p> <b>Lunes:</b> {{$comidam->elun}}</p>
	
	<p> <b>Martes:</b> {{$comidam->emar}}<p>
	
	<p> <b>Miercoles:</b> {{$comidam->emier}}</p>

	<p> <b>Jueves:</b> {{$comidam->ejue}}</p>
	
	<p> <b>Viernes:</b> {{$comidam->evie}}</p>
	
	</div>
  </div>

  <center><h4>Colacion (6)</h4></center>
  <div class="form-inline pull-Rigth">
	<div>	
	<p> Ensalada de frutas con barra de cereales.</p>
	</div>
  </div>

  <center><h4>Merienda (7)</h4></center>
  <div class="form-inline pull-Rigth">
	<div>	
	<p> Factura/medias lunas con chocolatada, jugo o café con leche.</p>
	</div>
  </div>
 
 


</div>
&nbsp
&nbsp
 </nav>
</div>	
		
		

	<form action="{{action('AlmuerzoController@actualizar')}}" method="POST">
		@csrf

    </div>





    <div class="col-md-6">
      <div class="position-sticky" style="top: 2rem;">
        <div class="p-2 mb-2 bg-light rounded">
          <h4 class="fst-italic">Menu</h4>
          <div class="input-field col s6 ">
        	
        	   	

        	
       	        
				<?php
      			   $fechade = $comi->fecha_desde;
       			   $fechaha = $comi->fecha_hasta;
       			   $hoy=date("Y-m-d");
       			   $sem=$comi->id_sem;

					$fechad = date("d-m-Y ",strtotime(str_replace('/','-',$fechade)));
		 			$fechah = date("d-m-Y ",strtotime(str_replace('/','-',$fechaha)));
		 			$fhoy=date("Y-m-d",strtotime(str_replace('/','-',$hoy)));
		 			$activo=$comi->activo;
			 
				?>

				
		<div class="card">
		<div class="card-header">
			Semana : {{$sem}}
		</div>	
		<div class="card-body text-center" >
		    <p class="mb-0" ><i> {{$fechad}} a {{$fechah}} </i></p>
       
       </div>
       </div>


        </div>
        	
      
        <div class="input-field col s6 ">Comensal:
          
            <select class="form-control" name="id_e" id="id_e" >
             
             	<option value="">Buen dia seleccione su nombre</option>
             	@foreach($personas as $personas)
             		<?php
						$nom=$personas->apellido." ".$personas->nombre_p;
         				//$salida=str_replace(" ","", $nom);
         				$id_p=$personas->id_p;
             		?>
                <option value="{{$nom}}" >{{$personas->apellido}}&nbsp{{$personas->nombre_p}} </option>
              	
              	@endforeach
            </select>
           
          </div>
          	
        <div class="">
          <h4 class="fst-italic">Lunes</h4>
          <ol class="list-unstyled mb-0">
          	<h5>
          		<input type="text" name="dni" id="dni" hidden="true" >
          		<input type="text" name="id" id="id" hidden="true">
        	<div class="form-group">
        		<input type="radio" name="lunes" id="lunes_1" value="1" required=""> 1
				&nbsp
				&nbsp
				<input type="radio" name="lunes" id="lunes_2" value ="2" required=""> 2 
				&nbsp
				&nbsp
				<input type="radio" name="lunes" id="lunes_3" value ="3" required=""> 3 
				&nbsp
				&nbsp
				<input type="radio" name="lunes" id="lunes_4" value ="4" required=""> 4 
				&nbsp
				&nbsp
				<input type="radio" name="lunes" id="lunes_5" value ="5" required=""> 5 
				&nbsp
				&nbsp
				<input type="radio" name="lunes" id="lunes_6" value ="6" required=""> 6 
				&nbsp
				&nbsp
				<input type="radio" name="lunes" id="lunes_7" value ="7" required=""> 7 
			</div>
			</h5>
		    </ol>
			 <h4 class="fst-italic">Martes</h4>
          <ol class="list-unstyled mb-0">

          	<h5>
        	<div class="form-group">
				<input type="radio" name="martes" id="martes_1" value="1" required=""> 1 
				&nbsp
				&nbsp
				<input type="radio" name="martes" id="martes_2" value ="2" required=""> 2 
				&nbsp
				&nbsp
				<input type="radio" name="martes" id="martes_3" value ="3" required=""> 3 
				&nbsp
				&nbsp
				<input type="radio" name="martes" id="martes_4" value ="4" required=""> 4 
				&nbsp
				&nbsp
				<input type="radio" name="martes" id="martes_5" value ="5" required=""> 5 
				&nbsp
				&nbsp
				<input type="radio" name="martes" id="martes_6" value ="6" required=""> 6
				&nbsp
				&nbsp
				<input type="radio" name="martes" id="martes_7" value ="7" required=""> 7 
			</div>
		</h5>
		</ol>
			 <h4 class="fst-italic">Miercoles</h4>
          <ol class="list-unstyled mb-0">

        	<h5><div class="form-group">
				<input type="radio" name="miercoles" id="miercoles_1" value="1" required=""> 1 
				&nbsp
				&nbsp
				<input type="radio" name="miercoles" id="miercoles_2" value ="2" required=""> 2 
				&nbsp
				&nbsp
				<input type="radio" name="miercoles" id="miercoles_3" value ="3" required=""> 3 
				&nbsp
				&nbsp
				<input type="radio" name="miercoles" id="miercoles_4" value ="4" required=""> 4 
				&nbsp
				&nbsp
				<input type="radio" name="miercoles" id="miercoles_5" value ="5" required=""> 5 
				&nbsp
				&nbsp
				<input type="radio" name="miercoles" id="miercoles_6" value ="6" required=""> 6 
				&nbsp
				&nbsp
				<input type="radio" name="miercoles" id="miercoles_7" value ="7" required=""> 7 
			</div></h5>
			</ol>
			 <h4 class="fst-italic">Jueves</h4>
          <ol class="list-unstyled mb-0">
          	<h5>
        	<div class="form-group">
				<input type="radio" name="jueves" id="jueves_1" value="1" required=""> 1 
				&nbsp
				&nbsp
				<input type="radio" name="jueves" id="jueves_2" value ="2" required=""> 2 
				&nbsp
				&nbsp
				<input type="radio" name="jueves" id="jueves_3" value ="3" required=""> 3 
				&nbsp
				&nbsp
				<input type="radio" name="jueves" id="jueves_4" value ="4" required=""> 4 
				&nbsp
				&nbsp
				<input type="radio" name="jueves" id="jueves_5" value ="5" required=""> 5 
				&nbsp
				&nbsp
				<input type="radio" name="jueves" id="jueves_6" value ="6" required=""> 6 
				&nbsp
				&nbsp
				<input type="radio" name="jueves" id="jueves_7" value ="7" required=""> 7 
			</div>
			</h5>
		</ol>

			 <h4 class="fst-italic">Viernes</h4>
          <ol class="list-unstyled mb-0">
          	<h5>
        	<div class="form-group">
				<input type="radio" name="viernes" id="viernes_1" value="1" required=""> 1 
				&nbsp
				&nbsp
				<input type="radio" name="viernes" id="viernes_2" value ="2" required=""> 2
				&nbsp
				&nbsp
				<input type="radio" name="viernes" id="viernes_3" value ="3" required=""> 3 
				&nbsp
				&nbsp
				<input type="radio" name="viernes" id="viernes_4" value ="4" required=""> 4 
				&nbsp
				&nbsp
				<input type="radio" name="viernes" id="viernes_5" value ="5" required=""> 5 
				&nbsp
				&nbsp
				<input type="radio" name="viernes" id="viernes_6" value ="6" required=""> 6 
				&nbsp
				&nbsp
				<input type="radio" name="viernes" id="viernes_7" value ="7" required=""> 7 
			</div>
			</h5>
		</ol>
			
			
		
				&nbsp
				&nbsp
				&nbsp
				&nbsp
			
			<ol class="list-unstyled mb-5">

				<li><b>Referencias</b></li>
				<li> 1----Tradicional </li>
				<li> 2----Bajas Calorias </li>
				<li> 3----Merienda </li>
				<li> 4----Yogurt & Fruta </li>
				<li> 5----Ensalada </li>
				<li> 6----Colacion </li>
				<li> 7----Merienda </li>
			</ol>
			
			
			@if ($admin)
			<div class="container">
				<div class="row">
					<div class="col-8">
						<button class="btn btn-primary btn-block"  type="submit" id="guardar" >Guardar</button>
						<button class="btn btn-primary btn-block"  type="submit" id="actualizar" style="display:none; color: white;">Actualizar</button>
					</div>
					<div class="col-4">
			<a href="{{route('almuerzo.inicio')}}" class="btn btn-warning btn-block" style="background-color: #0CA664; border-color: #0CA664;">Inicio</a>
					</div>
				</div>
			</div>
			<br>
			      				
			@else

			@if($activo)

			<button class="btn btn-primary btn-block" type="submit" id="guardar" >Guardar</button>
			<button class="btn btn-primary btn-block" type="submit" id="actualizar"  style="display:none" >Actualizar </button>
			<a href="{{route('semanaactual',$nom )}}" class="btn btn-block btn-info">Revisar almuerzo de la semana</a>
			@else
			<a class="btn btn-primary btn-block"  href="{{route('alogin')}}" style="background-color:#D41C1C;border-color:#D41C1C; ">ya vencio el plazo de seleccion <i>,(volver)</i></a>
			<a href="{{route('semanaactual',$nom )}}" class="btn btn-block btn-info">Revisar almuerzo de la semana</a>
			

        	@endif
        	
        		@endif
</body>		
</form>


<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" />		
			


<script type="text/javascript">
	 $(document).ready(function () {
          var cod = document.getElementById("id_e").value; 
      });
</script>
<script type="text/javascript" src= '/js/seleccionar.js'></script>




@endsection