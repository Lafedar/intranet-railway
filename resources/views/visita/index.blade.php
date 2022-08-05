@extends('visita.layouts.layout')
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

<div class="container">
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <h2 class="headertekst" align="center">Control de ingresos</h2>
      <hr>
    </div>
  </div>

  <div class="container text-center" >
    <br><br>
    <div class="row">
      <div class="col-md-12"></div>
      <br><br>

      <div class="col-md-4" align="right">
        <a  href=/asignar> <img  src="{{ URL::to('/img/asignar.png') }}" height="150"></a>
      </div>

      <div class="col-md-4">
        <a  href=# data-toggle="modal" data-target="#baja"> <img src="{{ URL::to('/img/baja.png') }}" height="150"> </a>
      </div>

      <div class="col-md-4" align="left">
        <a  href=/consulta > <img src="{{ URL::to('/img/consulta.png') }}" height="150"> </a>
      </div>
    </div>
  </div>
  <div id="footer-lafedar"></div>

  @include('visita.baja')

  <script>  
    $("document").ready(function(){
      setTimeout(function(){
       $("div.alert").fadeOut();
    }, 5000 );

    });
  </script>
  @stop