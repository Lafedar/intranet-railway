<!-- Modal Update-->
<div class="form-group col-md-12">
  <input type="hidden" name="id_puesto" id="id_puesto" value="{{ $puesto->id_puesto }}">

  <div class="row">
    <div class="col">
      <label for="desc_puesto" class="form-label"><strong>Nombre de puesto:</strong></label>
      <input type="text" name="desc_puesto1" class="form-control" id="desc_puesto1" minlength="1" maxlength="100" required>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label for="area" class="form-label"><strong>Area:</strong></label>
      <select class="form-control" name="area1" id="area1" required></select>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label for="localizacion" class="form-label"><strong>Localizacion :</strong></label>
      <select class="form-control" name="localizacion1" id="localizacion1" required></select>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label for="persona" class="form-label"><strong>Persona:</strong></label>
      <select class="form-control" name="persona1" id="persona1" required></select>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label for="obs" class="form-label"><strong>Observación:</strong></label>
      <input type="text" name="obs1" class="form-control" id="obs1" maxlength="100">
    </div>
  </div>
</div>



