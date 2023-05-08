<div class="form-group col-md-12">
  <div class="row">
    <div class="col-md-2">
      <label for="id_solicitud" class="form-label"><strong>ID:</strong></label>
      <p class="form-control-static">{{$solicitud->id}}</p>
      <input type="hidden" name="id_solicitud" id="id_solicitud" value="{{ $solicitud->id }}">
    </div>
    <div class="col-md-4">
      <label for="estado" class="form-label"><strong>Estado:</strong></label>
      <select class="form-control" name="estado" id="estado" required></select>
    </div>
    <div class="col-md-6">
      <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
      <input type="text" name="descripcion" class="form-control" id="descripcion" minlength="10" maxlength="500" required>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <label for="rep" class="form-label"><strong>Repuestos:</strong></label>
      <div class="form-check">
        <input type="checkbox" name="rep" class="form-check-input" id="rep" value="1">
        <label class="form-check-label" for="rep">Sí</label>
      </div>
    </div>
    <div class="col-md-9" style="display:none;" id="divDescRep">
      <label for="descripcionRep" class="form-label"><strong>Descripción de repuestos:</strong></label>
      <input type="text" name="descripcionRep" class="form-control" id="descripcionRep" minlength="10" maxlength="500">
    </div>
  </div>
</div>