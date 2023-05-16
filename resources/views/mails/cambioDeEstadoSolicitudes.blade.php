<!DOCTYPE html>
<html>
    <head>
        <style>
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }

            .button {
                display: inline-block;
                padding: 10px 20px;
                background-color: blue;
                color: white;
                text-decoration: none;
                border-radius: 10px;
                text-align: center;
                width: fit-content; /* Ajusta el ancho al contenido */
                font-size: 24px; /* Ajusta el tamaño de la fuente según sea necesario */
            }

            .container-button {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Novedad de solicitud {{ $idSolicitud }}</h1>
            <p>Estimado/a {{ $nombre }},
            Nos dirigimos a usted para informarle sobre un cambio de estado en relación a su solicitud con el id {{ $idSolicitud }}. 
            Su solicitud pasó a estado {{ $estado }}. Para acceder a las solicitudes de su área, así como a las suyas propias, 
            le invitamos a hacer clic en el siguiente botón.</p>
            <div class="container-button">
                <a href="http://intranet.lafedar.desa/solicitudes" class="button">Solicitudes</a>
            </div>
            <p>Saludos<br>Area de mantenimiento</p>
        </div>
    </body>
</html>