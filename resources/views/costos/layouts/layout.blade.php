<!DOCTYPE html>
<html lang="es">

<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">

<script type="text/javascript" src="{{ URL::asset('/js/modal-jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-popper.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-bootstrap.min.js') }}"></script>

<head>
  <meta charset="UTF-8">
  <title>Intranet Lafedar</title>
  <link  rel="icon"   href="img/ico.png" type="image/png" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script language="JavaScript" src="{{ URL::asset('/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/powerbis"> <img class="logo" src="{{ URL::to('/img/logo.png') }}" height="40"> </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar1">
      <ul class="navbar-nav ml-auto">
        @can('agregar-costo')
        <button class="btn btn-info"  data-toggle="modal" data-target="#agregar"> Agregar</button>
        @endcan
        &nbsp
        <form action="{{ url('/logout') }}" method="POST" >
          {{ csrf_field() }}
          <button type="submit" class="btn btn-danger" style="display:inline;cursor:pointer">
           Cerrar sesión
         </button>
       </form>
     </ul>
   </div>
 </nav>
<p></p>
</head>

<script type="text/javascript" src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>

<body>

@yield('content')

</body>
@include('costos.create')
</html>