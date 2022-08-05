<!-- Modal Novedades-->
<div class="modal fade" id="novedad" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <form action="{{ action('HomeController@store_novedades') }}" method="POST">
      {{csrf_field()}}
      <div class="modal-body">
       <div class="row">
         <div class="col-md-12">
          <div class="form-row col-md-12">
            <div class="col-md-6" align="center">Fecha desde:
              <input type="date" name="fecha_desde" class="form-control" step="1" min="2019-01-01" value="<?php echo date("Y-m-d");?>">
            </div>

            <div class="col-md-6" align="center">Fecha hasta:
              <input type="date" name="fecha_hasta" class="form-control" step="1" min="2019-01-01" value="<?php echo date("Y-m-d");?>">
            </div>
          </div>
          <br>
          <div class="input-field col s12 ">Descripción:
            <textarea class="form-control" rows="5" name="descripcion" id="descripcion" value="{{old('descripcion')}}" maxlength="200" required></textarea>
            <div id="result" align="right"></div>
          </div>
          <div class="col-md-6">
            <input type="checkbox" value="1" checked id="enviar_correo" name="enviar_correo">
            <label for="enviar_correo">Enviar correos</label>
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