<!-- Modal Editar-->
<div class="form-group col-md-12">
  <div class="row">
    <div class="col">
      <strong>Eliminar falla al tipo {{$tipo_equipo->nombre}}: </strong>
      <br><br>
      <select class="form-control" name="fallasAsignadas" id="fallasAsignadas" required></select>
    </div>
    <input type="hidden" name="id_tipo_equipo" value="{{ $tipo_equipo->id }}">
  </div>
</div> 
