@extends('empleado.layouts.layout')
@section('content')

<div id="alert" class="alert alert-info" style="display: none"></div>

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
  <div class="form-group">
   <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar">
 </div>
</div>

<div class="col-sm-12">             
  <table id="test" class="table table-striped table-bordered table-condensed" role="grid" cellspacing="0" cellpadding="2" border="10">
    <thead>
      <th class="text-center">Apellido y nombre</th>
      <th class="text-center">DNI</th>
      <th class="text-center">Fecha de ingreso</th>
      <th class="text-center">Fecha de nacimiento</th>
      <th class="text-center">Area</th>
      <th class="text-center">En actividad</th>
      <th class="text-center">Acciones</th>
    </thead>        
    
    <tbody>
      @if(count($empleados))
        @foreach($empleados as $empleado) 
          <tr>
            @if ($empleado->dni != 9999999)
              <td > {{$empleado->apellido . ' '. $empleado->nombre_p}}</td>

              <td align="center">{{$empleado->dni}}</td>

              @if ($empleado->fe_ing != '')
                <td align="center">{!! \Carbon\Carbon::parse($empleado->fe_ing)->format("d-m-Y") !!}</td>
              @else
                <td align="center"></td>
              @endif

              @if ($empleado->fe_nac != '')
                <td align="center">{!! \Carbon\Carbon::parse($empleado->fe_nac)->format("d-m-Y") !!}</td>
              @else
                <td align="center"></td>
              @endif

              <td>{{$empleado->nombre_a}}</td>

              @if($empleado->activo == 1)
                <td width="60" style="text-align: center;"><div class="circle_green"></div></td>
              @else
                <td width="60" style="text-align: center;"><div class="circle_grey"></div></td>
              @endif

              <td align="center" width="110">
                <form action="{{route('destroy_empleado', $empleado->id_p)}}" method="put">
                  <a href="#" class="btn btn-info btn-sm"  data-toggle="modal" data-id="{{$empleado->id_p}}" data-nombre="{{$empleado->nombre_p}}" 
                  data-apellido="{{$empleado->apellido}}" data-area="{{$empleado->area}}" data-dni="{{$empleado->dni}}" data-fe_nac="{{$empleado->fe_nac}}" 
                  data-fe_ing="{{$empleado->fe_ing}}" data-interno="{{$empleado->interno}}" data-correo="{{$empleado->correo}}" data-activo="{{$empleado->activo}}" 
                  data-target="#editar_empleado">Editar</a>
                  <button type="submit" class="btn btn-danger btn-sm btn-borrar" data-tooltip="Borrar"> X</button>
                </form>
              </td>
            @endif
          </tr>
        </tr>
      @endforeach  
    @endif  
  </tbody>
</table>
</div>
@include('empleado.edit')

<script>
  $('#editar_empleado').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var id = button.data('id')
    var nombre = button.data('nombre')
    var apellido = button.data('apellido')
    var area = button.data('area')
    var dni = button.data('dni')
    var interno = button.data('interno')
    var fe_nac = button.data('fe_nac')
    var fe_ing = button.data('fe_ing')
    var correo = button.data('correo')
    var activo = button.data('activo')
    var checkbox = $('#actividad');
    var modal = $(this)
    
    checkbox.prop('checked', activo == 1);
    modal.find('.modal-body #id_p').val(id);
    modal.find('.modal-body #nombre_p').val(nombre);
    modal.find('.modal-body #apellido').val(apellido);
    modal.find('.modal-body #dni').val(dni);
    modal.find('.modal-body #interno').val(interno);
    modal.find('.modal-body #fe_nac').val(fe_nac);
    modal.find('.modal-body #fe_ing').val(fe_ing);
    modal.find('.modal-body #correo').val(correo);

    $.get('select_area/',function(data){
      var html_select = '<option value="">Seleccione </option>'
      for(var i = 0; i<data.length; i ++){
        if(data[i].id_a == area){
          html_select += '<option value ="'+data[i].id_a+'"selected>'+data[i].nombre_a+'</option>';
        }else{
          html_select += '<option value ="'+data[i].id_a+'">'+data[i].nombre_a+'</option>';
        }
      }
      $('#select_area').html(html_select);
    });
  })
</script>

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


@endsection

