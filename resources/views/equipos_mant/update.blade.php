<!-- Modal Editar-->
<div class="form-group col-md-12">
  <div class="row">
    <div class="col">
      <label for="title"><strong>ID:</strong></label>
      <input type="text" name="id" class="form-control" id="id" autocomplete="off" value="{{$equipo_mant->id}}" min="6" required>
    </div>
    <div class="col">
      <label for="title"><strong>Tipo: </strong></label>
      <select class="form-control" name="tipo_equipo_mant_editar" id="tipo_equipo_mant_editar" required></select>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <label for="title"><strong>Marca:</strong></label>
      <input type="text" name="marca" class="form-control" autocomplete="off" id="marca" value="{{ $equipo_mant->marca }}" required>
    </div>
    <div class="col">
      <label for="title"><strong>Modelo:</strong></label>
      <input type="text" name="modelo" class="form-control" autocomplete="off" id="modelo" value="{{ $equipo_mant->modelo }}">
    </div>
  </div>
  <label for="title"><strong>Numero de Serie:</strong></label>
  <input type="text" name="num_serie" class="form-control" autocomplete="off" id="num_serie" value="{{ $equipo_mant->num_serie }}">
                  
  <label for="title"><strong>Descripcion:</strong></label>
  <textarea rows="3" type="text" class="form-control" name="descripcion" id="descripcion">{{ $equipo_mant->descripcion }}</textarea>

  <div class="row">
    <div class="col-6">
      <label for="title"><strong>Area:</strong></label>
      <select class="form-control" name="area_editar" id="area_editar" required></select>
    </div>
    <div class="col-6" id="div_localizacion">
      <label for="title"><strong>Localizacion:</strong></label>
      <select class="form-control" name="localizacion_editar" id="localizacion_editar" required></select>
    </div>
  </div>
  <div>
    <br>
    <label for="title"><strong>Uso:</strong></label>
    <input type="checkbox" name="uso" class="from-control" autocomplete="off" id="uso" value="1" {{ $equipo_mant->uso == 1 ? 'checked' : '' }}>
  </div>
  <input type="hidden" name="id_equipo_mant" value="{{ $equipo_mant->id }}">
</div> 
