@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> 
@section('content')

<div class="container text-center" id="parametros-mant-container">

    <br><br><br>

    <div class="row">
        <br><br>
        <div class="col-md-4" aling="center">
            <a href="/areas"> <img src="{{ URL::to('/img/areas.png') }}" height="140"></a>
            <h3 style="color: #3b557a">Areas</h3>
        </div>
        <div class="col-md-4" aling="center">
            <a href="/localizaciones"> <img src="{{ URL::to('/img/localizaciones.png') }}" height="140"></a>
            <h3 style="color: #3b557a">Localizaciones</h3>
        </div>
        <div class="col-md-4" aling="center">
            <a href="/estados"> <img src="{{ URL::to('/img/estados.png') }}" height="140"></a>
            <h3 style="color: #3b557a">Estados</h3>
        </div>

        <div class="col-md-4" aling="center">
            <br>
            <a href="/fallas"> <img src="{{ URL::to('/img/fallas.png') }}" height="140"></a>
            <h3 style="color: #3b557a">Fallas</h3>
        </div>
        <div class="col-md-4" aling="center">
            <br>
            <a href="/tipos_equipos"> <img src="{{ URL::to('/img/tipos de equipos.png') }}" height="140"></a>
            <h3 style="color: #3b557a">Tipos de equipos</h3>
        </div>
        <div class="col-md-4" aling="center">
            <br>
            <a href="/tipos_solicitudes"> <img src="{{ URL::to('/img/tipos de solicitudes.png') }}" height="140"></a>
            <h3 style="color: #3b557a">Tipos de solicitudes</h3>
        </div>
        <div class="col-md-4" aling="center">
            <br>
            <a href="/parametros_gen"> <img src="{{ URL::to('/img/parametros.png') }}" height="140"></a>
            <h3 style="color: #3b557a">Parametros Generales</h3>
        </div>

        <!--<div class="col-md-3" aling="center">
            <br>
            <a  href='/usuarios'> <img  src="{{ URL::to('/img/usuarios.png') }}" height="140"></a>
        </div>
        <div class="col-md-3" aling="center">
            <br>
            <a  href='roles'> <img  src="{{ URL::to('/img/roles.png') }}" height="140"></a>
        </div>-->

    </div>

</div>
<div id="footer-lafedar"></div>

@stop