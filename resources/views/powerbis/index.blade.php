@extends('powerbis.layouts.layout')
@section('content')

<div class="container-fluid h-200 mt-n5">
    <div class="row justify-content-center align-items-center h-100">
        <div class="col-md-12">
            <div class="container text-center">
                <br><br><br>
                <div class="row">
                    @foreach($rolesUsuario as $rol)
                        @if($rol === 'administrador')
                            <div class="col-md-4 mb-4">
                                <a href="/ventas"> <img src="{{ URL::to('/img/ventas.png') }}" height="140"></a>
                            </div>
                            <div class="col-md-4 mb-4">
                                <a href="/costos"> <img src="{{ URL::to('/img/costos.png') }}" height="140"></a>
                            </div>
                            <div class="col-md-4 mb-4">
                                <a href="/compras"> <img src="{{ URL::to('/img/compras.png') }}" height="140"></a>
                            </div>
                            <div class="col-md-4 mb-4">
                                <a href="/producciones"> <img src="{{ URL::to('/img/produccion.png') }}" height="140"></a>
                            </div>
                            <div class="col-md-4 mb-4">
                                <a href="/calidades"> <img src="{{ URL::to('/img/calidad.png') }}" height="140"></a>
                            </div>
                            <div class="col-md-4 mb-4">
                                <a href="/rrhhs"> <img src="{{ URL::to('/img/rrhh.png') }}" height="140"></a>
                            </div>
                            @break;
                        @elseif($rol === 'venta')
                            <div class="col-md-4 mb-4">
                                <a href="/ventas"> <img src="{{ URL::to('/img/ventas.png') }}" height="140"></a>
                            </div>
                        @elseif($rol === 'costo')
                            <div class="col-md-4 mb-4">
                                <a href="/costos"> <img src="{{ URL::to('/img/costos.png') }}" height="140"></a>
                            </div>
                        @elseif($rol === 'compra')
                            <div class="col-md-4 mb-4">
                                <a href="/compras"> <img src="{{ URL::to('/img/compras.png') }}" height="140"></a>
                            </div>
                        @elseif($rol === 'produccion')
                            <div class="col-md-4 mb-4">
                                <a href="/producciones"> <img src="{{ URL::to('/img/produccion.png') }}" height="140"></a>
                            </div>
                        @elseif($rol === 'calidad')
                            <div class="col-md-4 mb-4">
                                <a href="/calidades"> <img src="{{ URL::to('/img/calidad.png') }}" height="140"></a>
                            </div>
                        @elseif($rol === 'rrhh')
                            <div class="col-md-4 mb-4">
                                <a href="/rrhhs"> <img src="{{ URL::to('/img/rrhh.png') }}" height="140"></a>
                            </div>
                        @elseif($rol != 'administrador' && $rol != 'costo' && $rol != 'compra' && $rol != 'produccion' && $rol != 'calidad' && $rol != 'rrhh' && $rol != 'venta')
                            <div style="text-align: center; width: 90%;">
                                <h1 style="color: red;">No tiene ningún permiso</h1>
                                <h3>Consulte con un administrador</h3>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@stop

