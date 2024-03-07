@extends('internos.layouts.layout')

@section('content')

<div class="col-md-12 ml-auto d-print-none">
  <div class="form-group">
   <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar">
 </div>
</div>

<div class="col-sm-12 ">             
  <table id="test" class="table table-striped table-bordered table-condensed" role="grid" cellspacing="0" cellpadding="2" border="10">
    <thead>
      <th>Interno</th>
      <th>Area</th>
      <th>Nombre / Localizacion</th>
      <th>Correo electrónico</th>
    </thead>  
    <tbody>
      @foreach ($localizaciones as $localizacion)
        <tr>
          <td>{{$localizacion->interno}}</td>
          <td>{{$localizacion->area}}</td>
          <td>{{$localizacion->nombre}}</td>
          <td></td>
        </tr>
      @endforeach
      @foreach($personas as $persona) 
        <tr>
          <td>{{$persona->interno}}</td>
          <td>{{$persona->area}}</td>
          <td>{{$persona->nombre . ' '. $persona->apellido}}</td>
          <td><a href="mailto:{{$persona->correo}}">{{$persona->correo}}</a></td>
        </tr>
      @endforeach  
    </tbody>
  </table>
  {{ $personas->links() }}
</div>

@stop

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