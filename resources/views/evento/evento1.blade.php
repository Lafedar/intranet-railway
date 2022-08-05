@extends('layouts.app')
@section('content')

@if(Session::has('message'))
<div class="container" id="div.alert">
  <div class="row">
<div class="col-1"></div>
<div class="alert {{Session::get('alert-class')}} col-10 text-center" role="alert">
 {{Session::get('message')}}
</div>
</div>
</div>
@endif
  <head>
    <title></title>
    <meta content="">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    
    <style>
    body{
      font-family: 'Exo', sans-serif;
    }
    .header-col{
      background: #E3E9E5;
      color:#536170;
      text-align: center;
      font-size: 20px;
      font-weight: bold;
    }
    .header-calendar{
      background: #EE192D;color:white;
    }
    .box-day{
      border:1px solid #E3E9E5;
      height:150px;
    }
    .box-dayoff{
      border:1px solid #E3E9E5;
      height:150px;
      background-color: #ccd1ce;
    }

    .btn-ttc,
    .btn-ttc:hover,
    .btn-ttc:active {
     color: white;
     text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
      background-color:#2B547E;
    </style>

  </head>
  <body>

    <div class="container">
      <div style="height:50px"></div>
      <h1>Evento <small></small></h1>
      <p class="lead">
      <!--<h3></h3>-->
      <p>Detalles de evento</p>
      <a class="btn btn-ttc"  href="{{ asset('/Evento/index') }}">Atras</a>
     <!-- <button name ="button1" type="submit" class="btn btn-default"> Buscar</button>-->
      <hr>


    
     
      <div class="col-md-6">
        <form action="{{ asset('/Evento/create/') }}" method="post">
          <div class="fomr-group">
            <u><h4><FONT COLOR= #2B547E >Titulo</FONT></h4></u>
           <em><h5>{{ $event->titulo }} </h5></em>
          </div>
          <div class="fomr-group">
            <u><h4><FONT COLOR= #2B547E >Descripcion y hubicacion del Evento</FONT></h4></u>
           <em><h5> {{ $event->descripcion }} </h5></em>
          </div>
          <div class="fomr-group">
           <u> <h4><FONT COLOR= #2B547E >Donde se hace</FONT></h4></u>
           <em> <h5>{{ $event->ubicacion }}</h5></em>
            </div>
          <div class="fomr-group">
           <u> <h4><FONT COLOR= #2B547E >Fecha</FONT></h4></u>
           <em> <h5>{{ $event->fecha }}</h5></em>
            </div>
          <div class="fomr-group">
            <u><h4><FONT COLOR= #2B547E >Hora</FONT></h4></u>
           <em> <h5>{{ $event->hora }}</h5></em>
          </div>
          <br>
        <!--  <input type="submit" class="btn btn-info" value="Guardar">-->
        </form>
      </div>

    

      <!-- inicio de semana -->


    </div> <!-- /container -->

  </body>
  
@stop