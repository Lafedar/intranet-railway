@extends('layouts.app')
@section('content')

<link rel="shortcut icon" src="{{ URL::to('/img/ico.png') }}" />

@if(Session::has('message'))
<div class="container">
  <div class="row">
    <div class="col-2"></div>
    <div class="alert {{Session::get('alert-class')}} col-8 text-center" role="alert">
     {{Session::get('message')}}
   </div>
 </div>
</div>
@endif

<div class="container text-center" >

  <br>
<!--***********Margen superior**********-->  
  <div class="row">
    <div class="col-md-12">
        <br><br>
    </div>
  </div>
<!--***********linea 1, 3 iconos****************-->

<div class="row">
    <div class="col-md-3">
      <a  href="/internos"> <img  src="{{ URL::to('/img/internos.png') }}" height="120"></a>
    </div>

    <div class="col-md-3" >
      <a  href="{{('/permisos')}}"> <img src="{{ URL::to('/img/permisos.png') }}" height="120"> </a>
    </div>

    <div class="col-md-3">
      <a  href="{{('/documentos')}}"> <img src="{{ URL::to('/img/documentacion.png') }}" height="120"> </a>
    </div>
    
    <div class="col-md-3">
      <a  href="{{('/persona')}}"> <img  src="{{ URL::to('/img/recepcion.png') }}" height="120"></a>
    </div>
    

</div>
<br><br>
<!--<div class="row">
    <div class="col-md-12">
        <br><br>
    </div>
</div>-->

<div class="row">
    
    
    <div class="col-md-4">
      <a  href="{{('/sistemas')}}"> <img src="{{ URL::to('/img/sistemas.png') }}" height="120"> </a>
    </div>
        
    <div class="col-md-4">
      <a  href="{{('/qad')}}"> <img src="{{ URL::to('/img/qad.png') }}" height="120"> </a>
    </div>
   
    <div class="col-md-4">
      <a  href="{{('/eventos')}}"> <img src="{{ URL::to('/img/calendario.png') }}" height="120"> </a>
    </div>
    
    
</div>
<br><br>
<!--<div class="row">
    <div class="col-md-12">
        <br><br>
    </div>
</div>-->

<div class="row">
  <div class="col-md-3">
    <a  href="{{'/powerbis'}}"> <img src="{{ URL::to('/img/powerbi.png') }}" height="120"> </a>
  </div>

  <div class="col-md-3">
    <a  href="{{('/empleado')}}"> <img src="{{ URL::to('/img/personal.png') }}" height="120"> </a>
  </div>

  <div class="col-md-3">
    <a  href="{{('/medico')}}"> <img src="{{ URL::to('/img/medico.png') }}" height="120"> </a>
  </div>

  <div class="col-md-3">
    <a  href="{{('/visitas')}}"> <img src="{{ URL::to('/img/guardia.png') }}" height="120"> </a>
  </div>
        
</div>
  
</div>

<div id="footer-lafedar"></div>

<script> 
  $("document").ready(function(){
    setTimeout(function(){
     $("div.alert").fadeOut();
    }, 5000 ); // 5 secs

    $.get('notificaciones', function(data){
      if(data.length==0){
        $('#notificacion_off').show();
      }else{
        $('#notificacion_on').show();
        var parentDiv = document.getElementById("notis");
        originalDiv = document.getElementById("div1");
        for(var i = 0; i<data.length; i++){
          var nuevo_div = document.createElement("div");
          nuevo_div.setAttribute("id","mensajes-inicio");
          nuevo_div.setAttribute("class","text-justify");
          if(i == 0){
            nuevo_div.innerHTML=data[i].descripcion + "<br>" + "<br>";
          }else{
            nuevo_div.innerHTML=data[i].descripcion + "<br>"+ "<hr>";  
          }
          parentDiv.insertBefore(nuevo_div, originalDiv.nextSibling);
        }
      $("#novedades").modal("show");
      }

    });

  });
</script>

@stop