<!-- Modal Editar-->
<div class="form-group col-md-12">
  <div class="row">
    <div class="col-md-1">
      <strong>ID: </strong>
      <p>{{$solicitud->id}}</p>
    </div>
    <div class="col-md-4">
      <strong>Estado: </strong>
      <select class="form-control" name="estado" id="estado" required></select>
    </div>
    <div class="col-md-7">
      <strong>Descripcion: </strong>
      <input type="text" name="descripcion" class="form-control" id="descripcion" autocomplete="off" required>
      <input type="hidden" name="id_solicitud" value="{{ $solicitud->id }}">
    </div>
  </div>
</div> 
