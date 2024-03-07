<script>
  $(document).ready(function() {
    $('#fe_nac').on('change', function() {
      // Obtiene el valor de la fecha del campo
      let inputValue = $(this).val();

      // Divide la fecha en sus componentes (año, mes, día)
      let components = inputValue.split('-');

      // Si el año tiene más de 4 caracteres, recorta los extras
      if (components[0].length > 4) {
        components[0] = components[0].substring(0, 4);
        $(this).val(components.join('-'));
      }
    });
    $('#fe_ing').on('change', function() {
      // Obtiene el valor de la fecha del campo
      let inputValue = $(this).val();

      // Divide la fecha en sus componentes (año, mes, día)
      let components = inputValue.split('-');

      // Si el año tiene más de 4 caracteres, recorta los extras
      if (components[0].length > 4) {
        components[0] = components[0].substring(0, 4);
        $(this).val(components.join('-'));
      }
    });
  });
</script>

<div class="modal fade" id="agregar_empleado" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">           
      <form action="{{route('empleado.store')}}" method="post" autocomplete="off">
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <input type="hidden" name="id" value="{{{ isset($empleado->id_p) ? $empleado->id_p : ''}}}">
              <div class="row">
                <div class="col-md-6">
                  <label for="nombre"><strong>Nombre:</strong></label>
                  <input type="text" name="nombre" class="form-control" id="nombre" autocomplete="off" value="{{old('nombre')}}" minlength="3" maxlength="30" required>
                </div>
                <div class="col-md-6">
                  <label for="apellido"><strong>Apellido:</strong></label>
                  <input type="text" name="apellido" class="form-control" id="apellido" autocomplete="off" value="{{old('apellido')}}" minlength="3" maxlength="30" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <label for="dni"><strong>DNI:</strong></label>
                  <input type="number" name="dni" class="form-control" id="dni" autocomplete="off" value="{{old('dni')}}" minlength="8" maxlength="11" required>
                </div>
                <div class="col-md-6">
                  <label for="interno"><strong>Interno:</strong></label>
                  <input type="number" name="interno" class="form-control" id="interno" autocomplete="off" value="{{old('interno')}}" minlength="2" maxlength="5">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <label for="fe_nac"><strong>Fecha de nacimiento:</strong></label>
                  <input type="date" name="fe_nac" id="fe_nac" class="form-control" step="1" value="{{old('fe_nac')}}">
                </div>
                <div class="col-md-6">
                  <label for="fe_ing"><strong>Fecha de ingreso:</strong></label>
                  <input type="date" name="fe_ing" id="fe_ing" class="form-control" step="1" value="<?php echo date("Y-m-d");?>">
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <label for="correo"><strong>Correo electrónico:</strong></label>
                  <input type="email" name="correo" class="form-control" id="correo" value="{{old('correo')}}" >
                </div>
              </div>

              <div class="row">
                <div class="col-6">
                  <label for="area"><strong>Area:</strong></label>
                  <select class="form-control" name="area" id="area" required></select>
                </div>
                <div class="col-6">
                  <label for="turno"><strong>Turno:</strong></label>
                  <select class="form-control" name="turno" id="turno" required></select>
                </div>
              </div>

              <p></p>

              <div class="row">
                <div class="col-6">
                  <label for="actividadCreate"><strong>En actividad:</strong></label>
                  <input type="checkbox" name="actividadCreate" id="actividadCreate">
                </div>
                <div class="col-6">
                  <label for="esJefeCreate"><strong>Es jefe:</strong></label>
                  <input type="checkbox" name="esJefeCreate" id="esJefeCreate" disabled>
                </div>
              </div>

              <p></p>

              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-info">Agregar</button>
            </div>
          </div>
        </div>
      </form>                
    </div>
  </div>
</div>