<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="modal fade" id="agregar_permiso" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <form id="myForm" action="{{ action('PermisosController@store') }}" method="POST">
      {{csrf_field()}}
      <div class="modal-body">
        <h4 class="headertekst">Solicitud de permiso</h4>
        <hr>
        <div class="row">
         <div class="col-md-12">

          <div class="row">
            <div class="col">
              <label for="title">Autorizo a:</label>
              <select class="form-control" name="autorizado"  id="select"required></select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label for="title">Fecha desde:</label>
              <input type="date" name="fecha_desde" class="form-control" step="1" min="2019-01-01" value="<?php echo date("Y-m-d");?>">
            </div>
            <div class="col-md-6">
              <label for="title">Fecha hasta:</label>
              <input type="date" name="fecha_hasta" class="form-control" step="1" min="2019-01-01" value="<?php echo date("Y-m-d");?>">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
              <label for="title">Horario desde:</label>
              <!--<select class="form-control-sm" name="hora_desde" id="hora_desde"  required>
                @for($i=00; $i<24; $i++)
                <option>{{$i}} </option>
                @endfor
              </select>-->
            </div>
            <div class="col-md-6">
              <label for="title">Horario hasta:</label>
             <!-- <select class="form-control-sm" name="hora_hasta" id="hora_hasta"  required>
                @for($i=00; $i<24; $i++)
                <option>{{$i}} </option>
                @endfor
              </select>-->
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <input type="time" name="hora_desde" id="hora_desde">
            </div>
            <div class="col-md-6">
             <input type="time" name="hora_hasta" id="hora_hasta">
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label for="title">Motivo:</label>
              <select class="form-control" name="motivo"  id="select_motivo"required></select>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label for="tittle">Descripción</label>
              <textarea class="form-control" rows="4" name="descripcion" id="descripcion" required></textarea>
            </div>
          </div>
          <p></p>
          <button type="button" class="btn-create-permission btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cancelar</button>
          <button type="submit" class="btn btn-info"  onclick="fnSaveSolicitud()" id="asignar-btn">Agregar</button>
        </div>
      </div>
    </div>
  </form>                
</div>
</div>
</div>

