<!DOCTYPE html>
<html lang="es">

<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<script src="https://kit.fontawesome.com/b36ad16a06.js" crossorigin="anonymous"></script>
<link href="{{ asset('css/acciones.css') }}" rel="stylesheet">
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
        <button class="btn btn-info"  data-toggle="modal" data-target="#agregar_solicitud"> Agregar solicitud</button>
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

@include('solicitudes.create')

<script>
  $('#agregar_solicitud').on('show.bs.modal', function (event) {

    $.get('select_create/',function(data)
    {
      var html_select_area = '<option value="">Seleccione </option>'
      var html_select_localizacion = '<option value="">Seleccione </option>'
      var html_select_tipo_solicitud = '<option value="">Seleccione </option>'
      var html_select_equipo = '<option value="">Seleccione </option>'
      var html_select_falla = '<option value="">Seleccione </option>'

      // [0]=areas [1]=localizaciones [2]=tipo_solicitudes [3]=equipos_mant 
      // [4]=fallas [5]=tipos_equipos [6]=fallasxtipo
      for(var i = 0; i<data[0].length; i ++)
      {
        html_select_area += '<option value ="'+data[0][i].id_a+'">'+data[0][i].nombre_a+'</option>';
      }

      $('#area').html(html_select_area);
      $('#localizacion').html(html_select_localizacion);
      //toma cambio de seleccion de area
      document.getElementById("area").addEventListener("change", function() 
      {
        var selectedOption = this.value;
        var html_select_localizacion = '<option value="">Seleccione </option>';
        for (var i = 0; i < data[1].length; i++) 
        {
          if (data[1][i].id_area == selectedOption) 
          {
            html_select_localizacion += '<option value="' + data[1][i].id + '">' + data[1][i].nombre + '</option>';
            document.getElementById("localizacion").innerHTML = html_select_localizacion;
          }
        }
        if(selectedOption == '')
        {
          document.getElementById("localizacion").innerHTML = html_select_localizacion;
          document.getElementById("div_localizacion").style.display = "none";
          document.getElementById("div_tipo_solicitud").style.display = "none";
          document.getElementById("div_equipo").style.display = "none";
          document.getElementById("div_falla").style.display = "none";
        }
        else
        {
          document.getElementById("localizacion").innerHTML = html_select_localizacion;
          document.getElementById("div_localizacion").style.display = "block";
          document.getElementById("div_tipo_solicitud").style.display = "none";
          document.getElementById("div_equipo").style.display = "none";
          document.getElementById("div_falla").style.display = "none";
        }
      });
      
      var aux_localizacion;

      document.getElementById("localizacion").addEventListener("change", function() 
      {
        var html_select_tipo_solicitud = '<option value="">Seleccione </option>';
        var selectedOption = this.value;
        aux_localizacion = this.value;
        if(selectedOption == '')
        {
          document.getElementById("div_tipo_solicitud").style.display = "none";
          document.getElementById("div_equipo").style.display = "none";
          document.getElementById("div_falla").style.display = "none";
        }
        else
        {
          document.getElementById("div_tipo_solicitud").style.display = "block";
          document.getElementById("div_equipo").style.display = "none";
          document.getElementById("div_falla").style.display = "none";
        }
        for(var i = 0; i<data[2].length; i ++)
        {
          html_select_tipo_solicitud += '<option value ="'+data[2][i].id+'">'+data[2][i].nombre+'</option>';
        } 
        $('#tipo_solicitud').html(html_select_tipo_solicitud);
      });

      document.getElementById("tipo_solicitud").addEventListener("change", function() 
      {
        var selectedOption = this.value;
        if(selectedOption == '')
        {
          document.getElementById("div_equipo").style.display = "none";
          document.getElementById("div_falla").style.display = "none";
        }
        else if(selectedOption == 1)
        {
          document.getElementById("div_equipo").style.display = "block";
          document.getElementById("div_falla").style.display = "none";
          var html_select_equipo = '<option value="">Seleccione </option>'
          for(var i = 0; i<data[3].length; i ++)
          { 
            if(aux_localizacion == data[3][i].id_localizacion)
            html_select_equipo += '<option value ="'+data[3][i].id+'">'+data[3][i].id+'</option>';
          } 
          $('#equipo').html(html_select_equipo);
        }
        else
        {
          document.getElementById("div_equipo").style.display = "none";
          document.getElementById("div_falla").style.display = "block";
          var html_select_falla = '<option value="">Seleccione </option>'
          for(var i = 0; i<data[4].length; i ++)
          {
            for(var j = 0; j<data[6].length; j ++)
            {
              if((data[6][j].id_falla == data[4][i].id) and (data[6][j].id_tipo_solicitud == 2))
              {
                html_select_falla += '<option value ="'+data[4][i].id+'">'+data[4][i].nombre+'</option>';
              }
            }
          } 
          $('#falla').html(html_select_falla);
        }
      });

    });

    /*$.get('select_equipo/',function(data)
    {
      
      
      
      document.getElementById("equipo").addEventListener("change", function() 
      {
        var selectedOption = this.value;
        if(selectedOption == '')
        {
          document.getElementById("div_falla").style.display = "none";
        }
        else
        {
          document.getElementById("div_falla").style.display = "block";
        }
      });
    });
    
    $.get('select_falla/',function(data)
    {
      
      
    });*/

  });
</script>

<body>

@yield('content')

</body>
</html>

