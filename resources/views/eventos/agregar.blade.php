<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Datos de la reserva</h5>
        
      </div>
      <div class="modal-body">
        <input type="text" name="txtID" id="txtID" class="d-none">
        <input type="text" class="form-control d-none" name="txtFecha" id="txtFecha">
        <input type="color" name="txtColor" id="txtColor" autocomplete="off" class="d-none">
        <div class="form-group">
          <label>Titulo: </label>
          <input type="text" class="form-control" name="txtTitulo" id="txtTitulo" autocomplete="off" required>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Hora comienzo:</label>
            <input type="time" step="600" class="form-control" name="txtHoras" id="txtHoras" autocomplete="off" required>
          </div>
          <div class="form-group col-md-6">
            <label>Hora fin:</label>
            <input type="time" step="600" class="form-control" name="txtHoraf" id="txtHoraf" autocomplete="off" required>
          </div>
        </div>

        <div class="form-group">
    <label for="personas" style="display: block; margin-bottom: 5px;">Solicitado por:</label>
    <select class="form-control" name="txtPedido_por" id="txtPedido_por">
        <option value="" disabled selected>Selecciona una persona</option>
        @foreach($personas as $persona)
            <option value="{{$persona->id_p}}">{{$persona->apellido . ' '. $persona->nombre_p}}</option>
        @endforeach
    </select>
</div>

        <div class="form-group">
          <label> Reserva sala:</label>
          <select name="txtSala" class="form-control" id="txtSala" required>
            <option value="" disabled selected>Selecciona una sala</option>
            @foreach($salas as $sala)
              <option value="{{$sala->nombre}}">{{$sala->nombre}}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label> Descripcion:</label>
          <textarea name="txtDescripcion" class="form-control" id="txtDescripcion" cols="20" rows="8" autocomplete="off"></textarea>
        </div>
      </div>
     
      <div class="modal-footer d-flex justify-content-center">
        <button id="btnCancelar" data-dismiss="modal" class="btn btn-default mx-1">Cancelar</button>
        <button id="btnAgregar" class="btn btn-success mx-1">Agregar</button>
        <button id="btnBorrar" class="btn btn-danger mx-1">Borrar</button>
        <button id="btnModificar" class="btn btn-warning mx-1">Modificar</button>
      </div>

    </div>
  </div>
</div>
