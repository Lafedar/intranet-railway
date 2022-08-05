@extends('layouts.app')
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
  <head>
    <title></title>
    <meta content="">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

    <style>
    body{
      font-family: 'Exo', sans-serif;
    }


    .header-col{
      background: #E3E9E5;
      width: 162.5px;
      color:#536170;
      text-align: center;
      font-size: 20px;
      font-weight: bold;
    }
    .header-calendar{
      background: #EE192D;
      width: 162.5px;
      color:white;
      text­align: justify;
    }
    .box-day{
      
      border:1px solid #E3E9E5;
      height:150px;
      width: 162.5px;
    }
    .box-dayoff{
      border:1px solid #E3E9E5;
      height:150px;
      width: 162.5px;
      background-color: #ccd1ce;
    }
    .btn-ttc,
    .btn-ttc:hover,
    .btn-ttc:active {
     color: white;
     text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
      background-color:#2B547E;.
    }


    .badge-sal{
     background-color:#FFDFDD;
     color:black;
    }
    .badge-sal1{
     background-color:#48CCCD;
     color:black;
    }
    .badge-sal2{
     background-color:#1569C7;
     color:black;
    }

    .badge-sal3{
     background-color:#98AFC7;
     color:black;
    }

    .badge-sal4{
     background-color:#FFE5B4;
     color:black;

    </style>

  </head>
  <body>

    <div class="container">
      <div style="height:50px"></div>
      <!--<h1>      Calendario LAFEDAR.SA <small></small></h1>
      <p class="lead">
      <h3>Calendario - evento</h3>-->
      <a class="btn btn-ttc"  href="{{ asset('/Evento/form') }}">Crear un evento</a>
      <a class="btn btn-ttc"  href="{{ asset('/') }}">Volver</a>
       



      <hr>

      <!---->  <div class="row header" >

        <div class="col" style="display: flex; justify-content: space-between; padding: 10px;">
          <a  href="{{ asset('Evento/index/') }}/<?= $data['last']; ?>" style="margin:5px;">
            <i class="fas fa-chevron-circle-left" style="font-size:30px;color:#3490dc;"></i>
          </a>
          
          <h2 style="font-weight:bold;margin:5px;color :#4863A0"><?= $mespanish; ?> <small><?= $data['year']; ?></small></h2>
          
          <a  href="{{ asset('Evento/index/') }}/<?= $data['next']; ?>" style="margin:5px;">
            <i class="fas fa-chevron-circle-right" style="font-size:30px;color: #3490dc ;"></i>
          </a>
        </div>

      </div>
      <div class="row">
        <div class="col header-col">Lunes</div>
        <div class="col header-col">Martes</div>
        <div class="col header-col">Miercoles</div>
        <div class="col header-col">Jueves</div>
        <div class="col header-col">Viernes</div>
        <div class="col header-col">Sabado</div>
        <div class="col header-col">Domingo</div>
      </div>
      <!-- inicio de semana -->
      @foreach ($data['calendar'] as $weekdata)
        <div class="row">
     <!-- ciclo de dia por semana -->
          @foreach  ($weekdata['datos'] as $dayweek)

          @if  ($dayweek['mes']==$mes)

            <div class="box-day">
              
              {{ $dayweek['dia']  }}
   
              <!-- Cargar lsita de eventos -->
              <!-- evento -->
              @foreach  ($dayweek['evento'] as $evento) 
                
                @if($evento->activo == "1")

                @if($evento->ubicacion=="Sala Vidriada")
                
                <a class="badge badge-sal1" href="{{ asset('/Evento/details/') }}/{{ $evento->id }}"
                   data-titulo="{{$evento->titulo}}" data-ubicacion="{{$evento->ubicacion}}" data-id="{{$evento->id}}" data-descripcion="{{$evento->descripcion}}" data-solicitado="{{$evento->solicitado}}" data-activo="{{$evento->activo}}" data-fecha="{{$evento->fecha}}" data-hora="{{$evento->hora}}" data-toggle="modal" data-target="#ver">
                    {{ $evento->ubicacion }}
                    {{ $evento ->hora }}
                   </a>
                          
               @elseif($evento->ubicacion=="Auditorio")
                <a class="badge badge-sal2" href="{{ asset('/Evento/details/') }}/{{ $evento->id }}"
                   data-titulo="{{$evento->titulo}}" data-ubicacion="{{$evento->ubicacion}}" data-descripcion="{{$evento->descripcion}}" data-activo="{{$evento->activo}}" data-id="{{$evento->id}}"
                   data-solicitado="{{$evento->solicitado}}" data-fecha="{{$evento->fecha}}" data-hora="{{$evento->hora}}" data-toggle="modal" data-target="#ver">
                    {{ $evento->ubicacion }}
                    {{ $evento ->hora }}
                 
                  </a>
                  @elseif($evento->ubicacion=="Sala Protocolar")
                  
                <a class="badge badge-sal3" href="{{ asset('/Evento/details/') }}/{{ $evento->id }}"
                   data-titulo="{{$evento->titulo}}" data-ubicacion="{{$evento->ubicacion}}" data-descripcion="{{$evento->descripcion}}" data-activo="{{$evento->activo}}" data-fecha="{{$evento->fecha}}" data-solicitado="{{$evento->solicitado}}" data-id="{{$evento->id}}"
                   data-solicitado="{{$evento->solicitado}}" data-hora="{{$evento->hora}}" data-toggle="modal" data-target="#ver">
                    {{ $evento->ubicacion }}
                    {{ $evento ->hora }}
                 
                  </a>
                
                  @elseif($evento->ubicacion=="Sala Vidridada 2")

                <a class="badge badge-sal4" href="{{ asset('/Evento/details/') }}/{{ $evento->id }}"
                   data-titulo="{{$evento->titulo}}" data-ubicacion="{{$evento->ubicacion}}" data-id="{{$evento->id}}"data-descripcion="{{$evento->descripcion}}"  data-activo="{{$evento->activo}}" data-fecha="{{$evento->fecha}}" data-solicitado="{{$evento->solicitado}}" data-hora="{{$evento->hora}}" data-toggle="modal" data-target="#ver">
                    {{ $evento->ubicacion }}
                    {{ $evento ->hora }}
                 
                  </a>

                @else

                  <a class="badge badge-sal" href="{{ asset('/Evento/details/') }}/{{ $evento->id }}"
                   data-titulo="{{$evento->titulo}}" data-ubicacion="{{$evento->ubicacion}}" data-id="{{$evento->id}}" data-descripcion="{{$evento->descripcion}}" data-activo="{{$evento->activo}}" data-fecha="{{$evento->fecha}}" data-solicitado="{{$evento->solicitado}}" data-hora="{{$evento->hora}}" data-toggle="modal" data-target="#ver">
                    {{ $evento->ubicacion }}
                    {{ $evento ->hora }}
                 
                  </a>
                  @endif
                  @endif
              @endforeach


            </div>
          @else
          <div class="box-dayoff">
          </div>
          @endif


          @endforeach
        </div>
      @endforeach

    </div> <!-- /container -->

   

  </body>


@include('evento.evento')

<script>
  $('#ver').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) 
    var titulo = button.data('titulo') 
    var descripcion = button.data('descripcion') 
    var ubicacion = button.data('ubicacion')
    var fecha = button.data('fecha')
    var hora = button.data('hora')
    var activo =button.data('activo')
    var solicitado= button.data('solicitado')
    var Enca = 'Evento Programado'
    var id= button.data('id')
    
    var modal = $(this)
    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #activo').val(activo);
    modal.find('.modal-body #Enca').val(Enca);
    modal.find('.modal-body #titulo').val(titulo);
    modal.find('.modal-body #solicitado').val(solicitado);
    modal.find('.modal-body #descripcion').val(descripcion);
    modal.find('.modal-body #ubicacion').val(ubicacion);
    modal.find('.modal-body #fecha').val(fecha);
    modal.find('.modal-body #hora').val(hora);
    
})
</script>


 <script>

  $.get('quien_reserva/',function(data){
var html_select = '<option value=""> Seleccione </option>'
for (var i = 0; i<data.length; i++){
  html_select += '<option value ="'+data[i].id_p+'"selected>'+data[i].nombre'</option>';
}
$('#persona').html(html_select);
});
  

</script>



<@stop