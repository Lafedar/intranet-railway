<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Aprobación</title>

</head>

<body>
    <div class="certificate">
        <div class="certificate-border">
            <div class="certificate-content">
                <div class="certificate-header">
                    <img src="{{ $imageBase64 }}" alt="Logo" width="200" height="70" />
                    <br><br>
                    <h1>Certificado de Aprobación</h1>
                    <p>Otorgado por <strong>Laboratorios Lafedar</strong></p>
                </div>

                <div class="certificate-body">
                    <p>Se otorga el presente certificado a:</p>
                    <h2 class="recipient-name">{{$nombre}} {{$apellido}}</h2>
                    <p>por haber aprobado la capacitación</p>
                    <h3 class="course-title">{{$curso}}</h3>

                </div>

                <div class="certificate-footer">
                    <p>Capacitador/a: {{$capacitador}}</p>
                    <br>
                    <br>
                    <p>Fecha de Capacitación: {{$fecha}}</p>
                    <br><br><br><br><br>
                    <div style="display: inline-block; width: 48%; text-align: center;">
                        <p>______________________________</p>
                        <p>Firma de RRHH</p>
                    </div>
                    <div style="display: inline-block; width: 48%; text-align: center;">
                        <p>______________________________</p>
                        <p>Firma del capacitador/a</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>




<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Georgia', serif;
    }

    body {
        background-color: #f0f0f8;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 60vh;
        width: 90vw;
        overflow: hidden;
    }

    .certificate {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 50px;
        height: 90%;
        width: 90%;
    }

    .certificate-border {
        width: 1200px;
        height: 790px;
        padding: 30px;
        background: #fff;
        border: 6px solid #003366;
        border-radius: 15px;
        box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .certificate-content {
        padding: 20px;
        border: 1px solid #003366;
        text-align: center;
        flex-grow: 1;
        height: 710px;
    }

    .certificate-header {
        margin-bottom: 40px;
    }

    .logo {
        width: 200px;
        height: auto;
        margin-bottom: 20px;
    }

    .certificate-header h1 {
        font-size: 48px;
        color: #003366;
        margin-bottom: 10px;
    }

    .certificate-header p {
        font-size: 24px;
        color: #333;
    }

    .certificate-body {
        margin: 50px 0;
    }

    .recipient-name {
        font-size: 36px;
        font-weight: bold;
        color: #003366;
        margin: 10px 0;
    }

    .course-title {
        font-size: 30px;
        font-weight: bold;
        color: #333;
        margin: 15px 0;
        font-style: italic;
    }

    .course-duration {
        font-weight: bold;
        color: #003366;
    }

    .certificate-footer {
        margin-top: 50px;
    }

    .certificate-footer p {
        font-size: 20px;
        color: #555;
    }

    .signature {
        margin-top: 50px;
        text-align: center;
    }

    .signature p {
        color: #333;
        font-size: 20px;
    }

    orm.hide-when-pdf {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        width: 100%;
        position: absolute;
        bottom: 20px;
    }

    button.btn-primary {
        padding: 10px 20px;
        font-size: 18px;
        background-color: #003366;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button.btn-primary:hover {
        background-color: #001f33;
    }
</style>