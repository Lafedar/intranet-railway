@extends('permisos.layouts.layout')
@section('content')

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

<div class="col-md-12 ml-auto">
  <h1>
   <div class="form-inline pull-right">
    <form  method="GET">
      <div class="form-group">
        <div class="form-group"><h6>Empleado:</h6>
          <input type="text" name="empleado" class="form-control" id="empleado" value="{{$empleado}}" >
        </div>
        &nbsp
        <div class="form-group">
          <select class="form-control" name="motivo"  id="motivo" value="{{{ isset($tipo_permisos->desc) ? $permisos->motivo : ''}}}">
            <option value="0">{{'Sin motivo'}} </option>
            @foreach($tipo_permisos as $tipo_permiso)
            <option value="{{$tipo_permiso->id_tip}}">{{$tipo_permiso->desc}} </option>
            @endforeach
          </select>                  
        </div>
        &nbsp
        <button type="submit" class="btn btn-default"> Buscar</button>
      </form>
    </div>
  </h1>            
</div>


<div class="col-md-12">             
  <table class="table table-striped table-bordered ">
    <thead>
     <th class="text-center">Empleado</th>
     <th class="text-center">Fecha solicitud</th>
     <th class="text-center">Fecha desde</th>
     <th class="text-center">Fecha hasta</th>
     <th class="text-center">Horario</th>
     <th class="text-center">Motivo</th>
     <th class="text-center">Acciones</th>       
   </thead>  
   <tbody>
    @if(count($permisos))
    @foreach($permisos as $permiso) 
    <tr>
      <td > {{$permiso->nombre_autorizado.' '. $permiso->apellido_autorizado}}</td>
      <td class="text-center"> {!! \Carbon\Carbon::parse($permiso->fecha_permiso)->format("d-m-Y") !!}</td>
      <td class="text-center">{!! \Carbon\Carbon::parse($permiso->fecha_desde)->format("d-m-Y") !!}</td>
      @if( $permiso->fecha_hasta != null)
      <td class="text-center">{!! \Carbon\Carbon::parse($permiso->fecha_hasta)->format("d-m-Y") !!}</td>
      @else
      <td></td>
      @endif
      <td class="text-center" width="100"> {{$permiso->hora_desde . ' a '. $permiso->hora_hasta}}</td>
      <td class="text-center">{{$permiso->motivo}}</td>
      <td align="center" width="95">
       <form action="{{route('destroy_permiso', $permiso->id)}}" method="put">

        <a href="#" class="btn btn-info btn-sm" data-fecha_soli="{!! \Carbon\Carbon::parse($permiso->fecha_permiso)->format('d-m-Y') !!}" data-fecha_desde="{!! \Carbon\Carbon::parse($permiso->fecha_desde)->format('d-m-Y') !!}" data-fecha_hasta="{!! \Carbon\Carbon::parse($permiso->fecha_hasta)->format('d-m-Y') !!}"  data-horario="{{'de '.$permiso->hora_desde . ' a '. $permiso->hora_hasta}}" data-motivo="{{$permiso->motivo}}" data-descripcion="{{$permiso->descripcion}}" data-solicitante="{{$permiso->nombre_autorizado.' '. $permiso->apellido_autorizado}}" data-area="{{$permiso->area}}" data-autorizante="{{$permiso->nombre_autorizante.' '. $permiso->apellido_autorizante}}" data-toggle="modal" data-target="#ver">Ver</a>   

        <button type="submit" class="btn btn-danger btn-sm btn-borrar" data-tooltip="Borrar">X</button>
      </form>
    </td>
  </tr>                    
  @endforeach  
  @endif  
</tbody>
</table>
{{ $permisos->appends($_GET)->links() }}
</div>

@include('permisos.show')    


<script>
  function fnSaveSolicitud() {
    $('#saveButton').prop('disabled', true);
    $('#myForm').submit();
  }

  $('#ver').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var fecha_soli = button.data('fecha_soli')
    var fecha_desde = button.data('fecha_desde')
    var fecha_hasta = button.data('fecha_hasta')
    var horario = button.data('horario')
    var motivo = button.data('motivo')
    var descripcion = button.data('descripcion')
    var solicitante = button.data('solicitante')
    var autorizante = button.data('autorizante')
    var area = button.data('area') 
    var modal = $(this)

    modal.find('.modal-body #fecha_soli').val(fecha_soli);
    modal.find('.modal-body #fecha_desde').val(fecha_desde);
    modal.find('.modal-body #fecha_hasta').val(fecha_hasta);
    modal.find('.modal-body #horario').val(horario);
    modal.find('.modal-body #motivo').val(motivo);
    modal.find('.modal-body #descripcion').val(descripcion);
    modal.find('.modal-body #solicitante').val(solicitante);
    modal.find('.modal-body #autorizante').val(autorizante);
    modal.find('.modal-body #area').val(area);
  })

  $(document).ready(function(){
    $('#alert').hide();
    $('.btn-borrar').click(function(e){
        e.preventDefault();
        if(! confirm("¿Está seguro de eliminar?")){
            return false;
        }
        var row = $(this).parents('tr');
        var form = $(this).parents('form');
        var url  = form.attr('action');       
        
        $.get(url, form.serialize(),function(result){
            row.fadeOut();
            $('#alert').show();
            $('#alert').html(result.message)
            setTimeout(function(){ $('#alert').fadeOut();}, 5000 );
        }).fail(function(){
            $('#alert').show();
            $('#alert').html("Algo salió mal");
        });
    });
});

  $("document").ready(function(){
    setTimeout(function(){
     $("div.alert").fadeOut();
    }, 5000 ); // 5 secs

  });
</script>


@stop