<!-- Modal Mostrar-->
<div class="modal fade" id="mostrar" role="dialog" aling="center">
  <div class="modal-dialog">
    <div class="modal-content">     
      <form action="{{ route('show_solicitud')}}" method="POST" enctype="multipart/form-data">      
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <input type="hidden" name="id" id="id" value="">
              <div class="form-group col-md-12">
                <div class="row">
                  <label for="title"><strong>ID:</strong></label>
                  &nbsp
                  <p name="id" id="id">{{$solicitud->id}}</p>
                </div>
                <div class="row">
                  <label for="title"><strong>Titulo:</strong></label>
                  &nbsp
                  <p name="titulo" id="titulo">{{$solicitud->titulo}}</p>
                </div>
                <div class="row">
                  <label for="title"><strong>Tipo de solicitud:</strong></label>
                  &nbsp
                  <p name="tipo_solicitud" id="tipo_solicitud">{{$solicitud->tipo_solicitud}}</p>
                </div>
                <div class="row">
                  <label for="title"><strong>Equipo:</strong></label>
                  &nbsp
                  <p name="equipo" id="equipo">{{$solicitud->id_equipo}}</p>
                </div>
                <div class="row">
                  <label for="title"><strong>Tipo de falla:</strong></label>
                  &nbsp
                  <p name="falla" id="falla">{{$solicitud->falla}}</p>
                </div>
                <div class="row">
                  <label for="title"><strong>Solicitante:</strong></label>
                  &nbsp
                  <p name="solicitante" id="solicitante">{{$solicitud->nombre_solicitante}}</p>
                </div>
                <div class="row">
                  <label for="title"><strong>Encargado:</strong></label>
                  &nbsp
                  <p name="encargado" id="encargado">{{$solicitud->nombre_encargado}}</p>
                </div>
                <div class="row">
                  <label for="title"><strong>Historico:</strong></label>
                  
                  <table class="table table-striped table-bordered ">
                    <thead>
                      <th class="text-center">Fecha</th>
                      <th class="text-center">Estado</th>
                      <th class="text-center">Descripcion</th>
                    </thead>
                    <tbody>
                      @foreach($historico_solicitudes as $historico)
                        
                          <tr>
                            <td><p name="fecha" id="fecha">{{$historico->fecha}}</p></td>
                            <td><p name="estado" id="estado">{{$historico->nombre}}</p></td>
                            <td><p name="descripcion" id="descripcion">{{$historico->descripcion}}</p></td>
                          </tr>
                        
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div> 
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-info">Guardar</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>