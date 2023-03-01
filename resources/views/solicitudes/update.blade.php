<!-- Modal Editar-->
<form action="{{ route('update_solicitud') }}" method="GET" enctype="multipart/form-data">
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
      <input type="text" name="obs" class="form-control" id="obs" autocomplete="off" required>
    </div>
  </div>
</div> 
</form>