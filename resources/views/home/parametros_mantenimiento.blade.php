@extends('layouts.app')
@section('content')

<div class="container text-center" >

    <br><br><br>
  
    <div class="row">
        <br><br>
        <div class="col-md-3" aling="center">
            <a  href="/areas"> <img  src="{{ URL::to('/img/ventas.png') }}" height="140"></a>
        </div>
        <div class="col-md-3" aling="center">
            <a  href="/localizaciones"> <img  src="{{ URL::to('/img/compras.png') }}" height="140"></a>
        </div>
        <div class="col-md-3" aling="center">
            <a  href="/"> <img  src="{{ URL::to('/img/calidad.png') }}" height="140"></a>
        </div>
        <div class="col-md-3" aling="center">
            <a  href="/"> <img  src="{{ URL::to('/img/ventas.png') }}" height="140"></a>
        </div>

        <div class="col-md-3" aling="center">
            <br>
            <a  href="/"> <img  src="{{ URL::to('/img/compras.png') }}" height="140"></a>
        </div>
        <div class="col-md-3" aling="center">
            <br>
            <a  href="/"> <img  src="{{ URL::to('/img/calidad.png') }}" height="140"></a>
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