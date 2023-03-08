<!DOCTYPE html>
<html lang="es">

<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<script src="https://kit.fontawesome.com/b36ad16a06.js" crossorigin="anonymous"></script>
<link href="{{ asset('css/acciones.css') }}" rel="stylesheet">
<link href="{{ asset('css/estado.css') }}" rel="stylesheet">
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
    <a class="navbar-brand" href="/mantenimiento"> <img class="logo" src="{{ URL::to('/img/logo.png') }}" height="40"> </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar1">
      <ul class="navbar-nav ml-auto">
        <button class="btn btn-info"  data-toggle="modal" data-target="#agregar_equipo_mant"> Agregar Equipo</button>
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

@include('equipos_mant.create')



<script>
  $('#agregar_equipo_mant').on('show.bs.modal', function (event) {

    $.get('select_area_localizacion/',function(data)
    {
      var html_select = '<option value="">Seleccione </option>'
      var html_select2 = '<option value="">Seleccione </option>'

      for(var i = 0; i<data[0].length; i ++)
      {
        html_select += '<option value ="'+data[0][i].id_a+'">'+data[0][i].nombre_a+'</option>';
      }

      $('#area').html(html_select);
      $('#localizacion').html(html_select2);

      document.getElementById("area").addEventListener("change", function() 
      {
        var selectedOption = this.value;
        var html_select2 = '<option value="">Seleccione </option>';
        for (var i = 0; i < data[1].length; i++) 
        {
          if (data[1][i].id_area == selectedOption) 
          {
            html_select2 += '<option value="' + data[1][i].id + '">' + data[1][i].nombre + '</option>';
            document.getElementById("localizacion").innerHTML = html_select2;
          }
        }
        if(selectedOption == '')
        {
          document.getElementById("localizacion").innerHTML = html_select2;
          document.getElementById("div_localizacion").style.display = "none";
        }
        else
        {
          document.getElementById("localizacion").innerHTML = html_select2;
          document.getElementById("div_localizacion").style.display = "block";
        }
      });
    });

    $.get('select_tipo_equipo/',function(data)
    {
      var html_select = '<option value="">Seleccione </option>'
      for(var i = 1; i<data.length; i ++)
      {
        html_select += '<option value ="'+data[i].id+'">'+data[i].nombre+'</option>';
      }
      $('#tipo').html(html_select);
    });
  });
</script>

<body>

@yield('content')

</body>
</html>

