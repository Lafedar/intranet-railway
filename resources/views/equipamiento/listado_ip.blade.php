@extends('equipamiento.layouts.layout')
@section('content')

<div class="col-md-12 ml-auto">
<h1>
        <div class="form-inline pull-right">
            <form method="GET" action="{{ route('listado_ip') }}" class="form-inline">
                <div class="form-group">
                    <label><h6>Busqueda general:</h6></label>
                    <input type="text" name="search" class="form-control col-md-6" id="search1" autocomplete="off" placeholder="Buscar" value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary ml-2">Buscar</button>
            </form>
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
            @foreach ($equipamientos as $equipamiento)
                <tr>
                    <td align="center">{{ $equipamiento->ip }}</td>
                    <td align="center">{{ $equipamiento->nombre_red }}</td>
                    <td align="center">{{ $equipamiento->id_equipamiento }}</td>
                    <td align="center">{{ $equipamiento->tipo }}</td>
                    <td align="center">{{ $equipamiento->nombre . ' ' . $equipamiento->apellido }}</td>
                    <td align="center">{{ $equipamiento->obs }}</td>
            
                </tr>
            @endforeach
    </tbody>
  </table>
</div>

{{$equipamientos->links('pagination::bootstrap-4')}}


<script src="{{ URL::asset('/js/jquery.min.js') }}"></script>

{{--<script>
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
</script>--}}



@stop

