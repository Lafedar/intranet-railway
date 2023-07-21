<!-- Modal Update-->
<div class="form-group col-md-12">
  <input type="hidden" name="id_puesto" id="id_puesto" value="{{ $puesto->id_puesto }}">

  <div class="row">
    <div class="col">
      <label for="desc_puesto" class="form-label"><strong>Nombre de puesto:</strong></label>
      <input type="text" name="desc_puesto" class="form-control" id="desc_puesto" minlength="1" maxlength="100" required>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label for="area" class="form-label"><strong>Area:</strong></label>
      <select class="form-control" name="area" id="area" required></select>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label for="localizacion" class="form-label"><strong>Localizacion :</strong></label>
      <select class="form-control" name="localizacion" id="localizacion" required></select>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label for="persona" class="form-label"><strong>Persona:</strong></label>
      <select class="form-control" name="persona" id="persona" required></select>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label for="obs" class="form-label"><strong>Observación:</strong></label>
      <input type="text" name="obs" class="form-control" id="obs" maxlength="100">
    </div>
  </div>
</div>



