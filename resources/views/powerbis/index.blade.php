@extends('layouts.app')

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">

    <!-- Link to custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

@section('content')

<main class="container-fluid h-200 mt-n5">
    <div class="row justify-content-center align-items-center h-100">
        <div class="col-md-12">
            <section class="text-center">
                

                <div class="row" id="powerbis-conteiner">
                    @foreach($rolesUsuario as $rol)
                        @if($rol === 'administrador')
                            <section class="col-md-4 mb-4">
                                <a href="/ventas" class="d-block">
                                    <img src="{{ URL::to('/img/ventas.png') }}" alt="Ventas" height="140">
                                </a>
                            </section>
                            <section class="col-md-4 mb-4">
                                <a href="/costos" class="d-block">
                                    <img src="{{ URL::to('/img/costos.png') }}" alt="Costos" height="140">
                                </a>
                            </section>
                            <section class="col-md-4 mb-4">
                                <a href="/compras" class="d-block">
                                    <img src="{{ URL::to('/img/compras.png') }}" alt="Compras" height="140">
                                </a>
                            </section>
                            <section class="col-md-4 mb-4">
                                <a href="/producciones" class="d-block">
                                    <img src="{{ URL::to('/img/produccion.png') }}" alt="Producción" height="140">
                                </a>
                            </section>
                            <section class="col-md-4 mb-4">
                                <a href="/calidades" class="d-block">
                                    <img src="{{ URL::to('/img/calidad.png') }}" alt="Calidad" height="140">
                                </a>
                            </section>
                            <section class="col-md-4 mb-4">
                                <a href="/rrhhs" class="d-block">
                                    <img src="{{ URL::to('/img/rrhh.png') }}" alt="RRHH" height="140">
                                </a>
                            </section>
                            @break
                        @elseif($rol === 'venta')
                            <section class="col-md-4 mb-4">
                                <a href="/ventas" class="d-block">
                                    <img src="{{ URL::to('/img/ventas.png') }}" alt="Ventas" height="140">
                                </a>
                            </section>
                        @elseif($rol === 'costo')
                            <section class="col-md-4 mb-4">
                                <a href="/costos" class="d-block">
                                    <img src="{{ URL::to('/img/costos.png') }}" alt="Costos" height="140">
                                </a>
                            </section>
                        @elseif($rol === 'compra')
                            <section class="col-md-4 mb-4">
                                <a href="/compras" class="d-block">
                                    <img src="{{ URL::to('/img/compras.png') }}" alt="Compras" height="140">
                                </a>
                            </section>
                        @elseif($rol === 'produccion')
                            <section class="col-md-4 mb-4">
                                <a href="/producciones" class="d-block">
                                    <img src="{{ URL::to('/img/produccion.png') }}" alt="Producción" height="140">
                                </a>
                            </section>
                        @elseif($rol === 'calidad')
                            <section class="col-md-4 mb-4">
                                <a href="/calidades" class="d-block">
                                    <img src="{{ URL::to('/img/calidad.png') }}" alt="Calidad" height="140">
                                </a>
                            </section>
                        @elseif($rol === 'rrhh')
                            <section class="col-md-4 mb-4">
                                <a href="/rrhhs" class="d-block">
                                    <img src="{{ URL::to('/img/rrhh.png') }}" alt="RRHH" height="140">
                                </a>
                            </section>
                        @elseif($rol != 'administrador' && $rol != 'costo' && $rol != 'compra' && $rol != 'produccion' && $rol != 'calidad' && $rol != 'rrhh' && $rol != 'venta')
                            <section class="col-md-12 text-center">
                                <h2 style="color: red;">No tiene ningún permiso</h2>
                                <p>Consulte con un administrador para obtener más información.</p>
                            </section>
                        @endif
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</main>

@stop

</html>
