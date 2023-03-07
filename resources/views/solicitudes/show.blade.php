
<!-- Modal Mostrar-->
<div class="col-md-12">
    <h4> Detalle: </h4>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <strong>ID: </strong>
            <p>{{$solicitud->id}}</p>
        </div>
        <div class="col-md-4">
            <strong>Titulo: </strong>
            <p>{{$solicitud->titulo}}</p>
        </div>
        <div class="col-md-4">
            <strong>Tipo de solicitud: </strong>
            <p>{{$solicitud->tipo_solicitud}}</p>
        </div>
    </div>
    <div class="row">    
        <div class="col-md-4">
            <strong>Equipo: </strong>
            <p>{{$solicitud->id_equipo}}</p>
        </div>
        <div class="col-md-4">
            <strong>Area: </strong>
            <p>{{$solicitud->area}}</p>
        </div>
        <div class="col-md-4">
            <strong>Localizacion: </strong>
            <p>{{$solicitud->localizacion}}</p>
        </div>
    </div>  
    <div class="row">
        <div class="col-md-4">
            <strong>Falla: </strong>
            <p>{{$solicitud->falla}}</p>
        </div>
        <div class="col-md-4">
            <strong>Solicitante: </strong>
            <p>{{$solicitud->nombre_solicitante}}</p>
        </div>
        <div class="col-md-4">
            <strong>Encargado: </strong>
            @if($solicitud->nombre_encargado)
                <p>{{$solicitud->nombre_encargado}}</p>
            @else
                <p style="color:gainsboro">Sin asignar</p>
            @endif
        </div>
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
                @foreach($historico_solicitudes as $historico)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($historico->fecha)->format('d/m/Y H:i') }}</td>
                        <td>{{ $historico->estado }}</td>
                        <td>{{ $historico->descripcion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
