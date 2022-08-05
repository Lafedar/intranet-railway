<div class="modal fade" id="incidente" role="dialog" align="center">
    <div class="modal-dialog">
     <div class="modal-content">           
        <form action="{{ action('IncidenteController@store_incidente') }}" method="POST" autocomplete="off">
         {{csrf_field()}}
         <div class="modal-body">
             <div class="row">
                 <div class="col-md-12">
                      <input type="hidden" name="equipamiento" class="form-control" id="equipamiento" >
                  <div class="input-field col s12 ">Descripción:
                      <textarea class="form-control" rows="5" name="descripcion" id="descripcion" required></textarea>
                  </div>
                  <div class="input-field col s12 ">Solución:
                      <textarea class="form-control" rows="5" name="solucion" id="solucion"></textarea>
                  </div>
                  <p></p>
                  <div class="col-md-6">
                      <input type="checkbox" value="1" checked id="resuelto" name="resuelto">
                      <label for="resuelto">Resuelto</label>
                  </div> 
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-info">Guardar</button>
              </div>
          </div>
      </div>
  </form>                
</div>
</div>
</div>