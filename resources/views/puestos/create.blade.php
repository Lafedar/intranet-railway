<div class="modal fade" id="agregar_puesto" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <form action="{{ action('PuestoController@store') }}" method="POST">
      {{csrf_field()}}
      <div class="modal-body">
       <div class="row">
         <div class="col-md-12">
          <div class="row">
            <div class="col">
              <label for="title">Descripción:</label>
              <input type="text" name="desc_puesto" class="form-control" id="desc_puesto" autocomplete="off" required>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label for="title">Area:</label>
              <select class="form-control" name="area"  id="area"></select>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label for="title">Persona:</label>
              <select class="form-control" name="persona"  id="persona"></select>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label for="title">Observación:</label>
              <input type="text" name="obs" class="form-control" id="obs" autocomplete="off">
            </div>
          </div>
          <div class="col-md-6">
            <input type="checkbox" value="1" checked id="telefono_ip" name="telefono_ip">
            <label for="telefono_ip">Telefono</label>
          </div>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Agregar</button>
        </div>
      </div>
    </div>
  </form>                
</div>
</div>
</div>