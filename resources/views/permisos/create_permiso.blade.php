<!-- Modal Agregar permiso-->
<div class="modal fade" id="agregar_permiso" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <p class="statusMsg"></p>
    <form action="{{action('RolController@store_permiso', '')}}" method="POST" >
      {{csrf_field()}}
      <div class="modal-body">
       <div class="row">
         <div class="col-md-12 form-group">
          <label>Nombre nuevo permiso:</label>
          <input type="text" name="nombre_permiso" class="form-control">
          <br>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Agregar</button>
        </div>
      </div>
    </div>
  </form>                
</div>
</div>
</div>

