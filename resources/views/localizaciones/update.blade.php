<!-- Modal Editar-->
<div class="form-group col-md-12">
  <div class="row">
    <div class="col-1">
      <label for="title"><strong>ID: </strong></label>
      <p>{{$localizacion->id}}</p> 
      <input type="hidden" name="id" id="id" value="{{ $localizacion->id }}">
    </div>
    <div class="col-4">
      <label for="title"><strong>Area: </strong></label>
      <p>{{$localizacion->nombre_a}}</p>     
    </div>
    <div class="col-5">
      <label for="title"><strong>Nombre: </strong></label>
      <input type="text" name="nombre" class="form-control" autocomplete="off" id="nombre" value="{{ $localizacion->nombre }}">
    </div>
    <div class="col-2">
      <label for="title"><strong>Interno: </strong></label>
      <input type="text" name="interno" class="form-control" autocomplete="off" id="interno" value="{{ $localizacion->interno }}">
    </div>
  </div>
</div> 
