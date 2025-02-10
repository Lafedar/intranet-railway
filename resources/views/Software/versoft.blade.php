@extends('layouts.app')

@push('styles')
  
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

@section('content')
<div id="versof-container">
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
            <label>
              <h6>Equipo:</h6>
            </label>
            <input type="text" name="equipo" class="form-control col-md-1" id="equipo" autocomplete="off"
              value="{{$equipo}}">
            &nbsp
            <label>
              <h6>Software:</h6>
            </label>
            <input type="text" name="software" class="form-control col-md-1" id="software" autocomplete="off"
              value="{{$software}}">
            &nbsp
            <label>
              <h6>Version:</h6>
            </label>
            <input type="text" name="version" class="form-control col-md-1" id="version" autocomplete="off"
              value="{{$version}}">
            &nbsp
            <label>
              <h6>Licencia:</h6>
            </label>
            <input type="text" name="licen" class="form-control col-md-1" id="licen" autocomplete="off"
              value="{{$licen}}">
            &nbsp
            <button type="submit" class="btn btn-default" id="asignar-btn"> Buscar</button>
          </div>
        </form>
      </div>
    </h1>
  </div>
  <div>
    <table id="test" role="grid" cellspacing="0" cellpadding="2" border="10">
      <thead>
        <th class="text-center">Id_Eq</th>
        <th class="text-center">Software</th>
        <th class="text-center">Version</th>
        <th class="text-center">Licencia</th>
        <th class="text-center">Observaciones</th>
        <th class="text-center">Desinstalar</th>
      </thead>
      <tbody>
        @if(count($tabla_soft))
      @foreach($tabla_soft as $tabla_soft) 
      @if($tabla_soft->estado == 1)
      <tr>
      <td align="center"> {{$tabla_soft->equipo}}</td>
      <td align="center"> {{$tabla_soft->software}}</td>
      <td align="center"> {{$tabla_soft->version}}</td>
      <td align="center"> {{$tabla_soft->licen}}</td>
      <td align="center"> {{$tabla_soft->obs}}</td>
      <td align="center" width="140">
      <form action="{{route('destroy_srelacions', $tabla_soft->id)}}" method="put">
      <div class="botones">
      <!-- Boton para eliminar asignacion de equipo -->
      <button class="fa-solid fa-xmark eliminar" title="Eliminar"
      onclick="return confirm ('Está seguro que desea eliminar el archivo?')" data-tooltip="Borrar"
      id="icono"> <img src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar"
      id="img-icono"></button>
      </div>
      </form>
      </tr>
    @endif
    @endforeach
    @endif
      </tbody>
    </table>
  </div>


</div>
@endsection
@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

@endpush
