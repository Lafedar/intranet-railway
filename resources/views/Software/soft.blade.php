@extends('layouts.app')

@push('styles')

<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

@section('content')
<div id="software-container"> 
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
              <h6>Software:</h6>
              <input type="text" name="software" class="form-control" id="software" value="{{$software}}">
            </div>
            &nbsp
            <div class="form-group">
              <h6>Version:</h6>
              <input type="text" name="version" class="form-control" id="version" value="{{$version}}">
            </div>
            &nbsp
            <button type="submit" class="btn btn-default" id="asignar-btn"> Buscar</button>
          </div>
        </form>
      </div>
    </h1>
  </div>
  <div>
    <table>
      <thead>
        <th class="text-center">Id_Soft</th>
        <th class="text-center">Software</th>
        <th class="text-center">Version</th>
        <th class="text-center">Licencia</th>
        <th class="text-center">T de L</th>
        <th class="text-center">Fecha</th>
        <th class="text-center">Observacion</th>
        <th class="text-center">Acciones</th>
      </thead>
      <tbody>
        @if(count($software))
      @foreach($software as $software) 
      <tr>
      <td align="center">{{$software->id_s}}</td>
      <td align="center">{{$software->Software}}</td>
      <td align="center">{{$software->Version}}</td>
      <td align="center">{{$software->Licencia}}</td>
      <td align="center">{{$software->t_Licencia}}</td>
      <td align="center">{{$software->fecha_inst}}</td>
      <td align="center">{{$software->Obs}}</td>
      <td align="center" width="140">
      <div class="botones">
        <!-- Boton para editar software -->
        <a href="#" title="Editar" class="fa-solid fa-pen default" title="Editar" data-toggle="modal"
        data-id="{{$software->id_s}}" data-software="{{$software->Software}}"
        data-version="{{$software->Version}}" data-licencia="{{$software->Licencia}}"
        data-tlicencia="{{$software->t_Licencia}}" data-fecha_inst="{{$software->fecha_inst}}"
        data-obs="{{$software->Obs}}" data-target="#edit_soft" type="submit"></a>
      </div>
      </tr>
    @endforeach
    @endif
      </tbody>
    </table>
  </div>

  @include ('software.edit')
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> 
<script>
  $('#edit_soft').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var software = button.data('software')
    var version = button.data('version')
    var licencia = button.data('licencia')
    var tlicencia = button.data('tlicencia')
    var fecha_inst = button.data('fecha_inst')
    var obs = button.data('obs')
    var modal = $(this)
    modal.find('.modal-body #id_s').val(id);
    modal.find('.modal-body #Software').val(software);
    modal.find('.modal-body #Version').val(version);
    modal.find('.modal-body #Licencia').val(licencia);
    modal.find('.modal-body #t_Licencia').val(tlicencia);
    modal.find('.modal-body #fecha_inst').val(fecha_inst);
    modal.find('.modal-body #Obs').val(obs);

  
  });
</script>
@endpush
