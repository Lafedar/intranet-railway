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

<!-- Barra de busqueda -->
<div class="col">
  <div class="form-group">
    <form  method="GET">
      <div style="display: inline-block;">
        <label for="equipo" style="display: block; margin-bottom: 5px;"><h6>ID:</h6></label>
        <input type="text" name="equipo" class="form-control" id="equipo" autocomplete="off" value="{{$equipo}}" >
      </div>
      <div style="display: inline-block;">
        <label for="usuario" style="display: block; margin-bottom: 5px;"><h6>Usuario:</h6></label>
        <input type="text" name="usuario" class="form-control" id="usuario" autocomplete="off" value="{{$usuario}}" >
      </div>
      <div style="display: inline-block;">
        <label for="puesto" style="display: block; margin-bottom: 5px;"><h6>Puesto:</h6></label>
        <input type="text" name="puesto" class="form-control" id="puesto"  autocomplete="off" value="{{$puesto}}" >
      </div>
      <div style="display: inline-block;">
        <label for="area" style="display: block; margin-bottom: 5px;"><h6>Area:</h6></label>
        <input type="text" name="area" class="form-control" id="area" autocomplete="off" value="{{$area}}" >
      </div>
      <div style="display: inline-block;">
        <label for="ip" style="display: block; margin-bottom: 5px;"><h6>IP:</h6></label>
        <input type="text" name="ip" class="form-control" id="ip" autocomplete="off" value="{{$ip}}" >
      </div>
      <div style="display: inline-block;">
        <label for="tipo" style="display: block; margin-bottom: 5px;"><h6>Tipo:</h6></label>
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
      <div style="display: inline-block;">
        <label for="subred" style="display: block; margin-bottom: 5px;"><h6>Subred:</h6></label>
        <select class="form-control" name="subred"  id="subred">
          <option value="0">{{'Todos'}} </option>
          @foreach($ips as $ips)
            @if($ips->id == $subred)
              <option value="{{$ips->id}}" selected>{{$ips->nombre}} </option>
            @endif
            <option value="{{$ips->id}}">{{$ips->nombre}} </option>
          @endforeach
        </select> 
      </div>         
      &nbsp
      <div style="display: inline-block;">
        <button type="submit" class="btn btn-default"> Buscar</button>
      </div>
    </form>
  </div>         
</div>

<div class="col-md-12">             
  <table class="table table-striped table-bordered ">
    <thead>
      <th class="text-center">ID</th>
      <th class="text-center">Usuario</th>
      <th class="text-center">Puesto</th>
      <th class="text-center">Localizacion</th>
      <th class="text-center">Area</th>
      <th class="text-center">Sub Red</th>
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
            <td>{{$equipamiento->nombre .' '. $equipamiento->apellido}}</td>
            <td width="available">{{$equipamiento->puesto}}</td>
            <td>{{$equipamiento->localizacion}}</td>
            <td>{{$equipamiento->area}}</td>
            <td width="110">{{$equipamiento->nombre_subred}}</td>
            <td width="110">{{$equipamiento->ip}}</td>
            <td width="min-content">{{$equipamiento->obs}}</td>
            @can('editar-equipamiento')
              <td align="center" width="170">
                <div class="botones">
                  @if ($equipamiento->relacion != null)
                    <!-- Boton para eliminar asignacion de equipo -->
                    <a role="button"  class="fa-solid fa-xmark eliminar" href="{{url('destroy_relacion', $equipamiento->relacion)}}"  title="Borrar" 
                    onclick="return confirm ('¿Está seguro que desea eliminar la relación?')"data-position="top" data-delay="50" data-tooltip="Borrar"> </a>

                  @else
                  <!-- Boton para asignar equipo -->
                    <a role="button"  class="fa-solid fa-plus agregar" href="#"  title="Asignar" data-id="{{$equipamiento->id_equipamiento}}" data-toggle="modal" 
                    data-target="#asignar"></a>

                  @endif   
                    <!-- Boton para editar equipo -->
                    <a role="button" class="fa-solid fa-pen default" href="#" title="Editar" data-toggle="modal" data-id="{{$equipamiento->id_equipamiento}}" 
                    data-ip="{{$equipamiento->ip}}" data-marca="{{$equipamiento->marca}}" data-modelo="{{$equipamiento->modelo}}" data-tipo="{{$equipamiento->tipo}}" 
                    data-num_serie="{{$equipamiento->num_serie}}" data-procesador="{{$equipamiento->procesador}}" data-disco="{{$equipamiento->disco}}" 
                    data-memoria="{{$equipamiento->memoria}}" data-pulgadas="{{$equipamiento->pulgadas}}" data-toner="{{$equipamiento->toner}}" 
                    data-unidad_imagen="{{$equipamiento->unidad_imagen}}" data-obs="{{$equipamiento->obs}}" data-oc="{{$equipamiento->oc}}" 
                    data-subred="{{$equipamiento->subred}}" data-target="#editar_equipamiento" type="submit"></a>
                    
                    <!-- Boton para agregar software a equipo -->
                    <a role="button" class="fa-solid fa-gear default" href="#" title="Software" data-id="{{$equipamiento->id_equipamiento}}" data-toggle="modal" 
                    data-target="#ver_s"></a>
                    
                    <!-- Boton para agregar un incidente al equipo -->
                    <a role="button" class="fa-solid fa-exclamation default" href="#" title="Incidente" data-id="{{$equipamiento->id_equipamiento}}" data-toggle="modal" 
                    data-target="#incidente"></a>
                </div>  
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
      for(var i = 0; i<data.length; i ++)
      {
        if(data[i].nombre_p == null)
        {
          html_select += '<option value ="'+data[i].id_puesto+'">'+data[i].desc_puesto+'</option>';  
        }
        else
        {
        html_select += '<option value ="'+data[i].id_puesto+'">'+data[i].desc_puesto +' - '+data[i].apellido+' '+data[i].nombre_p+'</option>';
        }
      }
    $('#select_puesto').html(html_select);
});
    
})
</script>

<script>
  $('#editar_equipamiento').on('show.bs.modal', function (event) 
  {
    var button = $(event.relatedTarget) 
    var id = button.data('id')
    var ip = button.data('ip')
    var marca = button.data('marca')
    var modelo = button.data('modelo')
    var tipo = button.data('tipo')
    var subred = button.data('subred')
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

    let ip_dividida = ip.split('.');
    ip = ip_dividida[3];

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
    //desplegar select de tipo de equipo en editar equipamiento 
    $.get('select_tipo_equipamiento',function(data)
    {
      var html_select = '<option value="">Seleccione </option>'
      for(var i = 0; i<data.length; i ++)
      {
        if(data[i].id == tipo)
        {
          html_select += '<option value ="'+data[i].id+'"selected>'+data[i].equipamiento+'</option>';
        }
        else
        {
          html_select += '<option value ="'+data[i].id+'">'+data[i].equipamiento+'</option>';
        }
      }
      $('#tipo_equipamiento_editar').html(html_select);
    });
    //desplegar select de subred en editar equipamiento 
    $.get('select_ips',function(data)
    {
      var html_select = '<option value="">Seleccione </option>'
      var html_select2 = '<option value="">Seleccione </option>'
      for(var i = 0; i<data.length; i ++)
      {
        let ip = data[i].puerta_enlace.split('.');
        if(data[i].id == subred)
        {
          html_select += '<option value ="'+data[i].id+'"selected>'+data[i].nombre+'</option>';
          html_select2 += '<option value ="'+data[i].id+'"selected>'+ip[0]+'.'+ip[1]+'.'+ip[2]+'.'+'</option>';
        }
        else
        {
          html_select += '<option value ="'+data[i].id+'">'+data[i].nombre+'</option>';
          html_select2 += '<option value ="'+data[i].id+'">'+ip[0]+'.'+ip[1]+'.'+ip[2]+'.'+'</option>';
        }
      }
      //al cambiar un dato de un select se cambia en el otro 
      $("#ips_editar").on("change", () => {
      $("#id_red_editar").val($("#ips_editar").val());
      });

      $("#id_red_editar").on("change", () => {
      $("#ips_editar").val($("#id_red_editar").val());
      });

      //envia opciones de select a la vista edit.blade.php
      $('#ips_editar').html(html_select);
      $('#id_red_editar').html(html_select2);

    });
  });
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

<script>
 $(document).ready(function(){
   $("#equipo").keyup(function(){
     _this = this;
     $.each($("#test tbody tr"), function() {
       if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
         $(this).hide();
       else
         $(this).show();
     });
   });
 });
</script>

@stop