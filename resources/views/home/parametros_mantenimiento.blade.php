@extends('layouts.app_parametros_mant')
@section('content')

<div class="container text-center" >

    <br><br><br>
  
    <div class="row">
        <br><br>
        <div class="col-md-3" aling="center">
            <a  href="/areas"> <img  src="{{ URL::to('/img/areas.png') }}" height="140"></a>
            <h3 style="color: #3b557a">Areas</h3>
        </div>
        <div class="col-md-3" aling="center">
            <a  href="/localizaciones"> <img  src="{{ URL::to('/img/localizaciones.png') }}" height="140"></a>
            <h3 style="color: #3b557a">Localizaciones</h3>
        </div>
        <div class="col-md-3" aling="center">
            <a  href="/estados"> <img  src="{{ URL::to('/img/estados.png') }}" height="140"></a>
            <h3 style="color: #3b557a">Estados</h3>
        </div>
        <div class="col-md-3" aling="center">
            <a  href="/"> <img  src="{{ URL::to('/img/fallas.png') }}" height="140"></a>
            <h3 style="color: #3b557a">Fallas</h3>
        </div>

        <div class="col-md-3" aling="center">
            <br>
            <a  href="/"> <img  src="{{ URL::to('/img/tipos de equipos.png') }}" height="140"></a>
            <h3 style="color: #3b557a">Tipos de equipos</h3>
        </div>
        <div class="col-md-3" aling="center">
            <br>
            <a  href="/"> <img  src="{{ URL::to('/img/tipos de solicitudes.png') }}" height="140"></a>
            <h3 style="color: #3b557a">Tipos de solicitudes</h3>
        </div>
        <div class="col-md-3" aling="center">
            <br>
            <a  href='/usuarios'> <img  src="{{ URL::to('/img/usuarios.png') }}" height="140"></a>

        </div>
        <div class="col-md-3" aling="center">
            <br>
            <a  href='roles'> <img  src="{{ URL::to('/img/roles.png') }}" height="140"></a>

        </div>

    </div>

</div>
<div id="footer-lafedar"></div>

@stop