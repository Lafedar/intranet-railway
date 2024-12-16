<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
 <div class="modal fade" id="revocar_permiso" role="dialog" align="center">
    <div class="modal-dialog">
     <div class="modal-content">           
      <form action="{{action('RolController@revocar_permiso', '')}}" method="POST" >
        {{csrf_field()}}
        <div class="modal-body">
         <div class="row">
           <div class="col-md-12">
            <input type="hidden" name="id" id="id" value="">  
            <strong><output class="headertekst" type="text"  name="nombre" id="nombre"></strong>
            <hr>
            <select class="form-control" name="permiso"  id="select_revocar_permiso"required>
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
