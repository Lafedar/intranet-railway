@extends('persona.layouts.layout')
@section('content')

<div class="content">
    <div class="row" style="justify-content: center">
      <div id="alert" class="alert alert-success col-md-10 text-center" style="display: none"></div>
  </div>
</div>

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
              <div class="form-group"><h6>Nombre:</h6>
                <input type="text" name="nombre" class="form-control" id="nombre" value="{{$nombre}}">
            </div>
            &nbsp
            <div class="form-group"><h6>Empresa:</h6>
                <input type="text" name="empresa" class="form-control" id="empresa" value="{{$empresa}}" >
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
        <th class="text-center">Nombre</th>
        <th class="text-center">Apellido</th>
        <th class="text-center">Empresa</th>
        <th class="text-center">Telefono</th>
        <th class="text-center">Celular</th>        
        <th class="text-center">Acciones</th>        
    </thead>

    <tbody>
        @if(count($personas))
        @foreach($personas as $persona)
        <tr>
            <td>{{$persona->nombre}}</td>
            <td>{{$persona->apellido}}</td>
            <td>{{$persona->empresa}}</td>
            <td>{{$persona->telefono}}</td>
            <td>{{$persona->celular}}</td>
            <td width="153">
                <form action="{{route('destroy_contacto', $persona->id)}}" method="put">
                    <a href="#" class="btn btn-info btn-sm" data-nombre="{{$persona->nombre .' '. $persona->apellido}}" data-empresa="{{$persona->empresa}}" data-direccion="{{$persona->direccion}}" data-celular="{{$persona->celular}}"  data-telefono="{{$persona->telefono}}" data-correo="{{$persona->correo}}" data-toggle="modal" data-target="#ver"> Ver</a>


                    <a  href="#" class="btn btn-primary btn-sm" data-id="{{$persona->id}}" data-nombre="{{$persona->nombre}}" data-apellido="{{$persona->apellido}}" data-direccion="{{$persona->direccion}}" data-empresa="{{$persona->empresa}}" data-interno="{{$persona->interno}}" data-celular="{{$persona->celular}}"  data-telefono="{{$persona->telefono}}" data-correo="{{$persona->correo}}" data-toggle="modal" data-target="#editar"> Editar</a>


                    <button type="submit" class="btn btn-danger btn-sm btn-borrar" data-tooltip="Borrar"> X</button>
                </form>
            </div>
        </td>
    </tr>
    @endforeach
    @endif  
</tbody>       
</table>
@include('persona.show')    

@include('persona.edit')

{{ $personas->appends($_GET)->links() }}
</div>

<script> 
  $("document").ready(function(){
    setTimeout(function(){
     $("div.alert").fadeOut();
    }, 5000 ); // 5 secs

  });
</script>

<script>
  $('#ver').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var nombre = button.data('nombre') 
    var empresa = button.data('empresa') 
    var direccion = button.data('direccion')
    var telefono = button.data('telefono')
    var celular = button.data('celular')
    var correo = button.data('correo')
    var modal = $(this)

    modal.find('.modal-body #nombre').val(nombre);
    modal.find('.modal-body #empresa').val(empresa);
    modal.find('.modal-body #direccion').val(direccion);
    modal.find('.modal-body #telefono').val(telefono);
    modal.find('.modal-body #celular').val(celular);
    modal.find('.modal-body #correo').val(correo);
})
</script>


<script>
  $('#editar').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var id = button.data('id')
    var nombre = button.data('nombre')
    var apellido = button.data('apellido') 
    var empresa = button.data('empresa') 
    var direccion = button.data('direccion')
    var telefono = button.data('telefono')
    var interno = button.data('interno')
    var celular = button.data('celular')
    var correo = button.data('correo')
    var modal = $(this)

    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #nombre').val(nombre);
    modal.find('.modal-body #apellido').val(apellido);
    modal.find('.modal-body #empresa').val(empresa);
    modal.find('.modal-body #direccion').val(direccion);
    modal.find('.modal-body #telefono').val(telefono);
    modal.find('.modal-body #interno').val(interno);
    modal.find('.modal-body #celular').val(celular);
    modal.find('.modal-body #correo').val(correo);
})
</script>

<script>
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
</script>


@endsection
