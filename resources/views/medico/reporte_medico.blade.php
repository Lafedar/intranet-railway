@extends('medico.layouts.layout')
@section('content')

<h2 class="text-center" font style="text-transform: uppercase;">{{$historia_clinica->apellido_paciente . ' ' . $historia_clinica->nombre_paciente}}</h2>

<div class="container col-11">
  <br>

  <div class="row">
    <div class="col-md-2 table-bordered">
      <h5 >Grupo sanguineo</h5>
      <p class="text-center">{{$historia_clinica->grupo_sang}}</p>
    </div>
    <div class="col-md-2 table-bordered">
      <h5 >Educación</h5>
      <p class="text-center">{{$historia_clinica->educacion}}</p>
    </div>
    <div class="col-md-2 table-bordered">
      <h5 >Tabaco</h5>
      @if($historia_clinica->tabaco == 1)
      <p class="text-center">{{'Si'}}</p>
      @else
      <p class="text-center">{{'No'}}</p>
      @endif
    </div>
    <div class="col-md-2 table-bordered">
      <h5>Alcohol</h5>
      @if($historia_clinica->alcohol == 1)
      <p class="text-center">{{'Si'}}</p>
      @else
      <p class="text-center">{{'No'}}</p>
      @endif
    </div>
    <div class="col-md-2 table-bordered">
      <h5 >Droga</h5>
      @if($historia_clinica->droga == 1)
      <p class="text-center">{{'Si'}}</p>
      @else
      <p class="text-center">{{'No'}}</p>
      @endif
    </div>
    <div class="col-md-2 table-bordered">
      <h5>Act física</h5>
      @if($historia_clinica->act_fisica == 1)
      <p class="text-center">{{$historia_clinica->desc_act_fisica}}</p>
      @else
      <p class="text-center">{{'No'}}</p>
      @endif
    </div>

    <div class="col-md-6 table-bordered">
      <h5>Antecedentes personales</h5>
      <p class="text-center">{{$historia_clinica->ant_per}}</p>
    </div>
    <div class="col-md-6 table-bordered">
      <h5 >Antecedentes familiares</h5>
      <p class="text-center">{{$historia_clinica->ant_fam}}</p>
    </div>
    <div class="col-md-6 table-bordered">
      <h5>Antecedentes quirurjicos</h5>
      <p class="text-center">{{$historia_clinica->ant_quir}}</p>
    </div>

    <div class="col-md-6 table-bordered">
      <h5 >Observaciones</h5>
      <p class="text-center">{{$historia_clinica->ant_quir}}</p>
    </div>
  </div>
</div>

<div class="container col-11">
  <br>
  <br>
  <h5  class="text-center" font style="text-transform: uppercase;">Consultas realizadas</h5>

  <div class="row">
    <table class="table table-striped table-bordered d-print-none">
      <thead>
       <th class="text-center ">Fecha de consulta</th>
       <th class="text-center">Motivo</th>
       <th class="text-center">Peso</th>
       <th class="text-center">Talla</th>
       <th class="text-center">Tension</th>
       <th class="text-center">IMC</th>
       <th class="text-center">Observacion</th>
     </thead>  
     <tbody>
      @if(count($consultas))
      @foreach($consultas as $consulta) 
      <tr>
        <td class="text-center"> {!! \Carbon\Carbon::parse($consulta->fecha)->format("d-m-Y") !!}</td>
        <td class="text-center"> {{$consulta->motivo}}</td>
        <td class="text-center"> {{$consulta->peso}}</td>
        <td class="text-center"> {{$consulta->talla}}</td>
        <td class="text-center"> {{$consulta->tension}}</td>
        <td class="text-center"> {{$consulta->imc}}</td>
        <td class="text-center"> {{$consulta->obs}}</td>
      </tr>                    
      @endforeach  
      @endif  
    </tbody>
  </table>
</div>
</div>

@stop