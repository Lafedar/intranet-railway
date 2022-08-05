@extends('medico.layouts.layout')
@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <h4 class="headertekst" align="center">Editar consulta</h4>
      <hr>
    </div>
  </div>
  
  <div class="row"> 
    <div class="col-md-3 field-label-responsive"></div>
    <div class="col-md-6">
      <div class="form-group has-danger">
        <form action="{{route('medico.update' , $consulta_med->id)}}" method="POST">
          {{ method_field('PUT')}} {{csrf_field()}}
          <input type="hidden" name="id" id="id" value="{{{ isset($consulta_med->id) ? $consulta_med->id : ''}}}">

          <div class="col-md-5" >Fecha de consulta:
            <input type="date" name="fecha"  class="form-control" step="1" min="2019-01-01" value="{{{ isset($consulta_med->fecha) ? $consulta_med->fecha : ''}}}">
          </div>
          <div class="container">
            <div class="row">
              <div class="input-field col-3" align="center">Peso:
                <input type="text" class="form-control" name="peso" id="peso" value="{{{ isset($consulta_med->peso) ? $consulta_med->peso : ''}}}" autocomplete="off" ></input>
              </div>
              <div class="input-field col-3 " align="center">Talla
                <input type="text" class="form-control"  name="talla" id="talla" value="{{{ isset($consulta_med->talla) ? $consulta_med->talla : ''}}}" autocomplete="off" ></input>
              </div>
              <div class="input-field col-3 " align="center">Tension
                <input type="text" class="form-control"  name="tension" id="tension" value="{{{ isset($consulta_med->tension) ? $consulta_med->tension : ''}}}" autocomplete="off"></input>
              </div>
              <div class="input-field col-3 " align="center">IMC
                <input type="text" class="form-control"  name="imc" id="imc" value="{{{ isset($consulta_med->imc) ? $consulta_med->imc : ''}}}" autocomplete="off"></input> 
              </div>
            </div>
          </div>
          <div class="input-field col s12 ">Paciente:
            <select class="form-control" name="paciente"  id="paciente" required>
              @foreach($personas as $persona)
              @if($persona->id_p == $consulta_med->paciente)
              <option value="{{$persona->id_p}}" selected="">{{$persona->apellido}}&nbsp{{$persona->nombre_p}} </option>
              @else
              <option value="{{$persona->id_p}}">{{$persona->apellido}}&nbsp{{$persona->nombre_p}} </option>
              @endif
              @endforeach
            </select>
          </div>

          <div class="input-field col s12 ">Motivo:
            <select class="form-control" name="motivo"  id="motivo"  required>
              @foreach($motivos as $motivo)
              @if($motivo->id == $consulta_med->motivo)
              <option value="{{$motivo->id}}" selected="">{{$motivo->desc_motivo}} </option>
              @else
              <option value="{{$motivo->id}}">{{$motivo->desc_motivo}} </option>
              @endif
              @endforeach
            </select>
            <a href=# data-toggle="modal" data-target="#añadir_motivo"> Añadir motivo</a>
          </div>

          <div class="input-field col s12 ">Observación:
            <textarea class="form-control" rows="3" name="observacion" id="observacion" required> {{{ isset($consulta_med->obs) ? $consulta_med->obs : ''}}}</textarea>
          </div>
          <p></p>
          <div class="row">
            <div class="col-md-3 field-label-responsive"></div>
            <div class="col-md-1"></div>
            <a class="btn btn-secondary " href="{{ URL::previous() }}">Volver</a>
            &nbsp
            <button type="subitm" class="btn btn-info">Guardar</button>
          </div>
        </div>
        <br>
      </form>
    </div>
  </div>
  <div id="footer-lafedar"></div>
  @include('medico.modal_añadir_motivo')


  @stop