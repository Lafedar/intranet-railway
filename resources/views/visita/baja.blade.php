<!-- Modal baja-->
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="modal fade" id="baja" role="dialog" align="center">
  <div class="modal-dialog">
    <div class="modal-content">
      <p class="statusMsg"></p>
      <form action="{{action('VisitaController@baja', '')}}" method="POST">
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <label for="title">Numero de tarjeta:</label>
              <select class="form-control" name="id" id="id" required>
                <option value="">Seleccione una tarjeta</option>
                @if($tarjetas != null)
          @foreach($tarjetas as $tarjeta)
        <option> {{$tarjeta->id_tar}}</option>
      @endforeach
        @endif
              </select>
              <br>
              <button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cancelar</button>
              <button type="submit" class="btn btn-info" id="asignar-btn">Dar de baja</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>