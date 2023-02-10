@extends('equipos_mant.layouts.layout')
@section('content')

<!-- alertas -->

<div class="content">
  <div class="row" style="justify-content: center">
    <div id="alert" class="alert alert-success col-md-10 text-center" style="display: none"></div>
  </div>
</div>

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

<!-- barra para buscar equipos -->

<!-- tabla de datos -->

<div class="col-md-12">             
  <table class="table table-striped table-bordered ">
    <thead>
      <th class="text-center">ID</th>
      <th class="text-center">Tipo</th>
      <th class="text-center">Marca</th>
      <th class="text-center">Modelo</th>
      <th class="text-center">Descripcion</th>
      <th class="text-center">Nro de Serie</th>
      <th class="text-center">Area</th>     
      <th class="text-center">Localizacion</th>
      <th class="text-center">Uso</th>
    </thead>
    <tbody>
      @foreach($equipos_mant as $equipo_mant)
        <tr class="text-center">
          <td width="60">{{$equipo_mant->id}}</td>
          <td width="200">{{$equipo_mant->nombre_tipo}}</td>
          <td width="160">{{$equipo_mant->marca}}</td>
          <td width="160">{{$equipo_mant->modelo}}</td>
          <td>{{$equipo_mant->descripcion}}</td>
          <td>{{$equipo_mant->num_serie}}</td>
          <td>{{$equipo_mant->area}}</td>
          <td>{{$equipo_mant->localizacion}}</td>
          @if($equipo_mant->uso == 1)
            <td width="60"><div class="circle_green"></div></td>
          @else
            <td width="60"><div class="circle_grey"></div></td>
          @endif
        </tr>
      @endforeach
    </tbody>       
  </table>   

  @include('solicitudes.edit')

@stop