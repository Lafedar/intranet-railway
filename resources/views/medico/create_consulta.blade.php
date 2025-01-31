@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<div id="medico-container">
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <h4 class="headertekst" align="center">Nueva consulta</h4>
      <hr>
    </div>
  </div>
  
  <div class="row"> 
    <div class="col-md-3 field-label-responsive"></div>
    <div class="col-md-6">
      <div class="form-group has-danger">
        <form action="{{ action('MedicoController@store') }}" method="POST">
          {{csrf_field()}}
          <input type="hidden" name="id" id="id" value="">

          <div class="col-md-5" >Fecha de consulta:
            <input type="date" name="fecha"  class="form-control" step="1" min="2019-01-01" value="<?php echo date("Y-m-d");?>">
          </div>
          <div class="container">
            <div class="row">
              <div class="input-field col-3" align="center">Peso:
                <input type="text" class="form-control" name="peso" id="peso" autocomplete="off" ></input>
              </div>
              <div class="input-field col-3 " align="center">Talla
                <input type="text" class="form-control"  name="talla" id="talla" autocomplete="off" ></input>
              </div>
              <div class="input-field col-3 " align="center">Tension
                <input type="text" class="form-control"  name="tension" id="tension" autocomplete="off"></input>
              </div>
              <div class="input-field col-3 " align="center">IMC
                <input type="text" class="form-control"  name="imc" id="imc" autocomplete="off"></input> 
              </div>
            </div>
          </div>
          <div class="input-field col s12 ">Paciente:
            <select class="form-control" name="paciente"  id="paciente" required>
              @foreach($personas as $personas)
              <option value="{{$personas->id_p}}">{{$personas->apellido}}&nbsp{{$personas->nombre_p}} </option>
              @endforeach
            </select>
          </div>

          <div class="input-field col s12 ">Motivo:
            <select class="form-control" name="motivo"  id="motivo"  required>
              @foreach($motivos as $motivo)
              <option value="{{$motivo->id}}">{{$motivo->desc_motivo}} </option>
              @endforeach
            </select>
            <a href=# data-toggle="modal" data-target="#añadir_motivo"> Añadir motivo</a>
          </div>

          <div class="input-field col s12 ">Observación:
            <textarea class="form-control" rows="3" name="observacion" id="observacion" required></textarea>
          </div>
          <p></p>         
          <div class="row">
            <div class="col-md-3 field-label-responsive"></div>
            <div class="col-md-1"></div>
            <a class="btn btn-secondary " href="{{ URL::previous() }}" id="asignar-btn">Cancelar</a>
            &nbsp
            <button type="subitm" class="btn btn-info" id="asignar-btn">Guardar</button>
          </div>
        </div>
        <p></p>
      </form>
    </div>
  </div>

  


@include('medico.modal_añadir_motivo')
 
