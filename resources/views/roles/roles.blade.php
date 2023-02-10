  @extends('usuarios.layouts.layout')

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
 <div class="content">
    <div class="row" style="justify-content: center">
      <div id="alert" class="alert alert-success col-md-10 text-center" style="display: none"></div>
  </div>
</div>


<div class="col-md-12 ml-auto">
  <div class="form-group">
   <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar">
 </div>
</div>

<div class="col-sm-12">             
  <table id="test" class="table table-striped table-bordered table-condensed" role="grid" cellspacing="0" cellpadding="2" border="10">
    <thead>
      <th class="text-center">ID</th>
      <th class="text-center">Nombre</th>
      <th class="text-center">Permisos</th>
      <th class="text-center">Acciones</th>       
    </thead>        
    
    <tbody>
      @if(count($roles))
      @foreach($roles as $rol) 
      <tr>
        <td align="center">{{$rol->id_rol}}</td>
        <td align="center">{{$rol->nombre_rol}}&nbsp</td>
      <td>
        @foreach($permisos as $permiso)
          @if($permiso->rol == $rol->id_rol)
            {{$permiso->nombre_permiso}}&nbsp
          @endif
        @endforeach
      </td>
      <td  align="center" width="300">
        <form action="{{route('destroy_rol', $rol->id_rol)}}" method="put">

          <a href=# data-toggle="modal" data-target="#asignar_permiso" data-id="{{$rol->id_rol}}" data-nombre="{{$rol->nombre_rol}}" class="btn btn-info btn-sm" type="submit"> Asignar Permiso</a>
          
          <a href=# data-toggle="modal" data-target="#revocar_permiso" data-id="{{$rol->id_rol}}" data-nombre="{{$rol->nombre_rol}}" class="btn btn-warning btn-sm" type="submit"> Revocar Permiso</a>
        
        <button type="submit" class="btn btn-danger btn-sm btn-borrar" data-tooltip="Borrar"> X</button>
        </form>
      </td>
    </tr>                    
    @endforeach  
    @endif  
  </tbody>
</table>
</div>

@include('roles.modal_asignar')

@include('roles.modal_revocar')

<script> 
  $("document").ready(function(){
    setTimeout(function(){
     $("div.alert").fadeOut();
    }, 5000 ); // 5 secs

  });
</script>

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

<script>
  $('#asignar_permiso').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var id = button.data('id')
    var nombre = button.data('nombre')
    var modal = $(this)

    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #nombre').val(nombre);

    $.get('select_permiso/'+id ,function(data){
      var html_select = '<option value="">Seleccione permiso </option>'
      for(var i = 0; i<data.length; i ++)
        html_select += '<option value ="'+data[i].id+'">'+data[i].name+'</option>';
      $('#select_permiso').html(html_select);
    });

  });
</script>

<script>
  $('#revocar_permiso').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var id = button.data('id')
    var nombre = button.data('nombre')
    var modal = $(this)

    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #nombre').val(nombre);

    $.get('select_revocar_permiso/'+id ,function(data){
      var html_select = '<option value="">Seleccione permiso </option>'
      for(var i = 0; i<data.length; i ++)
        html_select += '<option value ="'+data[i].id+'">'+data[i].name+'</option>';
      $('#select_revocar_permiso').html(html_select);
    });

  });
</script>
 
@stop