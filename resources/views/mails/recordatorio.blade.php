
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
            <h1>Recordatorio de Solicitud #{{$id}} "{{$titulo}}"</h1>
            <p>Estimado/a {{$nombre_encargado}} {{$apellido_encargado}}
            
            <p>Nos dirigimos a usted para informarle que el solicitante <strong>{{$nombre_solicitante}} {{$apellido_solicitante}}</strong> ha enviado un recordatorio de su solicitud, ID: <strong>{{$id}}</strong>  </p>
            
            <p><strong>Estado de la solicitud:</strong> {{$nombre}}</p>
            
            
           
            <div class="container-button">
               
            </div>
            <br>
            <br>
            <p>Saludos,</p>
            <p>Área de Mantenimiento</p>
        </div>
    </body>
</html>