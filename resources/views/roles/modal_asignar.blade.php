  <div class="modal fade" id="asignar_permiso" role="dialog" align="center">
    <div class="modal-dialog">
     <div class="modal-content">           
      <form action="{{action('RolController@asignar_permiso', '')}}" method="POST" >
        {{csrf_field()}}
        <div class="modal-body">
         <div class="row">
           <div class="col-md-12">
            <strong><output class="headertekst" type="text"  name="nombre" id="nombre"></strong>
              <hr>
              <input type="hidden" name="id" id="id" value="">  
              <select class="form-control" name="permiso"  id="select_permiso"required>
              </select>
              <p></p>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-info">Asignar</button>
            </div>
          </div>
        </div>
      </form>                
    </div>
  </div>
</div>