<!-- Modal Editar-->
<div class="form-group col-md-12">
  <div class="row">
    <div class="col">
      <label for="title"><strong>ID: </strong></label>
      <p>{{$tipo_solicitud->id}}</p> 
      <input type="hidden" name="id" id="id" value="{{ $tipo_solicitud->id }}">
    </div>
    <div class="col-9">
      <label for="title"><strong>Nombre: </strong></label>
      <input type="text" name="nombre" class="form-control" autocomplete="off" id="nombre" value="{{ $tipo_solicitud->nombre }}">
    </div>
  </div>
</div> 
