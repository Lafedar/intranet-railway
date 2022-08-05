<div class="modal fade" id="añadir_externo" role="dialog" align="center" >
  <div class="modal-dialog">
   <div class="modal-content">           
    <div class="container">
      <form action="{{action('VisitaController@añadir_externo', '')}}" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-body">
         <div class="row">
           <div class="col-md-12" >
            <h5 class="headertekst">Agregar Persona </h5>
            <hr>
            <input type="hidden" id="empresa_ext" name="empresa_ext">
            <div class="row">
              <div class="col-md-6">
                <label for="title">Nombre:</label>
                <input class="form-control" type="text" id="nombre_ext" autocomplete="off" name="nombre_ext"  required>
              </div>
              <div class="col-md-6">
                <label for="title">Apellido:</label>
                <input class="form-control" type="text" id="apellido_ext" autocomplete="off" name="apellido_ext" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label for="title">DNI:</label>
                <input class="form-control" type="text" id="dni" name="dni" autocomplete="off" required>
              </div>
              <div class="col-md-6">
                <label for="title">Telefono:</label>
                <input class="form-control" type="text" id="telefono_ext" autocomplete="off" name="telefono_ext">
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="title" class="col-md-10">Foto:</label>
                <input type="file"  name="foto" accept=".jpg"  id="foto" required>
              </div>
            </div>
            <br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-info">Agregar</button>
          </div>
        </div>
      </div>
    </form>                
  </div>
</div>
</div>
</div>