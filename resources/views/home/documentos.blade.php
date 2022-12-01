@extends('layouts.app')
@section('content')

<div class="container text-center" >

  <br><br><br>
  
  <div class="row">
    <div class="col-md-12"></div>
    
    <br><br>

    <div class="col-md-3">
      <a  href="/politicas"> <img  src="{{ URL::to('/img/politicas.png') }}" height="140"></a>
    </div>

    <div class="col-md-3">
      <a  href="/planos"> <img  src="{{ URL::to('/img/planos.png') }}" height="140"></a>
    </div>
    
    <div class="col-md-3">
      <a  href="/proyectos"> <img  src="{{ URL::to('/img/proyectos.png') }}" height="140"></a>
    </div>
    
    <div class="col-md-3">
      <a  href="#"> <img  src="{{ URL::to('/img/manuales.png') }}" height="140"></a>
    </div>
  </div>

</div>

<div id="footer-lafedar"></div>

@stop