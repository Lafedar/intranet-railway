@extends('layouts.app')

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@section('content')

<div class="container text-center">

    <br><br><br>

    <div class="row">
        <div class="col-md-12"></div>

        <br><br>

        <div class="col-md-4">
            <a href="/solicitudes"> <img src="{{ URL::to('/img/solicitudes.png') }}" height="140"></a>
            <h2 style="color: #3b557a">Solicitudes</h2>
        </div>

        @role('administrador|Jefe-GarantiaDeCalidad|Jefe-Mantenimiento|Empleado-Mantenimiento')

        <div class="col-md-4">
            <a href="/equipos_mant"> <img src="{{ URL::to('/img/equipos.png') }}" height="140"></a>
            <h2 style="color: #3b557a">Equipos</h2>
        </div>
        @endrole
        @role('administrador|Jefe-Mantenimiento|rrhh')
        <div class="col-md-4">
            <a href="/parametros_mantenimiento"> <img src="{{ URL::to('/img/parametros.png') }}" height="140"></a>
            <h2 style="color: #3b557a">Parametros</h2>
        </div>
        @endrole
    </div>

</div>
<div id="footer-lafedar"></div>

@stop
{{--A esta vista la llamo en HomeController.php--}}