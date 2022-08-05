<!DOCTYPE html>
<html lang="es">

<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">

<script src="{{ URL::asset('/js/jquery.min.js') }}"></script>

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

    <a class="navbar-brand" href="/sistemas"> <img class="logo" src="{{ URL::to('/img/logo.png') }}" height="40"> </a>
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">

      <span class="navbar-toggler-icon"></span>

    </button>
    <div class="collapse navbar-collapse" id="navbar1">

      <ul class="navbar-nav ml-auto">    
       <a href="/create_usuario" class="btn btn-info" data-position="top" data-delay="50" data-tooltip="Ver"> Nuevo usuario</a>
       &nbsp
       <button class="btn btn-info"  data-toggle="modal" data-target="#agregar_rol">Nuevo rol</button>       
       &nbsp
       <button class="btn btn-info"  data-toggle="modal" data-target="#agregar_permiso">Nuevo permiso</button>       
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

<!-- Modal Agregar-->
<div class="modal fade" id="agregar_rol" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <p class="statusMsg"></p>
    <form action="{{action('RolController@store_rol', '')}}" method="POST" >
      {{csrf_field()}}
      <div class="modal-body">
       <div class="row">
         <div class="col-md-12 form-group">
          <label>Nombre nuevo rol:</label>
          <input type="text" name="nombre_rol" class="form-control">
          <br>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Agregar</button>
        </div>
      </div>
    </div>
  </form>                
</div>
</div>
</div>

<!-- Modal Agregar-->
<div class="modal fade" id="agregar_permiso" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <p class="statusMsg"></p>
    <form action="{{action('RolController@store_permiso', '')}}" method="POST" >
      {{csrf_field()}}
      <div class="modal-body">
       <div class="row">
         <div class="col-md-12 form-group">
          <label>Nombre nuevo permiso:</label>
          <input type="text" name="nombre_permiso" class="form-control">
          <br>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Agregar</button>
        </div>
      </div>
    </div>
  </form>                
</div>
</div>
</div>


</html>