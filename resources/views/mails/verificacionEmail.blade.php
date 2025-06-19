<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Hola {{ $nombre }}!</h2>
    <p>Gracias por registrarte en Extranet Lafedar. Para activar tu cuenta, hacé clic en el siguiente enlace:</p>

    <a href="{{ url('/verificar/' . $token) }}">Verificar cuenta</a>

    <p>Si no fuiste vos, ignorá este correo.</p>

</body>

</html>