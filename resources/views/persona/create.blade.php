<!-- Modal Agregar-->
<div class="modal fade" id="agregar" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <form action="{{route('persona.store')}}" method="POST" >
      {{csrf_field()}}
      <div class="modal-body">
       <div class="row">
         <div class="col-md-12">
          <input type="hidden" name="id">

          <div class="row">
            <div class="col-md-6">
              <label for="title">Nombre:</label>
              <input type="text" class="form-control" name="nombre" id="nombre" required>
            </div>

            <div class="col-md-6">
              <label for="title">Apellido:</label>
              <input type="text" class="form-control" name="apellido" id="apellido">
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label for="title">Dirección:</label>
            <input type="text" class="form-control" name="direccion" id="direccion">  
            </div>
          </div>

          <div class="row">
            <div class="col">
            <label for="title">Empresa:</label>
            <input type="text" class="form-control" name="empresa" id="empresa">  
            </div>
          </div>

          <div class="row">
            <div class="col-md-5">
              <label for="title">Teléfono:</label>
            <input type="text" class="form-control" name="telefono" id="telefono">
            </div>

            <div class="col-md-2">
            <label for="title">Interno:</label>
            <input type="text" class="form-control" name="interno" id="interno">  
            </div>

            <div class="col-md-5">
            <label for="title">Celular:</label>
            <input type="text" class="form-control" name="celular" id="celular">  
            </div>
          </div>

          <div class="row">
            <div class="col">
            <label for="title">Correo electrónico:</label>
            <input type="text" class="form-control" name="correo" id="correo">
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