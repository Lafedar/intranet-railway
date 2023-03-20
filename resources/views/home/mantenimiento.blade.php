@extends('layouts.app')
@section('content')

<div class="container text-center" >

    <br><br><br>
  
    <div class="row">
        <div class="col-md-12"></div>
    
        <br><br>

        <div class="col-md-4">
            <a  href="/solicitudes"> <img  src="{{ URL::to('/img/ventas.png') }}" height="140"></a>
        </div>
    
        <div class="col-md-4">
            <a  href="/equipos_mant"> <img  src="{{ URL::to('/img/compras.png') }}" height="140"></a>
        </div>
    
        <div class="col-md-4">
            <a  href="/parametros_mantenimiento"> <img  src="{{ URL::to('/img/calidad.png') }}" height="140"></a>
        </div>
    </div>

</div>
<div id="footer-lafedar"></div>

@stop