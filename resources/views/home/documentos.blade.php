@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
@section('content')

<div class="container text-center">

  <br><br><br>

  <div class="row">
    <div class="col-md-12"></div>
    <br><br>
    <div class="col-md-3">
      <a href="/politicas"> <img src="{{ URL::to('/img/seguridad.png') }}" height="150"></a>
    </div>
    @auth
      @role('administrador')
      <div class="col-md-3">
        <a href="/planos"> <img src="{{ URL::to('/img/planos.png') }}" height="140"></a>
      </div>
      <div class="col-md-3">
        <a href="/proyectos"> <img src="{{ URL::to('/img/proyectos.png') }}" height="140"></a>
      </div>
      <div class="col-md-3">
        <a href="/instructivos"> <img src="{{ URL::to('/img/instructivos.png') }}" height="140"></a>
      </div>
      </div>

      @endrole
      @role('ingenieria')
      <div class="col-md-3">
      <a href="/planos"> <img src="{{ URL::to('/img/planos.png') }}" height="140"></a>
      </div>
      <div class="col-md-3">
      <a href="/proyectos"> <img src="{{ URL::to('/img/proyectos.png') }}" height="140"></a>
      </div>
      <div class="col-md-3">
      <a href="/instructivos"> <img src="{{ URL::to('/img/instructivos.png') }}" height="140"></a>
      </div>
    </div>
    @endrole
    @role('planos')
    <div class="col-md-3">
      <a href="/planos"> <img src="{{ URL::to('/img/planos.png') }}" height="140"></a>
    </div>
    @endrole
    @role('proyectos')
    <div class="col-md-3">
      <a href="/proyectos"> <img src="{{ URL::to('/img/proyectos.png') }}" height="140"></a>
    </div>
    @endrole
    @role('instructivos')
    <div class="col-md-3">
      <a href="/instructivos"> <img src="{{ URL::to('/img/instructivos.png') }}" height="140"></a>
    </div>
    </div>
    @endrole
  @endauth

</div>

<div id="footer-lafedar"></div>

@stop