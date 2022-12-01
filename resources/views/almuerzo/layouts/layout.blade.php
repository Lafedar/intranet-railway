<!DOCTYPE html>
<html lang="es">

<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">


<script type="text/javascript" src="{{ URL::asset('/js/jquery-3.1.0.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-popper.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-bootstrap.min.js') }}"></script>
<!--<script type="text/javascript" src="{{URL::asset('/js/seleccionar.js')}}"></script>
-->
<head>

  <meta charset="UTF-8">
  <title>Intranet Lafedar</title>
  <link  rel="icon"   href="img/ico.png" type="image/png" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script language="JavaScript" src="{{ URL::asset('/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
   <a class="navbar-brand" href="/"> <img class="logo" src="{{ URL::to('/img/logo.png') }}" height="40"> </a>
  <!--
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbar1">
    <ul class="navbar-nav ml-auto"> &nbsp   
      <button class="btn btn-secondary" data-toggle="modal" data-target="#novedad">Novedades</button>&nbsp

      <a href="#" class="btn btn-info"  data-toggle="modal" data-target="#agregar_empleado" type="submit">seleccionar menu</a>
      &nbsp
    
      <form action="{{ url('/logout') }}" method="POST" >
       {{ csrf_field() }}
       <button type="submit"  class="btn btn-danger" style="display:inline;cursor:pointer">Cerrar sesión</button>  
     </form>-->
   </ul>
 </div>
</nav>
<p></p>
</head>
    

<body>
   <div class="container">
  @yield('seccion')
  </div>  
</body>

</html>

