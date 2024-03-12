<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Datos de la reserva</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="txtID" id="txtID" class="d-none">
        <input type="text" class="form-control d-none" name="txtFecha" id="txtFecha">
        
        <div class="form-group">
          <label>Titulo: </label>
          <input type="text" class="form-control" name="txtSala" id="txtSala" autocomplete="off" required>
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
          <select class="form-control" name="id_persona" id="id_persona">
            <option value="0">Selecciona una persona</option>
            @foreach($personas as $persona)
              <option value="{{$persona->id_p }}">{{$persona->nombre_p . ' '. $persona->apellido}}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label> Reserva sala:</label>
          <select name="txtTitulo" class="form-control" id="txtTitulo" required>
            <option value="Sala Vidriada">Sala Vidriada</option>
            <option value="Auditorio">Auditorio</option>
            <option value="Sala Protocolar">Sala Protocolar</option>
            <option value="Sala Vidridada 2">Sala Vidridada 2</option>
            <option value="Sala Compras">Sala Compras</option>
          </select>
        </div>

        <div class="form-group">
          <label> Descripcion:</label>
          <textarea name="txtDescripcion" class="form-control" id="txtDescripcion" cols="20" rows="8" autocomplete="off"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button id="btnAgregar" class="btn btn-success">Agregar</button>
        <button id="btnBorrar" class="btn btn-danger">Borrar</button>
        <button id="btnModificar" class="btn btn-warning">Modificar</button>
        <button id="btnCancelar" data-dismiss="modal" class="btn btn-default">Cancelar</button>
        
      </div>
    </div>
  </div>
</div>
