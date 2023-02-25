<!-- Modal Agregar-->
<div class="modal fade" id="agregar_equipo_mant" role="dialog" aling="center">
  <div class="modal-dialog">
    <div class="modal-content">           
      <form action="{{ action('Equipo_mantController@store_equipo_mant') }}" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <input type="hidden" name="id">
              <div class="form-group col-md-12">

                <div class="row">
                  <div class="col">
                    <label for="title"><strong>Id equipamiento:</strong></label>
                    <input type="text" name="id_e" class="form-control" id="id_e" autocomplete="off" value="{{old('id_e')}}" min="6" required>
                  </div>
                  <div class="col">
                    <label for="title"><strong>Tipo:</strong></label>
                    <select class="form-control" name="tipo" id="tipo" required></select>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <label for="title"><strong>Marca:</strong></label>
                    <input type="text" name="marca" class="form-control" autocomplete="off" id="marca" value="{{old('marca')}}">
                  </div>
                  <div class="col">
                    <label for="title"><strong>Modelo:</strong></label>
                    <input type="text" name="modelo" class="form-control" autocomplete="off" id="modelo" value="{{old('modelo')}}">
                  </div>
                </div>
              
                <label for="title"><strong>Numero de Serie:</strong></label>
                <input type="text" name="num_serie" class="form-control" autocomplete="off" id="num_serie" value="{{old('num_serie')}}">
                  
                <label for="title"><strong>Descripcion:</strong></label>
                <textarea rows="3" type="text" class="form-control" name="descripcion" id="descripcion" required></textarea>

                <div class="row">
                  <div class="col-6">
                    <label for="title"><strong>Area:</strong></label>
                    <select class="form-control" name="area" id="area" required></select>
                  </div>
                  <div class="col-6" style="display:none;" id="div_localizacion">
                    <label for="title"><strong>Localizacion:</strong></label>
                    <select class="form-control" name="localizacion" id="localizacion" required></select>
                  </div>
                </div>

                <div>
                  <br>
                  <label for="title"><strong>Uso:</strong></label>
                  <input type="checkbox" name="uso" class="from-control" autocomplete="off" id="uso">
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


