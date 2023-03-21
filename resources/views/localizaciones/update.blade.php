<!-- Modal Editar-->
<div class="form-group col-md-12">
  <div class="row">
    <div class="col">
      <label for="title"><strong>ID: </strong></label>
      <p>{{$localizacion->id}}</p> 
      <input type="hidden" name="id_a" id="id_a" value="{{ $localizacion->id }}">
    </div>
    <div class="col-9">
      <label for="title"><strong>Nombre: </strong></label>
      <input type="text" name="nombre_a" class="form-control" autocomplete="off" id="nombre_a" value="{{ $localizacion->nombre }}">
    </div>
  </div>
</div> 
