<!-- Modal Agregar rol-->
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="modal fade" id="agregar_rol" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <p class="statusMsg"></p>
    <form action="{{action('RolController@store_rol', '')}}" method="POST" >
      {{csrf_field()}}
      <div class="modal-body">
       <div class="row">
         <div class="col-md-12 form-group">
          <label>Nombre nuevo rol:</label>
          <input type="text" name="nombre_rol" class="form-control">
          <br>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cancelar</button>
          <button type="submit" class="btn btn-info" id="asignar-btn">Agregar</button>
        </div>
      </div>
    </div>
  </form>                
</div>
</div>
</div>