<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">

    <link href="{{ asset('css/planillaCursos.css') }}" rel="stylesheet">

</head>

<body>
    <!-- Contenedor principal -->
    
    <!-- Si inscriptosChunks es null o está vacío, mostrar este contenido -->
    <div class="pagina">
        <div class="contenedor" style="max-width: 900px; margin: 20px auto; padding: 20px;">
            <!-- Tabla -->
            <div class="tabla" style="width: 100%; border: 1px solid black; border-collapse: collapse; display: table;">
                <!-- Fila 1 -->


                <!-- Fila 2 -->
                <div class="fila-2" style="display: table-row; width: 80%;">
                    <!-- Celda izquierda (Logo) -->
                    <div class="celda-izquierda-2"
                        style="display: table-cell; border-right: 1px solid black; box-sizing: border-box; height: 50px; vertical-align: middle; text-align: center;">
                        <img src="{{ $base64image }}" alt="Logo" width="120" height="80" />
                    </div>

                    <!-- Celda central -->
                    <div class="celda-central"
                        style="display: table-cell; width: 50%; padding: 5px; border-right: 1px solid black; text-align: center; font-size: 18px; box-sizing: border-box;">
                        <b>Laboratorios LAFEDAR S.A.</b>
                        <p>Valentin Torra 4880 - Pque. Ind. Gral Belgrano - (3100) - Pná. Entre Rios - TEL-fax
                            0343-4362286</p>
                    </div>

                    <!-- Celda derecha -->
                    <div class="celda-derecha-2"
                        style="display: table-cell; position: relative; padding: 2px; text-align: center;width: 30%;">
                        <div class="arriba" style="margin-bottom: 2px;">
                            <b>REMITO</b>
                            <p>(Documento No Válido como Factura) <br>
                                <b>Fecha:</b> {{ $fecha }}
                            </p>

                        </div>




                    </div>
                </div>
            </div>

            <!-- Nuevo div con el label -->
            <div class="procedimiento-div"
                style="margin-top: 0px;padding: 10px; border: 1px solid black; position: relative; text-align: left; font-size: 18px;">
                <b>Nombre del solicitante: </b>

                @if(is_object($person))
                    {{ $person->apellido . ' ' . $person->nombre_p }}
                @else
                    {{ $person }}
                @endif

            </div>

            @php $cantidad = 0; @endphp
            
            <div class="procedimiento-div"
                style="margin-top: 0px; padding: 10px; border: 1px solid black; position: relative; text-align: left; font-size: 18px;">

                <b>Detalle de los Productos:</b>
                <br>
                
                @php $cantidad = 0; @endphp

                <ul>
                    @foreach($items as $item)
                        @if($item->aprobado == 1)
                            <li>{{ $item->medicamento }} – Cantidad Aprobada: {{ $item->cantidad_aprobada }}</li>
                            @php $cantidad += $item->cantidad_aprobada; @endphp
                        @endif
                        
                    @endforeach
                </ul>
                <br>
                <b>Cantidad de Bultos:</b> {{ $cantidad }}

                <br><br>
                <b>Descartes</b>

                <br><br>
                <b style="margin-left: 350px;">Firma RRHH:
                    <img src="{{ $base64image_signature }}" alt="Firma RRHH" width="130" height="80" />
                </b>
            </div>

        </div>
    </div>

    <div class="boton-pdf-medicamentos">
        @if(!$isPdf)
            @if(is_object($person))
                <form action="{{ route('medications.delete', ['id' => $medication->id, 'id_p' => $person->id_p]) }}"
                    class="forms-medication-requests d-inline-block">
                    <!-- d-inline-block para que el form no ocupe todo el ancho -->
                    @csrf
                    @method('GET')
                    <button type="submit" title="Pasar a Aprobacion Pendiente" id="icono" class="btn btn-primary">
                        Generar PDF
                    </button>
                </form>
            @else
                <form action="{{ route('medications.generatePdf', ['id' => $medication->id, 'personId' => $person]) }}"
                    class="forms-medication-requests d-inline-block">
                    @csrf
                    @method('GET')
                    <button type="submit" title="Pasar a Aprobacion Pendiente" id="icono" class="btn btn-primary">
                        Generar PDF
                    </button>
                </form>
            @endif
        @endif
    </div>
</body>

</html>