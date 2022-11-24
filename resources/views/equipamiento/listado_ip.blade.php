@extends('equipamiento.layouts.layout')
@section('content')

<div class="col-md-12 ml-auto">
  <h1>
  <div class="form-inline pull-right">
    <div class="form-group">
      <!-- <label><h6>Subred:</h6></label>
      <select class="form-control col-md-3" name="ips" id="search2">
        <option value=""> Seleccionar </option>
        <option value="Impresoras"> Impresoras </option>
        <option value="Lan"> Lan </option>
        <option value="PLC"> PLC </option>
        <option value="Terceros Mantenimiento"> Terceros Mantenimiento </option>
        <option value="Wifi Interno"> Wifi Interno </option>
        <option value="Wifi Invitados"> Wifi Invitados </option>
        <option value="Wan Fibertel"> Wan Fibertel </option>
      </select>
      &nbsp -->
      <label><h6>Busqueda general:</h6></label>
      <input type="text" class="form-control col-md-6" id="search1"  autocomplete="off" placeholder="Buscar">
    </div>
  </div>
  </h1>
</div>


<div class="col-sm-12">             
  <table id="test" class="table table-striped table-bordered table-condensed" role="grid" cellspacing="0" cellpadding="2" border="10">
    <thead>
     <th class="text-center">IP</th>
     <th class="text-center">Red</th>
     <th class="text-center">Equipamiento</th>
     <th class="text-center">Tipo</th>
     <th class="text-center">Usuario</th>
     <th class="text-center">Observación</th>  
    </thead>        

    <tbody>
      @for($i=1;$i<1530;$i++) 
        <tr>
          <td align="center">{{$listado[$i][0]}}</td>
          <td align="center">{{$listado[$i][5]}}</td>
          @if($listado[$i][1] == 'Libre')
            <td  style="color:blue" align="center"><strong>{{$listado[$i][1]}}</strong></td>
          @else
            <td align="center">{{$listado[$i][1]}}</td>
          @endif
          <td align="center">{{$listado[$i][2]}}</td>
          <td align="center">{{$listado[$i][3]}}</td>
          <td align="center">{{$listado[$i][4]}}</td>
        </tr>                    
      @endfor 
    </tbody>
  </table>
</div>

<script src="{{ URL::asset('/js/jquery.min.js') }}"></script>

<script>
   $(document).ready(function(){
     $("#search1").keyup(function(){
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


<!--
<script>
  $('#ips_lista').on('show.bs.modal', function (event) {
  $.get('select_ips/',function(data)
    {
      var html_select = '<option value="">Seleccione </option>'
      for(var i = 0; i<data.length; i ++)
      {
        let ip = data[i].puerta_enlace.split('.');
        html_select += '<option value ="'+data[i].id+'">'+data[i].nombre+'</option>';
      }

      //envia opciones de select a la vista create.blade.php
      $('#ips').html(html_select);

    });
  });
</script> -->

@stop
