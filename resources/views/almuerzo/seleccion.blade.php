@extends('almuerzo.layouts.layout')
@section('seccion')


<head>
<title>Almuerzo</title>
</head>

<body>
 
 <div class="row">
    <div class="col-sm-6">
      <h3 class="mb-6 fst-italic border-bottom">
        seleccion de almuerzo
      </h3>
 
      
 <nav>
 <div class="col-md-12 ml-12">

	<center><h4>Tradicional(1)</h4></center>
  <div class="form-inline pull-Rigth">
	<div>	
	<p> <b>Lunes:</b> {{$comidam->tlun}}</p>
	
	<p> <b>Martes:</b> {{$comidam->tmar}}</p>
	
	<p> <b>Miercoles:</b> {{$comidam->tlmie}}</p>
	
	<p> <b>Jueves:</b> {{$comidam->tjue}}</p>
	
	<p> <b>Viernes:</b> {{$comidam->tvie}}</p>
	
	</div>
  </div>

  <center><h4>Bajas calorias (2)</h4></center>
  <div class="form-inline pull-Rigth">
	<div>	
	<p> <b>Lunes:</b> {{$comidam->bclun}}</p>
	
	<p> <b>Martes:</b> {{$comidam->bcmar}}</p>
	
	<p> <b>Miercoles:</b>{{$comidam->bcmie}}</p>
	
	<p> <b>Jueves:</b> {{$comidam->bcjue}}</p>
	
	<p> <b>Viernes:</b> {{$comidam->bcvie}}</p>
	
	</div>
  </div>

  <center><h4>Merienda (3)</h4></center>
  <div class="form-inline pull-Rigth">
	<div>	
	<p> <b>Lunes:</b> {{$comidam->mlun}}</p>
	
	<p> <b>Martes:</b> {{$comidam->mmar}}</p>
	
	<p> <b>Miercoles:</b> {{$comidam->mmie}}.</p>
	
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
	
	<p> <b>Miercoles:</b> {{$comidam->emie}}</p>

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


	<form action="{{action('AlmuerzoController@guardar')}}" method="POST">
		@csrf

    </div>

    <div class="col-md-6">
      <div class="position-sticky" style="top: 2rem;">
        <div class="p-2 mb-2 bg-light rounded">
          <h4 class="fst-italic">Menu</h4>

         <?php
         $fechad = $comidam->fecha_desde;
         $fechah = $comidam->fecha_hasta;

		 $fechad = date("d-m-Y ",strtotime(str_replace('/','-',$fechad)));
		 $fechah = date("d-m-Y ",strtotime(str_replace('/','-',$fechah)));
		 //$fechac = date("D-m-Y ",strtotime(str_replace('/','-',$fechac)));
		
		 ?>



          <p class="mb-6">semana : {{$fechad}} a {{$fechah}}</p>
        </div>

        <div class="input-field col s6 ">Comensal:
            <select class="form-control" name="id_e"  id="id_e" required>
              @foreach($personas as $personas)
              <option value="{{$personas->nombre_p}} {{$personas->apellido}}">{{$personas->apellido}}&nbsp{{$personas->nombre_p}} </option>
              @endforeach
            </select>
          </div>

        <div class="">
          <h4 class="fst-italic">Lunes</h4>
          <ol class="list-unstyled mb-0">
          	<h5>
        	<div class="form-group">
				<input type="radio" name="lunes" value="1"> 1
				&nbsp
				&nbsp
				<input type="radio" name="lunes" value ="2"> 2 
				&nbsp
				&nbsp
				<input type="radio" name="lunes" value ="3"> 3 
				&nbsp
				&nbsp
				<input type="radio" name="lunes" value ="4"> 4 
				&nbsp
				&nbsp
				<input type="radio" name="lunes" value ="5"> 5 
				&nbsp
				&nbsp
				<input type="radio" name="lunes" value ="6"> 6 
				&nbsp
				&nbsp
				<input type="radio" name="lunes" value ="7"> 7 
			</div>
			</h5>

			 <h4 class="fst-italic">Martes</h4>
          <ol class="list-unstyled mb-0">

          	<h5>
        	<div class="form-group">
				<input type="radio" name="martes" value="1"> 1 
				&nbsp
				&nbsp
				<input type="radio" name="martes" value ="2"> 2 
				&nbsp
				&nbsp
				<input type="radio" name="martes" value ="3"> 3 
				&nbsp
				&nbsp
				<input type="radio" name="martes" value ="4"> 4 
				&nbsp
				&nbsp
				<input type="radio" name="martes" value ="5"> 5 
				&nbsp
				&nbsp
				<input type="radio" name="martes" value ="6"> 6
				&nbsp
				&nbsp
				<input type="radio" name="martes" value ="7"> 7 
			</div>
		</h5>
			 <h4 class="fst-italic">Miercoles</h4>
          <ol class="list-unstyled mb-0">

        	<h5><div class="form-group">
				<input type="radio" name="miercoles" value="1"> 1 
				&nbsp
				&nbsp
				<input type="radio" name="miercoles" value ="22"> 2 
				&nbsp
				&nbsp
				<input type="radio" name="miercoles" value ="3"> 3 
				&nbsp
				&nbsp
				<input type="radio" name="miercoles" value ="4"> 4 
				&nbsp
				&nbsp
				<input type="radio" name="miercoles" value ="5"> 5 
				&nbsp
				&nbsp
				<input type="radio" name="miercoles" value ="6"> 6 
				&nbsp
				&nbsp
				<input type="radio" name="miercoles" value ="7"> 7 
			</div></h5>

			 <h4 class="fst-italic">Jueves</h4>
          <ol class="list-unstyled mb-0">

          	<h5>
        	<div class="form-group">
				<input type="radio" name="jueves" value="1"> 1 
				&nbsp
				&nbsp
				<input type="radio" name="jueves" value ="2"> 2 
				&nbsp
				&nbsp
				<input type="radio" name="jueves" value ="3"> 3 
				&nbsp
				&nbsp
				<input type="radio" name="jueves" value ="4"> 4 
				&nbsp
				&nbsp
				<input type="radio" name="jueves" value ="5"> 5 
				&nbsp
				&nbsp
				<input type="radio" name="jueves" value ="6"> 6 
				&nbsp
				&nbsp
				<input type="radio" name="jueves" value ="7"> 7 
			</div>
			</h5>

			 <h4 class="fst-italic">Viernes</h4>
          <ol class="list-unstyled mb-0">
          	<h5>
        	<div class="form-group">
				<input type="radio" name="viernes" value="1"> 1 
				&nbsp
				&nbsp
				<input type="radio" name="viernes" value ="2"> 2
				&nbsp
				&nbsp
				<input type="radio" name="viernes" value ="3"> 3 
				&nbsp
				&nbsp
				<input type="radio" name="viernes" value ="4"> 4 
				&nbsp
				&nbsp
				<input type="radio" name="viernes" value ="5"> 5 
				&nbsp
				&nbsp
				<input type="radio" name="viernes" value ="6"> 6 
				&nbsp
				&nbsp
				<input type="radio" name="viernes" value ="7"> 7 
			</div>
			</h5>

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

			<button class="btn btn-primary btn-block" type="submit">Guardar</button>
			



		 

</body>


</form>
@endsection

