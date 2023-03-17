<!-- Modal Editar-->
<div class="form-group col-md-12">
  <div class="row">
    <div class="col">
      <strong>Asignar solictud {{$solicitud->id}} a: </strong>
      <br><br>
      <select class="form-control" name="user" id="user" required></select>
    </div>
    <input type="hidden" name="id_solicitud" value="{{ $solicitud->id }}">
  </div>
</div> 
