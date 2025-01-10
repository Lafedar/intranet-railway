<!DOCTYPE html>
<html>

<head>
    <title>Certificado de Capacitación</title>
</head>

<body>
    <p>Hola, {{$nombre}} {{$apellido}}</p>
    <p>Adjunto encontrarás tu certificado de la capacitación: "{{$curso}}" completado.</p>
    <p>¡Felicitaciones!</p>
    <img src="{{ $imageBase64Firma }}" alt="Firma" style="width: 100%; height: auto;">
    <!--dejo css inline porque desde el archivo css no funciona-->
</body>

</html>