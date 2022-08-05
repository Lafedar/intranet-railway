@extends('almuerzo.layouts.layout')
@section('seccion')
 <head>
  <link rel="stylesheet" type="text/css" href="/css/almuerzo.css">
</head>

 <div class="container">
       <a href="{{route('nuevo')}}"class="btn btn-primary botones">cargar Menu</a>
       <a href="{{route('seleccionar')}}"class="btn btn-primary botones">Elegir Menu</a>
       <a href="{{route('almuerzo.download')}}" class="btn btn-success botones" data-toggle="modal" data-target="#download">bajada</a>
       <a href= "{{route('menusid')}}" class="btn btn-primary botones" >Historico</a>
       <a class="btn btn-primary d-print-none botones" style="color: white" onClick="window.print()">Imprimir</a>
       <a href="{{route('cerrarsem')}}"class="btn btn-primary botones">Cerrar Semana</a>
       <a href="/clog" class="btn btn-primary botones">login</a>
 </div>
<br/>

 <div class="col-md-12 ml-auto">
    <h1>
       <div class="form-inline pull-right">
          <form  method="GET">
           
            <div class="form-group">
              <div class="form-group ">
                <label class="botones"><b>Nombre:</b></label>
                <input type="text" name="nombre" class="form-control button ent" id="nombre" autocomplete="off">
            </div>
            &nbsp

           <label class="botones"><b>Semana:</b></label>
            <select class="form-control botones" name="idsem" id="idsem">
              @foreach($comidam as $item)
                <?php
                  $fini=date("d-m-Y ",strtotime(str_replace('/','-',$item->fecha_desde)));
                  $ffin=date("d-m-Y ",strtotime(str_replace('/','-',$item->fecha_hasta)));

                ?>

              <option value="{{$item->id}}">{{$fini}} - {{$ffin}}</option>
              @endforeach
            </select>
                  
            &nbsp
            <button type="submit" class="btn btn-success botones"><b>Buscar</b></button>
          </div>
        </form>
    </div>
  </h1>     
</div>

<div class="container my-4">
  <h1 class="display-4 menu1"><b>Seleccion de almuerzos</b></h1>

<table class="table table-striped">
  <thead>
    
    <tr class="texto1">
      <th scope="col">Empleado</th>
      <th scope="col">Lunes</th>
      <th scope="col">Martes</th>
      <th scope="col">Miercoles</th>
      <th scope="col">Jueves</th>
      <th scope="col">Viernes</th>
    </tr>
  </thead>
  <tbody>
    
      @foreach($almuerzo as $comida)
      <tr class="texto1">
      	<td>{{$comida->id_e}}</td>
      	<td>{{$comida->lunes}}</td>
      	<td>{{$comida->martes}}</td>
      	<td>{{$comida->miercoles}}</td>
      	<td>{{$comida->jueves}}</td>
      	<td>{{$comida->viernes}}</td>
      	

      </tr>
      @endforeach

  </tbody>
</table>
</h1>
</div>
{{$almuerzo->links()}}
<br>

@include ('almuerzo.download')

<script>
  $('#download').on('show.bs.modal', function (event) {
    
})
</script>

@endsection