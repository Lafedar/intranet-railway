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
            <h1>La Solicitud #{{ $idSolicitud }} "{{$titulo}}" llevo repuestos</h1>
            <p>Estimado/a,</p>
            <p>Nos dirigimos a usted para informarle que la solicitud con el ID: {{ $idSolicitud }} paso a estado {{ $estado }}. La misma utilizo 
            repuestos con la siguiente descripcion: "{{$descripcionRepuesto}}". Para ver los datalles de esta solicitud hacer clic en el botón "Detalle" o para ver 
            todas las solicitudes puede hacer clic en el boton "Solicitudes"</p>
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