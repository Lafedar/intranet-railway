<div class="modal fade" id="modalForm" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <form action="{{ action('IncidenteController@update_incidente', $incidente->id_i) }}" method="POST">
      {{csrf_field()}}
      <div class="modal-body">
       <div class="row">
         <div class="col-md-12">
          <input type="hidden" name="incidente" id="incidente">
          <label for="title">Solución:</label>
          <textarea class="form-control" rows="5" name="solucion" id="solucion" value="{{{ isset($incidente->solucion) ? $incidente->solucion : ''}}}"required></textarea> 
          <p></p>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Guardar</button>
        </div>
      </div>
    </div>
  </form>                
</div>
</div>
</div>
