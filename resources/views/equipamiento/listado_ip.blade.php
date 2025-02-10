@extends('layouts.app')

@push('styles')

  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush


@section('content')
<div id="buscaip-container">
  <div class="col-md-12 ml-auto">
    <h1>
      <div class="form-inline pull-right">
        <form method="GET" action="{{ route('listado_ip') }}" class="form-inline">
          <div class="form-group">
            <label>
              <h6>Busqueda general:</h6>
            </label>
            <input type="text" name="search" class="form-control col-md-6" id="search1" autocomplete="off"
              placeholder="Buscar" value="{{ request('search') }}">
          </div>
          <button type="submit" class="btn btn-primary ml-2" id="asignar-btn">Buscar</button>
        </form>
      </div>
    </h1>
  </div>


  <div>
    <table id="test" role="grid" cellspacing="0" cellpadding="2" border="10">
      <thead>
        <th class="text-center">IP</th>
        <th class="text-center">Red</th>
        <th class="text-center">Equipamiento</th>
        <th class="text-center">Tipo</th>
        <th class="text-center">Usuario</th>
        <th class="text-center">Observación</th>
      </thead>

      <tbody>
        @foreach($equipamientos as $item)
      <tr>
        <td class="text-center">{{ $item['ip'] }}</td>
        <td class="text-center">{{ $item['nombre_red'] }}</td>
        <td class="text-center">@if ($item['id_equipamiento'] === 'Libre')
      <strong style="color: blue;">{{ $item['id_equipamiento'] }}</strong>
    @else
    {{ $item['id_equipamiento'] }}
  @endif
        </td>
        <td class="text-center">{{ $item['tipo'] }}</td>
        <td class="text-center">{{ $item['nombre'] }}</td>
        <td class="text-center">{{ $item['obs'] }}</td>

      </tr>
    @endforeach
      </tbody>
    </table>
  </div>

  {{$equipamientos->links('pagination::bootstrap-4')}}




</div>
@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ URL::asset('/js/jquery.min.js') }}"></script>

@endpush
@endsection