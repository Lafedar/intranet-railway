@extends('puestos.layouts.layout')
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
                    <div class="form-group"><h6>Puesto:</h6>
                        <input type="text" name="puesto" class="form-control" id="puesto" value="{{$puesto}}" >
                    </div>
                    &nbsp
                    <div class="form-group"><h6>Usuario:</h6>
                        <input type="text" name="usuario" class="form-control" id="usuario" value="{{$usuario}}" >
                    </div>
                    &nbsp
                    <div class="form-group"><h6>Localizacion:</h6>
                        <input type="text" name="localizacion" class="form-control" id="localizacion" value="{{$localizacion}}" >
                    </div>
                    &nbsp
                    <div class="form-group"><h6>Area:</h6>
                        <input type="text" name="area" class="form-control" id="area" value="{{$area}}" >
                    </div>
                    &nbsp
                    <button type="submit" class="btn btn-default"> Buscar</button>
                </div>
            </form>
        </div>
    </h1>            
</div>

<div class="col-md-12">             
    <table class="table table-striped table-bordered ">
        <thead>
            <th class="text-center">Nombre</th>
            <th class="text-center">Usuario</th>
            <th class="text-center">Localizacion</th>
            <th class="text-center">Area</th>
            <th class="text-center">Observación</th>
            @can('editar-puesto')
                <th class="text-center">Acciones</th>
            @endcan       
        </thead>  
        <tbody>
            @if(count($puestos))
                @foreach($puestos as $puesto) 
                    <tr>
                        <td>{{$puesto->desc_puesto}}</td>
                        <td >{{$puesto->nombre .' '. $puesto->apellido}}</td>
                        <td >{{$puesto->localizacion}}</td>
                        <td >{{$puesto->area}}</td>
                        <td align="center">{{$puesto->obs}}</td>
                        @can('editar-puesto')
                            <td align="center">
                                <div class="botones">
                                    <!-- Boton para eliminar puesto -->
                                    <a href="{{url('destroy_puesto', $puesto->id_puesto)}}" class="fa-solid fa-xmark eliminar" title="Borrar" 
                                    onclick="return confirm ('¿Está seguro que desea eliminar el puesto?')"data-position="top" data-delay="50" data-tooltip="Borrar"> </a>
                                    <!-- Boton para editar puesto -->
                                    <button class="fa-solid fa-pen default" onclick='fnOpenModalUpdate({{$puesto->id_puesto}})' data-toggle="modal" style="outline: none;"></button>
                                    
                                </div>    
                            </td>
                        @endcan
                    </tr>                    
                @endforeach  
            @endif  
        </tbody>
    </table>
    {{ $puestos->links('pagination::bootstrap-4') }}
</div>

<script> 
$("document").ready(function(){
    setTimeout(function(){
       $("div.alert").fadeOut();
    }, 5000 ); // 5 secs

});
</script>

@stop