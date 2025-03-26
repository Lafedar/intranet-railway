<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Participación</title>
    <link href="{{ asset('css/certificadoMail.css') }}" rel="stylesheet">

</head>

<body>
    <div class="certificate">
        <div class="certificate-border">
            <div class="certificate-content">
                <div class="certificate-header">
                    <img src="{{ $imageBase64 }}" alt="Logo" width="200" height="70" />
                    <br><br>
                    <h1>Certificado de Participación</h1>
                    <p>Otorgado por <strong>Laboratorios Lafedar</strong></p>
                </div>

                <div class="certificate-body">
                    <p>Se otorga el presente certificado a:</p>
                    <h2 class="recipient-name">{{$nombre}} {{$apellido}}</h2>
                    <p>por haber participado de la capacitación</p>
                    <h3 class="course-title">{{$course}}</h3>

                </div>

                <div class="certificate-footer">
                    <p>Capacitador/a: {{$capacitador}}</p>
                    <br>
                    <br>
                    <p>Fecha de Capacitación: {{$fecha}}</p>
                    <br><br><br><br><br>
                    <div style="display: inline-block; width: 48%; text-align: center;">
                    <img src="{{ $imageBase64Firma }}" alt="Logo" width="auto" height="150px;" style="margin-bottom: -30px; margin-top: -90px;"/>
                        <p>______________________________</p>
                        <p>Firma de RRHH</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>




