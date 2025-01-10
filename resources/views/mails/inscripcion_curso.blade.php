<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <title>Inscripción a la Capacitación</title>
</head>

<body>
    <p>Hola, {{ $user->nombre_p }} {{ $user->apellido }}</p>
    <p>Has sido inscripto en la capacitación: <b>{{ $curso }}</b>.</p>
    <p>Fecha de inicio: {{ $fechaInicio ? $fechaInicio->format('d/m/Y') : 'Fecha no disponible' }}</p>
    <p>Saludos</p>
    <img src="{{ $imageBase64Firma }}" alt="Firma" style="width: 100%; height: auto;">
    <!--dejo css inline porque desde el archivo css no funciona-->
</body>

</html>