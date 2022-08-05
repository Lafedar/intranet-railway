@extends('layouts.app')
@section('content')

<div class="container text-center" >

  <br><br><br>
  
  <div class="row">
    <div class="col-md-12"></div>
    
    <br><br>

       <div class="col-md-3">
      <a  href="/ot"> <img  src="{{ URL::to('/img/orden de trabajo.png') }}" height="140"></a>
    </div>
   <!--
    <div class="col-md-3">
      <a  href="/foc" data-toggle="modal" data-target="#formulario_oc"> <img  src="{{ URL::to('/img/orden de compra.png') }}" height="140"></a>
    </div>
    *********************************-->
    <div class="col-md-3">
      <a  href="/oc"> <img  src="{{ URL::to('/img/orden de compra.png') }}" height="140"></a>
      </div>
  
    <!--
     <div class="col-md-3">
      <a  href="/foc"> <img  src="{{ URL::to('/img/orden de compra.png') }}" height="140"></a>
     </div>
  **************************************-->

     <!--<div class="col-md-3">
      <a  href="#"> <img  src="{{ URL::to('/img/consulta requisiciones.png') }}" height="140"></a>
    </div>
-->
  </div>

</div>

@include('qad.formulario_oc')


<div id="footer-lafedar"></div>


<script type="text/javascript">
    $("form").submit(function(e){
    $('#formulario_oc').modal('hide');
     
   });
   $("#formulario_oc").on("hidden.bs.modal", function () {
    document.getElementById("oc").value = "";
    document.getElementById("fecha1").value = "";
    document.getElementById("fe_has").value = "";
    document.getElementById("nro_proveedor").value = "";
    document.getElementById("cuit").value = "";
    document.getElementById("nro_articulo").value = "";
});
</script>


@stop
