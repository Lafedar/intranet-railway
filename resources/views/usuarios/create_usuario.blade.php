<!-- Modal Agregar usuario-->
<div class="modal fade" id="agregar_usuario" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content"> 
    <p class="statusMsg"></p>          
      <form action="{{action('UsuarioController@store_usuario')}}" method="POST">
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col">
                  <label for="title">Nombre:</label>
                  <select class="form-control" name="nombre_p" id="nombre_p"></select>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label for="title">Correo electrónico:</label>
                  <select class="form-control" name="correo" id="correo"></select>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label for="title">Contraseña:</label>
                  <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label for="title">Confirmar contraseña:</label>
                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>
              </div>
              <p></p>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-info">Agregar</button>
            </div>
          </div>
        </div>
      </form>                
    </div>
  </div>
</div>