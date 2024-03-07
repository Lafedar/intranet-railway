<!DOCTYPE html>
<html>
    <head>
        <style>
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                font-family: Arial, sans-serif;
                background-color: #f2f2f2;
            }

            .button {
                display: inline-block;
                padding: 10px 20px;
                background-color: #4CAF50;
                color: white;
                text-decoration: none;
                border-radius: 30px;
                text-align: center;
                width: fit-content;
                font-size: 24px;
                margin-bottom: 10px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
                transition: background-color 0.3s ease;
            }

            .button:hover {
                background-color: #45a049;
            }

            .container-button {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Se requiere aprobación para la solicitud #{{ $idSolicitud }} "{{$titulo}}"</h1>
            <p>Estimado/a {{ $nombre }},</p>
            <p>Nos dirigimos a usted para informarle que su solicitud con el ID: {{ $idSolicitud }} ha sido procesada y ahora se encuentra en estado {{ $estado }}.</p>
            <p>Para proceder con la aprobación de la solicitud, haga clic en el botón "Aprobar". Si desea presentar un reclamo, puede utilizar el botón "Reclamar". También puede acceder a los detalles completos de la solicitud haciendo clic en el botón "Detalle".</p>
            <div class="container-button">
                <a href="http://intranet.lafedar/aprobar_solicitud/{{ $idSolicitud }}" class="button" style="background-color: #007bff;">Aprobar</a>
                <br><br>
                <a href="http://intranet.lafedar/solicitudes?idsolicitud={{ $idSolicitud }}&source=email" class="button" style="background-color: #e74c3c;">Reclamar</a>
                <br><br>
                <a href="http://intranet.lafedar/solicitudes?idsolicitud={{ $idSolicitud }}&source=detalle" class="button" style="background-color: #f39c12;">Detalle</a>
            </div>
            <p>Saludos,</p>
            <p>Área de Mantenimiento</p>
        </div>
    </body>
</html>