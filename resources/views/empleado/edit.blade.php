<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="modal fade" id="editar_empleado" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('empleado.update', $empleado->id_p) }}" method="post" autocomplete="off">
        {{ method_field('PUT')}} {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <input type="hidden" name="id_p" id="id_p" value="{{old('id_p')}}">

              <div class="row">
                <div class="col-md-6">
                  <label for="nombre_p"><strong>Nombre:</strong></label>
                  <input type="text" name="nombre" class="form-control" id="nombre_p" autocomplete="off"
                    value="{{old('nombre_p')}}" min="6" required>
                </div>
                <div class="col-md-6">
                  <label for="apellido"><strong>Apellido:</strong></label>
                  <input type="text" name="apellido" class="form-control" id="apellido" autocomplete="off"
                    value="{{old('apellido')}}" min="6" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <label for="dni"><strong>DNI:</strong></label>
                  <input type="number" name="dni" class="form-control" id="dni" autocomplete="off"
                    value="{{old('dni')}}" min="6" required>
                </div>
                <div class="col-md-6">
                  <label for="interno"><strong>Interno:</strong></label>
                  <input type="number" name="interno" class="form-control" id="interno" autocomplete="off"
                    value="{{old('interno')}}" min="6">
                </div>
                <div class="col-md-6">
                  <label for="legajo"><strong>Legajo:</strong></label>
                  <input type="number" name="legajo" class="form-control" id="legajo" autocomplete="off"
                    value="{{ old('legajo') }}" minlength="2" maxlength="5">
                </div>

              </div>

              <div class="row">
                <div class="col-md-6">
                  <label for="fe_nac"><strong>Fecha de nacimiento:</strong></label>
                  <input type="date" name="fe_nac" id="fe_nac" class="form-control" step="1" value="{{old('fe_nac')}}">
                </div>
                <div class="col-md-6">
                  <label for="fe_ing"><strong>Fecha de ingreso:</strong></label>
                  <input type="date" name="fe_ing" id="fe_ing" class="form-control" step="1"
                    value="<?php echo date("Y-m-d");?>">
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <label for="correo"><strong>Correo electrónico:</strong></label>
                  <input type="email" name="correo" class="form-control" id="correo" value="{{old('correo')}}">
                </div>
              </div>

              <div class="row">
                <div class="col-6">
                  <label for="select_area"><strong>Area:</strong></label>
                  <select class="form-control" name="area" id="select_area" required></select>
                </div>
                <div class="col-6">
                  <label for="turnoEdit"><strong>Turno:</strong></label>
                  <select class="form-control" name="turnoEdit" id="turnoEdit" required></select>
                </div>
                <div class="col-6">
                <label for="password"><strong>Contraseña:</strong></label>
                <input type="text" name="password" id="password" class="form-control"  value="{{ old('password') }}">
              </div>

              </div>

              <p></p>

              <div class="row">
                <div class="col-6">
                  <label for="actividad"><strong>En actividad:</strong></label>
                  <input type="checkbox" name="actividad" id="actividad">
                </div>
                <div class="col-6">
                  <label for="esJefe"><strong>Es jefe:</strong></label>
                  <input type="checkbox" name="esJefe" id="esJefe">
                </div>
              </div>

              <p></p>

              <button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cancelar</button>
              <button type="submit" class="btn btn-info" id="asignar-btn">Editar</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>