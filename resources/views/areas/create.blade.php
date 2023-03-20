<!-- Modal Agregar-->
<div class="modal fade" id="agregar_area" role="dialog" aling="center">
  <div class="modal-dialog">
    <div class="modal-content">           
      <form action="{{ action('AreaController@store_area') }}" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <input type="hidden" name="id">
              <div class="form-group col-md-12">
                <div class="row">
                  <div class="col">
                    <label for="title"><strong>ID: </strong></label>
                    <input type="text" name="id_a" class="form-control" id="id_a" autocomplete="off"  minlength="3" maxlength="3" required>
                  </div>
                  <div class="col">
                    <label for="title"><strong>Nombre: </strong></label>
                    <input type="text" name="nombre" class="form-control" id="nombre" autocomplete="off" required>
                  </div>
                </div>
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


