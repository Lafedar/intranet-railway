<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Hola {{ $nombre }}!</h2>
    <p>Hemos recibido una solicitud de restablecimiento de contraseña para tu cuenta. Para restablecer tu contraseña, hacé clic en el siguiente enlace:</p>

    <a href="{{ url('/redirectToResetPassword/' . $token) }}">Restablecer contraseña</a>
    <p>Si no fuiste vos, ignorá este correo.</p>

</body>

</html>