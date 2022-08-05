<@extends('layouts.app')
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
      <h1>"Buen dia !" <small>programemos un evento!</small></h1>
      <p class="lead">
      <h3>Evento</h3>
      <p>Formulario de evento</p>
      <a class="btn btn-ttc"  href="{{ asset('/Evento') }}">Atras</a>
      <hr>

      @if (count($errors) > 0)
        <div class="alert alert-danger">
         <button type="button" class="close" data-dismiss="alert">×</button>
         <ul>
          @foreach ($errors->all() as $error)
           <li>{{ $error }}</li>
          @endforeach
         </ul>
        </div>
       @endif
       @if ($message = Session::get('success'))
       <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
          <strong>{{ $message }}</strong>
       </div>
       @endif


      <div class="col-md-6">
        <form action="{{ asset('/Evento/create') }}" method="post">
          @csrf
          <div class="fomr-group">
            <label>Titulo</label>
            <input type="text" class="form-control" name="titulo">
          </div>
          <div class="fomr-group">
            <label>Quien Reserva</label>
              
              

<input type="text" class="form-control" name="solicitado">
          </div>
          
          <div class="fomr-group">
            <label>Descripcion del Evento</label>
            <input type="text" class="form-control" name="descripcion">
          </div>
          <div class="fomr-group">
            <label>Donde tendra Lugar</label>
            
            <select name="ubicacion" class="form-control" required>
                  <option value="Sala Vidriada">Sala Vidriada</option> 
                  <option value="Auditorio">Auditorio</option> 
                  <option value="Sala ProtocolarSala Protocolar">Sala Protocolar</option>
                  <option value="Sala Vidridada 2">Sala Vidridada 2</option> 
                  <option value="Sala Compras">Sala Compras</option> 
            </select>

          </div>
          <div class="fomr-group">
            <label>Fecha</label>
            <input type="date" class="form-control" name="fecha" >
          </div>
          <div class="fomr-group">
            <label>Hora/label>
            <input type="time" class="form-control" name="hora">
          </div>

          <input type="hidden" type="text" name="activo" id= "activo" value= "1"  >
          <br>
          <input type="submit" class="btn btn-info" value="Guardar">
        </form>
      </div>


      <!-- inicio de semana -->


    </div> <!-- /container -->

   
  </body>
@stop
