<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción al Curso</title>
</head>

<body>
    <p>Hola, {{ $user->nombre_p }} {{ $user->apellido }}</p>
    <p>Has sido inscripto en el curso: <b>{{ $curso }}</b>.</p>
    <p>Fecha de inicio: {{ $fechaInicio ? $fechaInicio->format('d/m/Y') : 'Fecha no disponible' }}</p>
    <p>Saludos</p>
    <img src="{{ $imageBase64Firma }}" alt="Firma">
</body>

</html>