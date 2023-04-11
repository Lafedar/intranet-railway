<!-- Modal Agregar-->
            <div class="col-md-12">
              <input type="hidden" name="id">
              <div class="form-group col-md-12">
                <label for="title"><strong>Titulo:</strong></label>
                <input type="text" name="titulo" class="form-control"  autocomplete="off" id="titulo" minlength="10" maxlength="50" required>

                <label for="title"><strong>Descripcion:</strong></label>
                <textarea rows="3" type="text" class="form-control" name="descripcion" id="descripcion"  minlength="10" maxlength="500" required></textarea>
                
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
              
                <div class="row" >
                  <div class="col-6" style="display:none;" id="div_tipo_solicitud">
                    <label for="title"><strong>Tipo de solicitud:</strong></label>
                    <select class="form-control" name="tipo_solicitud" id="tipo_solicitud" required></select>
                  </div>
                  <div class="col-6" style="display:none;" id="div_equipo">
                    <label for="title" ><strong>Equipo:</strong></label>
                    <select class="form-control" name="equipo" id="equipo"></select>
                  </div>
                  <div class="col-6" style="display:none;" id="div_falla">
                    <label for="title"><strong>Fallas:</strong></label>
                    <select class="form-control" name="falla" id="falla" required></select>
                  </div>
                </div>
                <input type="hidden" name="solicitante" value="{{ Auth::id() }}">
              </div> 
            </div>
        



