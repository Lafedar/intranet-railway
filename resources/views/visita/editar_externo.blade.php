<div class="modal fade" id="editar_externo" role="dialog" align="center" >
  <div class="modal-dialog">
   <div class="modal-content">           
    <div class="container">
      <form action="{{action('VisitaController@editar_externo', '')}}" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-body">
         <div class="row">
           <div class="col-md-12" >
            <h5 class="headertekst">Editar Persona </h5>
            <hr>
            <input type="hidden" id="empresa_ext" name="empresa_ext">
            <div class="row">
              <div class="col-md-6">
                <label for="title">Nombre:</label>
                <input class="form-control" type="text" id="nombre_ext" name="nombre_ext"  required>
              </div>
              <div class="col-md-6">
                <label for="title">Apellido:</label>
                <input class="form-control" type="text" id="apellido_ext" name="apellido_ext"   required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <label for="title">DNI:</label>
                <input class="form-control" type="text" id="dni" name="dni" required>
              </div>
              <div class="col-md-6">
                <label for="title">Telefono:</label>
                <input class="form-control" type="text" id="telefono_ext" name="telefono_ext">
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="title" class="col-md-10">Foto:</label>
                <input type="file"  name="foto" accept=".jpg"  id="foto">
              </div>
            </div>
            <br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cancelar</button>
            <button type="submit" class="btn btn-info" id="asignar-btn">Guardar</button>
          </div>
        </div>
      </div>
    </form>                
  </div>
</div>
</div>
</div>