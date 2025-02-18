@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

@section('content')
  <div id="guardia-consulta-container">
    <h1>
    <div class="form-inline pull-right">
      <form method="GET">
      <div class="form-group">
        <label>
        <h6><b>Tarjeta:</b></h6>
        </label>
        <input type="text" name="tarjeta" class="form-control col-md-1" id="tarjeta" value="{{$tarjeta}}">
        &nbsp
        <label>
        <h6><b>Visitante:</b></h6>
        </label>
        <input type="text" name="visitante" class="form-control col-md-2" id="visitante" value="{{$visitante}}">
        &nbsp
        <label>
        <h6><b>Visita a:</b></h6>
        </label>
        <input type="text" name="visita_a" class="form-control col-md-2" id="visita_a" value="{{$visita_a}}">
        &nbsp
        <label>
        <h6><b>Fecha:</b></h6>
        </label>
        <input type="date" name="fecha" class="form-control" step="1" min="2019-01-01" value="{{$fecha}}">
        &nbsp
        <select class="form-control" name="estado" id="estado" value="">
        @if($estado == 2 || $estado == null)
      <option value="2" selected>{{'Todas'}} </option>
      <option value="1">{{'Activas'}} </option>
      <option value="0">{{'Finalizadas'}} </option>
    @elseif($estado == 1)
    <option value="2">{{'Todas'}} </option>
    <option value="1" selected>{{'Activas'}} </option>
    <option value="0">{{'Finalizadas'}} </option>
  @else($estado == 0)
  <option value="2">{{'Todas'}} </option>
  <option value="1">{{'Activas'}} </option>
  <option value="0" selected>{{'Finalizadas'}} </option>
@endif
        </select>
        &nbsp
        <button type="submit" class="btn btn-default" id="asignar-btn"> Buscar</button>
      </form>
    </div>
    </h1>


    <div id="table-container">
    <table>
      <thead>
      <th class="text-center">Tarjeta</th>
      <th class="text-center">Visitante</th>
      <th class="text-center">Empresa</th>
      <th class="text-center">Visita a</th>
      <th class="text-center">Fecha</th>
      <th class="text-center">Desde</th>
      <th class="text-center">Hasta</th>
      <th class="text-center">Estado</th>
      <th class="text-center">Acciones</th>
      </thead>
      <tbody>
      @if(count($visitas))
      @foreach($visitas as $visita) 
      <tr>
      <td align="center" width="80">{{$visita->tarjeta}}</td>
      <td align="center">{{$visita->visitante_apellido . ' ' . $visita->visitante_nombre}}</td>
      <td align="center">{{$visita->empresa}}</td>
      <td align="center">{{$visita->visita_a_apellido . ' ' . $visita->visita_a_nombre}}</td>
      <td align="center">{!! \Carbon\Carbon::parse($visita->fecha_inicio)->format("d-m-Y") !!}</td>
      <td align="center" width="80">{!! \Carbon\Carbon::parse($visita->fecha_inicio)->format("H:i") !!}</td>
      @if($visita->fecha_inicio != $visita->fecha_fin)
      <td align="center" width="80">{!! \Carbon\Carbon::parse($visita->fecha_fin)->format("H:i") !!}</td>
    @else
      <td></td>
    @endif
      @if($visita->activa == 1)
      <td align="center">
      <h5 style="color:green">Activa</h5>
      </td>
    @else
      <td align="center">
      <h5>Finalizada</h5>
      </td>
    @endif
      <td align="center" width="80">
      <a href="#" data-toggle="modal" data-dni="{{$visita->dni_ext}}" data-target="#foto_externo" type="submit"
      title="Ver Foto"><img src="{{ asset('storage/cursos/foto.png') }}" alt="Ver Foto" id="img-icono"></a>
      </td>
      </tr>
    @endforeach
    @endif
      </tbody>
    </table>

    {{ $visitas->links('pagination::bootstrap-4') }} <!--paginacion-->
    </div>
    @include('visita.modal_foto_externo')
  </div>


@endsection
@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    $('#foto_externo').on('show.bs.modal', function (event) {
    document.getElementById("ver_foto").src = " ";
    var button = $(event.relatedTarget);
    var dni = button.data('dni');
    var modal = $(this);

    $.get('fotoExterno/' + dni, function (data) {
      var storage = "{{Storage::url(':fotito_reemplaza')}}";
      storage = storage.replace(':fotito_reemplaza', data[0].foto);
      foto = document.getElementById("ver_foto").src = storage;
    });
    })
  </script>
@endpush