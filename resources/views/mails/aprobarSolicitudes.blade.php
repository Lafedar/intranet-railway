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
            <h1>Se necesita aprobar solicitud {{ $idSolicitud }}</h1>
            <p>Estimado/a {{ $nombre }},
            Nos dirigimos a usted para informarle que su solicitud con el ID: {{ $idSolicitud }} ya se encuentra finaliza. 
            La solicitud pasó a estado {{ $estado }}. Esta solicitud necesita ser aprobada haciendo clic en el botón que se encuentra a continuación.</p>
            <div class="container-button">
                <a href="http://intranet.lafedar.desa/aprobar_solicitud/{{ $idSolicitud }}" class="button">Aprobar</a>
            </div>
            <p>Saludos<br>Area de mantenimiento</p>
        </div>
    </body>
</html>