<!-- Modal Editar-->
<div class="modal fade" id="editar" role="dialog" aling="center">
  <div class="modal-dialog">
    <div class="modal-content">     
      <form action="{{ route('update_costos')}}" method="POST" enctype="multipart/form-data">      
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <input type="hidden" name="id" id="id" value="">
              <div class="form-group col-md-12">
                <label for="title"><strong>Titulo:</strong></label>
                <textarea rows="3" type="text" class="form-control" name="titulo" id="titulo" required></textarea>
                <label for="title"><strong>Fecha de archivo:</strong></label>
                <input type="date" name="fecha"  class="form-control col-md-5" step="1" min="2019-01-01" value="<?php echo date("Y-m-d");?>">
                <label for="title"><strong>Observación:</strong></label>
                <textarea rows="3" type="text" class="form-control" name="obs" id="obs"></textarea>
                <label for="title"><strong>Frecuencia de actualización:</strong></label>
                <select class="form-control" name="frecuencia" id="frecuencia">
                  @foreach($frecuencias as $frecuencia)
                    <option value="{{ $frecuencia['id'] }}">{{$frecuencia['frecuencia']}}</option>
                  @endforeach
                </select>
                <br>
                <label for="title"><strong>Power Bi:</strong></label>
                <input type="file"  name="pbix" accept=".pbix" id="pbix">
                <div class="elim_pbix">                   
                  <label for="eliminar_pbix">Eliminar</label>
                  <input type="checkbox"value="1" id="eliminar_pbix" name="eliminar_pbix">
                </div>
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