<div class="modal fade" id="editar_empleado" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
      <form action="{{route('empleado.update' , ' ')}}" method="POST" autocomplete="off">
      {{ method_field('PUT')}} {{csrf_field()}}
      <div class="modal-body">
       <div class="row">
         <div class="col-md-12">

          <input type="hidden" name="id_p" id="id_p" value="{{old('id_p')}}">
          <div class="row">
            <div class="col-md-6">
              <label for="title">Nombre:</label>
              <input type="text" name="nombre" class="form-control" id="nombre_p" autocomplete="off" value="{{old('nombre_p')}}" min="6" required>
            </div>
            <div class="col-md-6">
              <label for="title">Apellido:</label>
              <input type="text" name="apellido" class="form-control" id="apellido" autocomplete="off" value="{{old('apellido')}}" min="6" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label for="title">DNI:</label>
              <input type="text" name="dni" class="form-control" id="dni" autocomplete="off" value="{{old('dni')}}" min="6" required>
            </div>
            <div class="col-md-1"></div>

            <div class="col-md-4">
              <label for="title">Interno:</label>
              <input type="text" name="interno" class="form-control" id="interno" autocomplete="off" value="{{old('interno')}}" min="6" >
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label for="title">Area:</label>
              <select class="form-control" name="area"  id="select_area"></select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label for="title">Fecha de nacimiento:</label>
              <input type="date"name="fe_nac"  id="fe_nac" class="form-control"-tep="1" value="{{old('fe_nac')}}">
            </div>
            <div class="col-md-6">
              <label for="title">Fecha de ingreso:</label>
              <input type="date"name="fe_ing"  id="fe_ing" class="form-control"-tep="1"  value="<?php echo date("Y-m-d");?>">
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label for="title">Correo eletrónico:</label>
              <input type="text" name="correo" class="form-control" id="correo" value="{{old('correo')}}" >
            </div>
          </div>
          <p></p>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-info">Editar</button>
          </div>
        </div>
      </div>
    </form>                
  </div>
</div>
</div>