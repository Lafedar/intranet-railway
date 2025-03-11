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
    <p>Fuiste inscripto por {{ $gestor->nombre_p }} {{ $gestor->apellido }} en la capacitación:
        <b>{{ $curso->titulo }}</b>.</p>
    <p>Obligatorio:
        @if($curso->obligatorio == 1)
            <b>Si</b>
        @else
            <b>No</b>
        @endif
    </p>
    <p>Ubicacion: <b>{{ $sala }}</b></p>
    <p>Fecha de inicio: <b>{{ $fechaInicio ? $fechaInicio->format('d/m/Y') : 'Fecha no disponible' }}</b></p>
    @if(!empty($hora))
        <p>Hora: <b>{{ date('H:i', strtotime($hora)) }}hs</b></p>
    @else
        <p>Hora: <b>N/A</b></p>
    @endif

    <p>Saludos</p>
    <img src="{{ $imageBase64Firma }}" alt="Firma" style="width: 100%; height: auto;">

</body>

</html>