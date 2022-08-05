<div class="modal fade" id="ver_s" role="dialog" align="center">
    <div class="modal-dialog">
     <div class="modal-content">           
        <form action="{{ action('SoftwareController@store_srelacions') }}" method="POST">
        {{csrf_field()}}
         <div class="modal-body">
             <div class="row">
                 <div class="col-md-12">
                  <div class="input-field col s12 ">Equipo:
                      <input class="form-control" rows="5" name="equipo" id="equipamiento" required></input>
                  </div>
                  <div class="input-field col s12 ">Software:
                     <select class="form-control"  name="softw" id="ssoftware"></select>
                  </div>
                  <div class="input-field col s12 ">Licencia:
                     <input class="form-control"  name="licen" id="licen"></input>
                  </div>
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

  