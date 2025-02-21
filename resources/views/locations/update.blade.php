<!-- Modal Editar-->
<div class="form-group col-md-12">
  <div class="row">
    <div class="col-1">
      <label for="title"><strong>ID: </strong></label>
      <p>{{$location->id}}</p> 
      <input type="hidden" name="id" id="id" value="{{ $location->id }}">
    </div>
    <div class="col-4">
      <label for="title"><strong>Area: </strong></label>
      <p>{{$location->nombre_a}}</p>     
    </div>
    <div class="col-5">
      <label for="title"><strong>Nombre: </strong></label>
      <input type="text" name="name" class="form-control" autocomplete="off" id="name" value="{{ $location->nombre }}" required>
    </div>
    <div class="col-2">
      <label for="title"><strong>Interno: </strong></label>
      <input type="text" name="internal" class="form-control" autocomplete="off" id="internal" value="{{ $location->interno }}">
    </div>
  </div>
</div> 