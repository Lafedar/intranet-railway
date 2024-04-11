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
                border-radius: 10px;
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
            <h1>Novedad de solicitud #{{ $idSolicitud }} "{{$titulo}}"</h1>
            <p>Estimado/a {{ $nombre }},</p>
            <p>Nos dirigimos a usted para informarle sobre un cambio de estado en relación a su solicitud con el ID: {{ $idSolicitud }}.</p>
            <p>Su solicitud ha pasado al estado: {{ $estado }}. Para acceder al detalle de la solicitud le invitamos a hacer clic en el botón "Detalle" 
                o para ir a var todas sus solicitudes y las de su area puede ir hacer clic en el boton "Solicitudes"</p>
            <div class="container-button">
                <a href="http://intranet.lafedar/solicitudes?idsolicitud={{ $idSolicitud }}&source=detalle" class="button" style="background-color: #f39c12;">Detalle</a>
                <br><br>
                <a href="http://intranet.lafedar/solicitudes" class="button">Solicitudes</a>
            </div>
            <p>Saludos,</p>
            <p>Área de Mantenimiento</p>
        </div>
    </body>
</html>