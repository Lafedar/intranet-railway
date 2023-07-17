<!-- Modal Agregar-->
<div class="col-md-12">
  <input type="hidden" name="idSolicitud1" id="idSolicitud1">
  <input type="hidden" name="estado1" id="estado1">
  <div class="form-group col-md-12">
    <label for="title"><strong>Titulo:</strong></label>
    <input type="text"  class="form-control" autocomplete="off" name="titulo1" id="titulo1" minlength="10" maxlength="50" required>

    <label for="title"><strong>Descripcion:</strong></label>
    <textarea type="text" rows="3" class="form-control" name="descripcion1" id="descripcion1"  minlength="10" maxlength="500" required></textarea>
                
    <div class="row" >
      <div class="col-6">
        <label for="title"><strong>Tipo de solicitud:</strong></label>
        <select class="form-control" name="tipo_solicitud1" id="tipo_solicitud1" required></select>
      </div>
      <div class="col-6" id="div_equipo1">
        <label for="title"><strong>Equipo:</strong></label>&nbsp&nbsp&nbsp&nbsp<a role="button" class="fa-solid fa-magnifying-glass default" 
        href="#" title="Mostrar Equipos" data-toggle="modal" data-target="#mostrar" onclick="fnOpenModalShowEquipos()"></a>
        <br>
        <select class="form-control select2" name="equipo1" id="equipo1" style="width: 100%;"></select>
      </div>
      <div class="col-12" id="div_descripcion1">
        <label for="title"><strong>Descripcion de equipo:</strong></label>
        <textarea rows="3" type="text" class="form-control" name="descripcion_equipo1" id="descripcion_equipo1"  minlength="10" maxlength="500"></textarea>
      </div>
      <div class="col-6">
        <label for="title"><strong>Area:</strong></label>
        <select class="form-control" name="area1" id="area1" required></select>
      </div>
      <div class="col-6" style="display:none;" id="div_localizacion1">
        <label for="title"><strong>Localizacion:</strong></label>
        <select class="form-control" name="localizacion1" id="localizacion1" required></select>
      </div>
      <div class="col-6" style="display:none;" id="div_falla1">
        <label for="title"><strong>Fallas:</strong></label>
        <br>
        <select class="form-control" name="falla1" id="falla1" required></select>
      </div>
    </div>
  </div> 
</div>
      