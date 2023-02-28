
<!-- Modal Mostrar-->
<div class="col-md-12">
    <h4> Detalle: </h4>
    <hr>
    <div class="form-group">
        <strong>ID: </strong>
        <p>{{$solicitud->id}}</p>
    </div>
    <div class="form-group">
        <strong>Titulo: </strong>
        <p>{{$solicitud->titulo}}</p>
    </div>
    <div class="form-group">
        <strong>Tipo de solicitud: </strong>
        <p>{{$solicitud->id_tipo_solicitud}}</p>
    </div>
    <div class="form-group">
        <strong>Equipo: </strong>
        <p>{{$solicitud->id_equipo}}</p>
    </div>
    <div class="form-group">
        <strong>Falla: </strong>
        <p>{{$solicitud->id_falla}}</p>
    </div>
    <div class="form-group">
        <strong>Nombre Solicitante: </strong>
        <p>{{$solicitud->id_solicitante}}</p>
    </div>
    <div class="form-group">
        <strong>Nombre Encargado: </strong>
        <p>{{$solicitud->id_encargado}}</p>
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
                
            </tbody>
        </table>
    </div>
</div>
