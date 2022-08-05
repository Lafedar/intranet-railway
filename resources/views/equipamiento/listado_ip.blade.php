@extends('equipamiento.layouts.layout')
@section('content')

<div class="col-md-12 ml-auto">
  <div class="form-group">
     <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar">
 </div>
</div>

<div class="col-sm-12">             
  <table id="test" class="table table-striped table-bordered table-condensed" role="grid" cellspacing="0" cellpadding="2" border="10">
    <thead>
     <th class="text-center">IP</th>
     <th class="text-center">Equipamiento</th>
     <th class="text-center">Tipo</th>
     <th class="text-center">Usuario</th>
     <th class="text-center">Observación</th>  
 </thead>        

 <tbody>
  @for($i=1;$i<255;$i++) 
  <tr>
    <td align="center">{{$listado[$i][0]}}</td>
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
     $("#search").keyup(function(){
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

