@extends('sistemas.layouts.layout')
@section('content')



  <div class="container text-center"> 

  <br><br><br><br>
  
  <div class="row">    
 
    <div class="col-md-3" aling="center">
      <a  href='equipamiento'> <img  src="{{ URL::to('/img/equipamiento.png') }}" height="140"></a>
    </div>

    <div class="col-md-3" aling="center">
      <a  href='/puestos'> <img  src="{{ URL::to('/img/puesto_de_trabajo.png') }}" height="140"></a>
    </div>
    
    @can('editar-usuarios')
    <div class="col-md-3" aling="center">
     <a  href='/usuarios'> <img  src="{{ URL::to('/img/usuarios.png') }}" height="140"></a>
   </div>

   <div class="col-md-3" aling="center">
     <a  href='roles'> <img  src="{{ URL::to('/img/roles.png') }}" height="140"></a>
   </div>
   @endcan



   <div class="col-md-3" aling="center">
    <br>
     <a  href='/incidentes'> <img  src="{{ URL::to('/img/incidentes.png') }}" height="140"></a>
   </div>

   

   <div class="col-md-3" aling="center">
    <br>
     <a  href='/Software'> <img  src="{{ URL::to('/img/software.png') }}" height="140"></a>
   </div>
   
   
   <div class="col-md-3" aling="center">
    <br>
    <a  href='/listado_ip'> <img  src="{{ URL::to('/img/busca_ip.png') }}" height="140"></a>
  </div>

  <div class="col-md-3" aling="center">
    <br>
     <a  href='/Instalado'> <img  src="{{ URL::to('/img/software instalado.png') }}" height="140"></a>
   </div>
</div>


<div id="footer-lafedar"></div>

@stop