<!DOCTYPE html>
<html>

<head>
    <title>Certificado de Curso</title>
</head>

<body>
    <p>Hola, {{$nombre}} {{$apellido}}</p>
    <p>Adjunto encontrarás tu certificado del curso: "{{$curso}}" completado.</p>
    <p>¡Felicitaciones!</p>
    <img src="{{ asset('storage/cursos/firma.jpg') }}">
</body>

</html>