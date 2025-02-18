@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

@section('content')
  <div>
    @if(Session::has('message'))
    <div class="content" id="div.alert">
    <div class="row">
      <div class="col-1"></div>
      <div class="alert {{Session::get('alert-class')}} col-10 text-center" role="alert">
      {{Session::get('message')}}
      </div>
    </div>
    </div>
  @endif

    <div id="medico-nav">
    <div id="medico-buttons">
      <a href="{{route('medico.create')}}" class="btn btn-info btn-xl" data-position="top" data-delay="50"
      id="btn-agregar">Nueva
      Consulta</a>
      &nbsp
      <a href="/historia_clinica" class="btn btn-info btn-xl" data-position="top" data-delay="50"
      id="btn-agregar">Historia Clinica</a>
    </div>

    <h1>
      <div class="form-inline pull-right">
      <form route="{{ 'medico.index'}}" method="GET">
        <div class="form-group">
        <div class="form-group">
          <h6><b>Paciente:</b></h6>
          <input type="text" name="paciente" class="form-control" id="paciente" value="{{$paciente}}">
        </div>
        &nbsp
        <div class="form-group">
          <h6><b>Fecha:</b></h6>
          <input type="date" name="fecha" class="form-control" step="1" min="2019-01-01" value="{{$fecha}}">
        </div>
        &nbsp
        <button type="submit" class="btn btn-default" id="asignar-btn">Buscar consulta</button>
      </form>
      </div>
    </h1>
    </div>

    <div id="table-container">
    <table>
      <thead>
      <th class="text-center">Paciente</th>
      <th class="text-center ">Fecha de consulta</th>
      <th class="text-center">Motivo</th>
      <th class="text-center">Peso</th>
      <th class="text-center">Talla</th>
      <th class="text-center">Tension</th>
      <th class="text-center">IMC</th>
      <th class="text-center">Acciones</th>
      </thead>
      <tbody>
      @if(count($consultas))
      @foreach($consultas as $consulta) 
      <tr>
      <td> {{$consulta->apellido_paciente . ' ' . $consulta->nombre_paciente}}</td>
      <td align="center"> {!! \Carbon\Carbon::parse($consulta->fecha)->format("d-m-Y") !!}</td>
      <td align="center"> {{$consulta->motivo}}</td>
      <td align="center"> {{$consulta->peso}}</td>
      <td align="center"> {{$consulta->talla}}</td>
      <td align="center"> {{$consulta->tension}}</td>
      <td align="center"> {{$consulta->imc}}</td>
      <td align="center" width="200">

      <a href="#" data-fecha="{!! \Carbon\Carbon::parse($consulta->fecha)->format('d-m-Y') !!}"
      data-nombre="{{$consulta->nombre_paciente . ' ' . $consulta->apellido_paciente}}"
      data-motivo="{{$consulta->motivo}}" data-peso="{{$consulta->peso}}" data-talla="{{$consulta->talla}}"
      data-tension="{{$consulta->tension}}" data-imc="{{$consulta->imc}}" data-obs="{{$consulta->obs}}"
      data-toggle="modal" data-target="#ver" title="Ver"> <img src="{{ asset('storage/cursos/ver.png') }}"
      alt="Ver" id="img-icono"></a>
      <a href="{{route('medico.edit', $consulta->id)}}" data-position="top" data-delay="50" data-tooltip="Ver"
      title="Editar"><img src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono"></a>
      <a href="{{url('reporte_medico', $consulta->ip_paciente)}}" data-position="top" data-delay="50"
      data-tooltip="Reporte" title="Reporte"><img src="{{ asset('storage/cursos/documentos.png') }}"
      alt="Reporte" id="img-icono"></a>
      </td>
      </tr>
    @endforeach
    @endif

      </tbody>
    </table>

    @include('medico.show')
    {{ $consultas->links('pagination::bootstrap-4') }} <!--paginacion-->

    </div>

  </div>


  @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    $('#ver').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget)
    var nombre = button.data('nombre')
    var fecha = button.data('fecha')
    var apellido = button.data('apellido')
    var motivo = button.data('motivo')
    var peso = button.data('peso')
    var talla = button.data('talla')
    var tension = button.data('tension')
    var imc = button.data('imc')
    var obs = button.data('obs')
    var modal = $(this)

    modal.find('.modal-body #nombre').val(nombre);
    modal.find('.modal-body #fecha').val(fecha);
    modal.find('.modal-body #apellido').val(apellido);
    modal.find('.modal-body #motivo').val(motivo);
    modal.find('.modal-body #peso').val(peso);
    modal.find('.modal-body #talla').val(talla);
    modal.find('.modal-body #tension').val(tension);
    modal.find('.modal-body #imc').val(imc);
    modal.find('.modal-body #obs').val(obs);
    })
    </script>

    <script>
    $("document").ready(function () {
    setTimeout(function () {
      $("div.alert").fadeOut();
    }, 5000); // 5 secs

    });
    </script>
  @endpush
@endsection