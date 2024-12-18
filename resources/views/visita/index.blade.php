@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> 

<div class="container-fluid">
  @if(Session::has('message'))
    <div class="container" id="div.alert">
    <div class="row">
      <div class="col-1"></div>
      <div class="alert {{Session::get('alert-class')}} col-10 text-center" role="alert">
      {{Session::get('message')}}
      </div>
    </div>
    </div>
  @endif

  <div class="container" id="guardia-container">

    <div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-8">
        <h2 class="headertekst" align="center">Control de ingresos</h2>
        <hr>
      </div>
    </div>

    <div class="container text-center">
      <br><br>
      <div class="row">
        <div class="col-md-12"></div>
        <br><br>

        <div class="col-md-4" align="right">
          <a href=/asignar> <img src="{{ URL::to('/img/asignar.png') }}" height="150"></a>
        </div>

        <div class="col-md-4">
          <a href=# data-toggle="modal" data-target="#baja"> <img src="{{ URL::to('/img/baja.png') }}" height="150">
          </a>
        </div>

        <div class="col-md-4" align="left">
          <a href=/consulta> <img src="{{ URL::to('/img/consulta.png') }}" height="150"> </a>
        </div>
      </div>
      <a href="/listado" class="btn btn-info" data-position="top" data-delay="50" title="Listado de personas"
        id="btn-agregar" style="margin-top: 50px;"><b>Visitantes</b></a>
    </div>
    <div id="footer-lafedar"></div>

    @include('visita.baja')

  </div>

  <script>
    $("document").ready(function () {
      setTimeout(function () {
        $("div.alert").fadeOut();
      }, 3000);

    });
  </script>