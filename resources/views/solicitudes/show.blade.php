
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
            @if($solicitud->id_equipo)
                <p>{{$solicitud->id_equipo}}</p>
            @else
                <p style="color:gainsboro">N/A</p>
            @endif
        </div>
        <div class="col-md-4">
            <strong>Area: </strong>
            @if($solicitud->area_equipo)
                <p>{{$solicitud->area_equipo}}</p>
            @elseif($solicitud->area_edilicio)
                <p>{{$solicitud->area_edilicio}}</p>
            @elseif($solicitud->area_proyecto)
                <p>{{$solicitud->area_proyecto}}</p>
            @endif
        </div>
        <div class="col-md-4">
            <strong>Localizacion: </strong>
            @if($solicitud->loc_edilicio)
                <p>{{$solicitud->loc_edilicio}}</p>
            @elseif($solicitud->loc_equipo)
                <p>{{$solicitud->loc_equipo}}</p>
            @else
                <p style="color:gainsboro">N/A</p>
            @endif
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
                <th class="text-center">Repuesto</th>
                <th class="text-center">Desc. repuesto</th>
            </thead>
            <tbody>
                @foreach($historico_solicitudes as $historico)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($historico->fecha)->format('d/m/Y H:i') }}</td>
                        <td>{{ $historico->estado }}</td>
                        <td>{{ $historico->descripcion }}</td>
                        @if($historico->rep)
                            <td>Si</td>
                        @else
                            <td>No</td>
                        @endif
                        <td>{{ $historico->desc_rep }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
