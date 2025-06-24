<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activación de cuenta</title>
</head>

<body>
    <h2>Hola {{ $nombre }}!</h2>
    <p>Gracias por registrarte en Extranet Lafedar. Para activar tu cuenta, hacé clic en el siguiente enlace:</p>

    <a href="{{ url('/verificar/' . $token) }}" target="_blank" rel="noopener noreferrer">Verificar cuenta</a>

    <p><strong>Este enlace es válido por 24 horas.</strong></p>
    
    <p>Si no fuiste vos, ignorá este correo.</p>

</body>

</html>