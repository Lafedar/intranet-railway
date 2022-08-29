@extends('layouts.app')
@section('content')

<div class="container text-center" >

    <br><br><br>
  
    <div class="row">
        <div class="col-md-12"></div>
    
        <br><br>

        <div class="col-md-4">
            <a  href="/ventas"> <img  src="{{ URL::to('/img/ventas.png') }}" height="140"></a>
        </div>
    
        <div class="col-md-4">
            <a  href="/compras"> <img  src="{{ URL::to('/img/compras.png') }}" height="140"></a>
        </div>
    
        <div class="col-md-4">
            <a  href="/calidades"> <img  src="{{ URL::to('/img/calidad.png') }}" height="140"></a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12"></div>
    
        <br><br>

        <div class="col-md-4">
            <a  href="/costos"> <img  src="{{ URL::to('/img/costos.png') }}" height="140"></a>
        </div>

        <div class="col-md-4">
            <a  href="/rrhhs"> <img  src="{{ URL::to('/img/rrhh.png') }}" height="140"></a>
        </div>

        <div class="col-md-4">
            <a  href="/producciones"> <img  src="{{ URL::to('/img/produccion.png') }}" height="140"></a>
        </div>
    </div>
</div>
<div id="footer-lafedar"></div>

@stop