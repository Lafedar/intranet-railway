<!-- Modal Editar-->
<div class="form-group col-md-12">
  <div class="row">
    <div class="col">
      <label for="title"><strong>ID: </strong></label>
      <p>{{$falla->id}}</p> 
      <input type="hidden" name="id" id="id" value="{{ $falla->id }}">
    </div>
    <div class="col-9">
      <label for="title"><strong>Nombre: </strong></label>
      <input type="text" name="nombre" class="form-control" autocomplete="off" id="nombre" value="{{ $falla->nombre }}">
    </div>
  </div>
</div> 
