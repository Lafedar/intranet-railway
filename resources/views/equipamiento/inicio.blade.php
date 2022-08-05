@extends('equipamiento.layouts.layout')
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
          <label><h6>ID:</h6></label>
          <input type="text" name="equipo" class="form-control col-md-1" id="equipo" autocomplete="off" value="{{$equipo}}" >
          &nbsp
          <label><h6>Puesto:</h6></label>
          <input type="text" name="puesto" class="form-control" id="puesto"  autocomplete="off" value="{{$puesto}}" >
          &nbsp
          <label><h6>Usuario:</h6></label>
          <input type="text" name="usuario" class="form-control col-md-1" id="usuario" autocomplete="off" value="{{$usuario}}" >
          &nbsp
          <label><h6>Area:</h6></label>
          <input type="text" name="area" class="form-control" id="area" autocomplete="off" value="{{$area}}" >
          &nbsp
          <label><h6>IP:</h6></label>
          <input type="text" name="ip" class="form-control col-md-1" id="ip" autocomplete="off" value="{{$ip}}" >
          &nbsp
          <div class="form-group">
            <select class="form-control" name="tipo"  id="tipo">
                <option value="0">{{'Todos'}} </option>
                @foreach($tipo_equipamiento as $tipo_equipamiento)
                @if($tipo_equipamiento->id == $tipo)
                <option value="{{$tipo_equipamiento->id}}" selected>{{$tipo_equipamiento->equipamiento}} </option>
                @endif
                <option value="{{$tipo_equipamiento->id}}">{{$tipo_equipamiento->equipamiento}} </option>
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
        <th class="text-center">ID</th>
        <th class="text-center">Puesto</th>
        <th class="text-center">Usuario</th>
        <th class="text-center">Area</th>
        <th class="text-center">IP</th>
        <th class="text-center">Observaciones</th>
        @can('editar-equipamiento')
        <th class="text-center">Acciones</th>
        @endcan
    </thead>  
    <tbody>
        @if(count($equipamientos))
        @foreach($equipamientos as $equipamiento) 
        <tr>
            <td align="center" width="60">{{$equipamiento->id_equipamiento}}</td>
            <td>{{$equipamiento->puesto}}</td>
            <td>{{$equipamiento->nombre .' '. $equipamiento->apellido}}</td>
            <td>{{$equipamiento->area}}</td>
            <td width="110">{{$equipamiento->ip}}</td>
            <td>{{$equipamiento->obs}}</td>
            @can('editar-equipamiento')
            <td align="center" width="140">

                <a href="#" class="btn btn-info btn-sm"  data-toggle="modal" data-id="{{$equipamiento->id_equipamiento}}" data-ip="{{$equipamiento->ip}}" data-marca="{{$equipamiento->marca}}" data-modelo="{{$equipamiento->modelo}}" data-tipo="{{$equipamiento->tipo}}" data-num_serie="{{$equipamiento->num_serie}}" data-procesador="{{$equipamiento->procesador}}" data-disco="{{$equipamiento->disco}}" data-memoria="{{$equipamiento->memoria}}" data-pulgadas="{{$equipamiento->pulgadas}}" data-toner="{{$equipamiento->toner}}" data-unidad_imagen="{{$equipamiento->unidad_imagen}}" data-obs="{{$equipamiento->obs}}" data-oc="{{$equipamiento->oc}}" data-target="#editar_equipamiento" type="submit">Editar</a>

                @if ($equipamiento->relacion != null)
                <a href="{{url('destroy_relacion', $equipamiento->relacion)}}" class="btn btn-danger btn-sm" title="Borrar" onclick="return confirm ('Está seguro que desea eliminar la relación?')"data-position="top" data-delay="50" data-tooltip="Borrar"> X</a>
                @else
                <a href="#" class="btn btn-success btn-sm" title="Asignar" data-id="{{$equipamiento->id_equipamiento}}" data-toggle="modal" data-target="#asignar">+</a>
                @endif
                <a  href="#" class="btn btn-info btn-sm" data-id="{{$equipamiento->id_equipamiento}}" data-toggle="modal" data-target="#ver_s">Soft</a>

                <a  href="#" class="btn btn-warning btn-sm" data-id="{{$equipamiento->id_equipamiento}}" data-toggle="modal" data-target="#incidente">!</a>
            </td>
            @endcan
            
        </tr>                    
        @endforeach  
        @endif  
    </tbody>
</table>


{{ $equipamientos->appends($_GET)->links() }}

</div>
@include('incidentes.create_incidente')

@include('equipamiento.edit')

@include('equipamiento.asignar')

@include('equipamiento.asingn_soft')

<script> 
    $("document").ready(function(){
        setTimeout(function(){
         $("div.alert").fadeOut();
    }, 5000 ); // 5 secs

    });
</script>

<script>
  $('#incidente').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var id = button.data('id') 
    var modal = $(this)

    modal.find('.modal-body #equipamiento').val(id);
    
})
</script>

<script>
  $('#asignar').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var id = button.data('id') 
    var modal = $(this)

    modal.find('.modal-body #equipamiento').val(id);

    $.get('select_puesto',function(data){
      var html_select = '<option value="">Seleccione </option>'
      for(var i = 0; i<data.length; i ++){
        if(data[i].activo ==1){
        if(data[i].nombre_p == null){
        html_select += '<option value ="'+data[i].id_puesto+'">'+data[i].desc_puesto+'</option>';  
        }
        else{
        html_select += '<option value ="'+data[i].id_puesto+'">'+data[i].desc_puesto +' - '+data[i].apellido+' '+data[i].nombre_p+'</option>';
        }
        }}
    $('#select_puesto').html(html_select);
});
    
})
</script>

<script>
  $('#editar_equipamiento').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var id = button.data('id')
    var ip = button.data('ip')
    var marca = button.data('marca')
    var modelo = button.data('modelo')
    var tipo = button.data('tipo')
    var num_serie = button.data('num_serie')
    var procesador = button.data('procesador')
    var disco = button.data('disco')
    var memoria = button.data('memoria')
    var pulgadas = button.data('pulgadas')
    var toner = button.data('toner')
    var unidad_imagen = button.data('unidad_imagen')
    var obs = button.data('obs')
    var oc = button.data('oc')
    var modal = $(this)

    modal.find('.modal-body #id_e').val(id);
    modal.find('.modal-body #ip').val(ip);
    modal.find('.modal-body #marca').val(marca);
    modal.find('.modal-body #modelo').val(modelo);
    modal.find('.modal-body #num_serie').val(num_serie);
    modal.find('.modal-body #procesador').val(procesador);
    modal.find('.modal-body #disco').val(disco);
    modal.find('.modal-body #memoria').val(memoria);
    modal.find('.modal-body #pulgadas').val(pulgadas);
    modal.find('.modal-body #toner').val(toner);
    modal.find('.modal-body #unidad_imagen').val(unidad_imagen);
    modal.find('.modal-body #obs').val(obs);
    modal.find('.modal-body #oc').val(oc);

    $.get('select_tipo_equipamiento',function(data){
      var html_select = '<option value="">Seleccione </option>'
      for(var i = 0; i<data.length; i ++){
        if(data[i].id == tipo){
        html_select += '<option value ="'+data[i].id+'"selected>'+data[i].equipamiento+'</option>';
        }else{
        html_select += '<option value ="'+data[i].id+'">'+data[i].equipamiento+'</option>';
        }
        }
    $('#tipo_equipamiento_editar').html(html_select);
});
})
</script>

<script>
  $('#ver_s').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var id = button.data('id') 
    var modal = $(this)

    modal.find('.modal-body #equipamiento').val(id);

$.get('select_soft/',function(data){
var html_select = '<option value=""> Seleccione </option>'
for (var i = 0; i<data.length; i++){
  html_select += '<option value ="'+data[i].id_s+'"selected>'+data[i].Software+' - '+data[i].Version+'</option>';
}
$('#ssoftware').html(html_select);
});
  
})
</script>



@stop