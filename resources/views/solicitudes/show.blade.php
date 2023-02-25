<!-- Modal Mostrar-->
<div class="modal fade" id="mostrar" role="dialog" aling="center">
  <div class="modal-dialog">
    <div class="modal-content">     
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <input type="hidden" name="id" id="id" value="">
              <div class="form-group col-md-12">
                <h4> Detalle: </h4>
                <hr>
                <div class="form-group">
                  <strong>ID: </strong><output type="text" name="id" id="id">
                </div>
                <div class="form-group">
                  <strong>Titulo: </strong><output type="text" name="titulo" id="titulo">
                </div>
                <div class="form-group">
                  <strong>Tipo de solicitud: </strong><output type="text" name="tipo_solicitud" id="tipo_solicitud">
                </div>
                <div class="form-group">
                  <strong>Equipo: </strong><output type="text" name="id_equipo" id="id_equipo">
                </div>
                <div class="form-group">
                  <strong>Falla: </strong><output type="text" name="falla" id="falla">
                </div>
                <div class="form-group">
                  <strong>Nombre Solicitante: </strong><output type="text" name="nombre_solicitante" id="nombre_solicitante">
                </div>
                <div class="form-group">
                  <strong>Nombre Encargado: </strong><output type="text" name="nombre_encargado" id="nombre_encargado">  
                </div>
                <div class="row">
                  <label for="title"><strong>Historico:</strong></label>
                  <table class="table table-striped table-bordered">
                    <thead>
                      <th class="text-center">Fecha</th>
                      <th class="text-center">Estado</th>
                      <th class="text-center">Descripcion</th>
                    </thead>
                    <tbody>
                      @if($historico_solicitudes)
                        @foreach($historico_solicitudes as $historico)
                          <tr>
                            <td><p name="fecha" >{{$historico->fecha}}</p></td>
                            <td><p name="estado" >{{$historico->estado}}</p></td>
                            <td><p name="descripcion" >{{$historico->descripcion}}</p></td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
              </div> 
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>