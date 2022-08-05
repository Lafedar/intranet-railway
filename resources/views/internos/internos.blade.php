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
      <th >Nombre</th>
      <th>Interno</th>
      <th>Correo electrónico</th>
      <th>Area</th>
      
    </thead>		
    
    <tbody>
      @if(count($personas))
      @foreach($personas as $persona)	
      @if($persona->activo==1)
      <tr>
        <td> {{$persona->nombre_p . ' '. $persona->apellido}}</td>
        <td>{{$persona->interno}}</td>
        <td><a href="mailto:{{$persona->correo}}">{{$persona->correo}}</a>
          <td>{{$persona->nombre_a}}</td>
        </tr>
      </tr>
      @endif
      @endforeach  
      @endif	
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