@extends('medico.layouts.layout')
@section('content')

@if(Session::has('message'))
<div class="content" id="div.alert">
  <div class="row">
    <div class="col-1"></div>
    <div class="alert {{Session::get('alert-class')}} col-10 text-center" role="alert">
     {{Session::get('message')}}
   </div>
 </div>
</div>
@endif

<div class="col-md-12 ml-auto">
  <h1>
    <div class="form-inline pull-right">
      <form route="{{ 'medico.index'}}" method="GET">
        <div class="form-group">
          <div class="form-group"><h6>Paciente:</h6>
            <input type="text" name="paciente" class="form-control" id="paciente" value="{{$paciente}}" >
          </div>
          &nbsp
          <div class="form-group"><h6>Fecha:</h6>
            <input type="date" name="fecha" class="form-control" step="1" min="2019-01-01" value="{{$fecha}}">
          </div>
          &nbsp
          <button type="submit" class="btn btn-default">Buscar consulta</button>
        </form>
      </div>
    </h1>            
  </div>

  <div class="col-md-12">             
    <table class="table table-striped table-bordered d-print-none">
      <thead>
       <th class="text-center">Paciente</th>
       <th class="text-center ">Fecha de consulta</th>
       <th class="text-center">Motivo</th>
       <th class="text-center">Peso</th>
       <th class="text-center">Talla</th>
       <th class="text-center">Tension</th>
       <th class="text-center">IMC</th>
       <th class="text-center">Acciones</th>
     </thead>  
     <tbody>
      @if(count($consultas))
      @foreach($consultas as $consulta) 
      <tr>
        <td > {{$consulta->apellido_paciente.' '. $consulta->nombre_paciente}}</td>
        <td align="center"> {!! \Carbon\Carbon::parse($consulta->fecha)->format("d-m-Y") !!}</td>
        <td align="center"> {{$consulta->motivo}}</td>
        <td align="center"> {{$consulta->peso}}</td>
        <td align="center"> {{$consulta->talla}}</td>
        <td align="center"> {{$consulta->tension}}</td>
        <td align="center"> {{$consulta->imc}}</td>
        <td align="center" width="200">

          <a href="#" class="btn btn-info btn-sm" data-fecha="{!! \Carbon\Carbon::parse($consulta->fecha)->format('d-m-Y') !!}" data-nombre="{{$consulta->nombre_paciente .' '. $consulta->apellido_paciente}}" data-motivo="{{$consulta->motivo}}" data-peso="{{$consulta->peso}}" data-talla="{{$consulta->talla}}" data-tension="{{$consulta->tension}}" data-imc="{{$consulta->imc}}" data-obs="{{$consulta->obs}}" data-toggle="modal" data-target="#ver"> Ver</a>
          <a href="{{route('medico.edit', $consulta->id)}}" class="btn btn-info btn-sm" data-position="top" data-delay="50" data-tooltip="Ver"> Editar</a>
          <a href="{{url('reporte_medico', $consulta->ip_paciente)}}" class="btn btn-info btn-sm"  data-position="top" data-delay="50" data-tooltip="Reporte">Reporte</a>
        </td>
      </tr>                    
      @endforeach  
      @endif  
      
    </tbody>
  </table>

@include('medico.show')
  
{{ $consultas->appends($_GET)->links() }}

</div>
<script>
  $('#ver').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var nombre = button.data('nombre') 
    var fecha = button.data('fecha') 
    var apellido = button.data('apellido')
    var motivo = button.data('motivo')
    var peso = button.data('peso')
    var talla = button.data('talla')
    var tension = button.data('tension')
    var imc = button.data('imc')
    var obs = button.data('obs')
    var modal = $(this)

    modal.find('.modal-body #nombre').val(nombre);
    modal.find('.modal-body #fecha').val(fecha);
    modal.find('.modal-body #apellido').val(apellido);
    modal.find('.modal-body #motivo').val(motivo);
    modal.find('.modal-body #peso').val(peso);
    modal.find('.modal-body #talla').val(talla);
    modal.find('.modal-body #tension').val(tension);
    modal.find('.modal-body #imc').val(imc);
    modal.find('.modal-body #obs').val(obs);
  })
</script>

<script> 
  $("document").ready(function(){
    setTimeout(function(){
     $("div.alert").fadeOut();
    }, 5000 ); // 5 secs

  });
</script>

@stop