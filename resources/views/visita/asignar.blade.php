@extends('visita.layouts.layout')
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

<div class="container">
  <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
      <h4 class="headertekst" align="center">Asignar tarjeta</h4>
      <hr>
    </div>
  </div>
  
  <div class="row"> 
    <div class="col-md-1"></div>
    <div class="col-md-5">
      <form action="{{ action('VisitaController@store') }}" method="POST">
        {{csrf_field()}}
        <input type="hidden" name="id">
        <div class="col">Empresa:
          <select class="form-control" name="empresa"  id="empresa"  required>
           <option value="">Seleccione empresa</option>
           @foreach($empresas as $empresas)
           <option value="{{$empresas->id_emp}}">{{$empresas->razon_social}}</option>
           @endforeach
         </select>
         <a href=# data-toggle="modal" data-target="#añadir_empresa">Agregar empresa</a>
         <p></p>
       </div>
       
       <div class="col">Persona:
        <select class="form-control" name="externo"  id="externo" required>
          <option value="1">Seleccione persona</option>
        </select>
       <div class="agregar">
        <a href=# data-toggle="modal" data-target="#añadir_externo">Agregar persona</a>
      </div>
        <p></p>
      </div>

      <div class="col">Visita a:
        <select class="form-control" name="interno"  id="interno" required>
         <option value="">Seleccione a quien visita</option>
         @foreach($internos as $internos)
         <option value="{{$internos->id_p}}">{{$internos->apellido.' '.$internos->nombre_p}}</option>
         @endforeach
       </select>
       <p></p>  
     </div>
     <div class="col">Tarjeta:
      <select class="form-control" id="tarjeta" name="tarjeta" required>
       <option value="">Seleccione una tarjeta</option>
       @foreach($tarjetas as $tarjetas)
       <option>{{$tarjetas->id_tar}}</option>
       @endforeach
     </select> 
     <p></p>      
   </div>
   
   <div class="row">
    <div class="col-md-12" align=" center">
      <a class="btn btn-secondary " href="visitas">Volver</a>
      
      <button type="subitm" class="btn btn-info">Guardar</button>
    </div>
  </div>
  <br>
</form>
</div>
&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp  
<div class="col-md-4" id="foto" style="display: none">
  <img id="imagen" name="imagen" height="241" width="400"  align="center">

</div>
</div>

<div id="footer-lafedar">
</div>

@include('visita.modal_añadir_empresa')
@include('visita.modal_añadir_externo')

<script>  
  $("document").ready(function(){
    $("div.agregar").hide();
    setTimeout(function(){
     $("div.alert").fadeOut();
    }, 5000 ); // 5 secs

  });
</script>

<script>
  $("#empresa").change(function(){
    $('#foto').fadeOut();
    $("div.agregar").show();

    var empresa = $(this).val();

    $.get('ExternoByEmpresa/'+empresa, function(data){
      var externo = '<option value="">Seleccione persona </option>'
      for (var i=0; i<data.length;i++)
        externo+='<option value="'+data[i].dni+'">'+data[i].nombre_ext+' '+ data[i].apellido_ext+'</option>';
      $("#externo").html(externo);
    });
  }); 
</script>

<script>
  $("#externo").change(function(){
    document.getElementById("foto").style = "display: none";
    document.getElementById("imagen").src = " ";
    var externo = document.getElementById("externo").value;
    $.get('fotoExterno/'+externo, function(data){
      var storage="{{Storage::url(':fotito_reemplaza')}}";
      storage=storage.replace(':fotito_reemplaza',data[0].foto);
      foto = document.getElementById("imagen").src =storage;
      $('#foto').show();
    });
  });
</script>


<script>
  $('#añadir_externo').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var empresa = document.getElementById("empresa").value 
    var modal = $(this)

    modal.find('.modal-body #empresa_ext').val(empresa);
  })
</script>



@stop