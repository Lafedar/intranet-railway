<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <div class="modal fade" id="revocar_rol" role="dialog" align="center">
    <div class="modal-dialog">
     <div class="modal-content">           
      <form action="{{action('UsuarioController@revocar_rol', '')}}" method="POST" >
        {{csrf_field()}}
        <div class="modal-body">
         <div class="row">
           <div class="col-md-12">
            <strong><output class="headertekst" type="text"  name="nombre" id="nombre"></strong>
            <hr>
            <input type="hidden" name="id" id="id" value="">  
            <select class="form-control" name="rol"  id="select_revocar_rol"required>
            </select>
            <p></p>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cerrar</button>
            <button type="submit" class="btn btn-info" id="asignar-btn">Revocar</button>
          </div>
        </div>
      </div>
    </form>                
  </div>
</div>
</div>
