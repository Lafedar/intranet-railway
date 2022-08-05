<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Datos de la reserva</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"> X </button>
      </div>
      <div class="modal-body">
        
        <div class="d-none">
        <input type="text" name="txtID" id="txtID" >
     
        <input type="text" class="form-control" name="txtFecha" id="txtFecha"  >

        <input type="color" class="form-control" name="txtColor" id="txtColor" autocomplete="off" >
        </div>

        <div class="form-row">
       
          <div class="form-group col-md-9">
          <label>Titulo: </label>
          <input type="text" class="form-control" name="txtSala" id="txtSala" autocomplete="off" required>
          </div>
      

          <div class="form-group col-md-4">
          <label>Hora comienzo:</label>
          <input type="time" step= "600"class="form-control" name="txtHoras" id="txtHoras"  autocomplete="off" required>
          </div>
        
          <div class="form-group col-md-4">
          <label>Hora fin:     </label>
          <input type="time" step= "600"class="form-control" name="txtHoraf" id="txtHoraf"  autocomplete="off" required>
          </div>


        <div class="form-group col-md-8">
        <label> Solicitado por:</label>
        <input type="text"  class="form-control" name="txtPedido_por" id="txtPedido_por" autocomplete="off" required>
        </div>

        <div class="form-group col-md-4">
        <label> Reserva sala:</label>
        <select name="txtTitulo" class="form-control"  id="txtTitulo" required> 
                  <option value="Sala Vidriada">Sala Vidriada</option> 
                  <option value="Auditorio">Auditorio</option> 
                  <option value="Sala Protocolar">Sala Protocolar</option>
                  <option value="Sala Vidridada 2">Sala Vidridada 2</option> 
                  <option value="Sala Compras">Sala Compras</option>
            </select>
        </div>

        <div class="form-gorup col-md-12">
        <label> Descripcion:</label>
        <textarea name="txtDescripcion" class="form-control" id="txtDescripcion" cols="20" rows="8" autocomplete="off"></textarea>
        </div>
        
        
        
      <div class="modal-footer">
      	<button id="btnAgregar" class="btn btn-success">Agregar</button>
      	<button id="btnModificar" class="btn btn-warning">Modificar</button>
      	<button id="btnBorrar" class="btn btn-danger">Borrar</button>
      	<button id="btnCancelar"  data-dismiss="modal"class="btn btn-default">Cancelar</button>
      </div>
    </div>
  </div>
</div>


