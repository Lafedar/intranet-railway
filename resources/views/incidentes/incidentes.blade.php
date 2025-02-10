@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

@endpush

@section('content')
<div id="incidentes-container">
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

  <div>
    <h1>
      <div class="form-inline pull-right">
        <form method="GET">
          <div class="form-group">
            <div class="form-group">
              <h6>Equipamiento:</h6>
            </div>
            <input type="text" name="equipamiento" class="form-control" id="equipamiento" value="{{$equipamiento}}">
            &nbsp
            <div class="form-group">
              <select class="form-control" name="resuelto" id="resuelto" value="">
                <option value="2">{{'Todos'}} </option>
                <option value="1">{{'Resuelto'}} </option>
                <option value="0">{{'No Resuelto'}} </option>
              </select>
            </div>
            &nbsp
            <button type="submit" class="btn btn-default" id="asignar-btn"> Buscar</button>
        </form>
      </div>
    </h1>
  </div>

  <div id="incidentes-table">
    <table>
      <thead>
        <th class="text-center">Equipamiento</th>
        <th class="text-center">Fecha</th>
        <th class="text-center">Descripción</th>
        <th class="text-center">Solución</th>
        <th class="text-center">Acciones</th>
      </thead>
      <tbody>
        @if(count($incidentes))
      @foreach($incidentes as $incidente) 
      <tr>
      <td width="100" align="center">{{$incidente->equipamiento}}</td>
      <td align="center" width="107">{!! \Carbon\Carbon::parse($incidente->creado)->format("d-m-Y") !!}</td>
      <td>{{$incidente->descripcion}}</td>
      <td>{{$incidente->solucion}}</td>

      <td align="center" width="130">
      @if ($incidente->resuelto == 1)
      <h5>Resuelto &#10003</h5>

    @else
      <button class="btn btn-info btn-sm" data-id=" {{$incidente->id_i}}" data-toggle="modal"
      data-target="#modalForm">Resolver</button>
    @endif
      </td>
      </tr>
    @include('incidentes.resolver')          @endforeach
    @endif
      </tbody>
    </table>



    {{ $incidentes->links('pagination::bootstrap-4') }} <!--paginacion-->

  </div>

  @push('scripts')
  
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function (e) {
      $('#modalForm').on('show.bs.modal', function (e) {
        var id = $(e.relatedTarget).data().id;
        $(e.currentTarget).find('#incidente').val(id);
      });
    });
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