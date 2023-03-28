<!-- Modal Editar-->
<div class="form-group col-md-12">
  <div class="row">
    <div class="col">
      <label for="title"><strong>ID: </strong></label>
      <p>{{$estado->id}}</p> 
      <input type="hidden" name="id" id="id" value="{{ $estado->id }}">
    </div>
    <div class="col-9">
      <label for="title"><strong>Nombre: </strong></label>
      <input type="text" name="nombre" class="form-control" autocomplete="off" id="nombre" value="{{ $estado->nombre }}">
    </div>
  </div>
</div> 
