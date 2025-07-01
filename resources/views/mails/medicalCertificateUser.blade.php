<!DOCTYPE html>
<html>
<body>
    <p>Hola {{ $nombre }}</p>
    <p>Tu certificado médico: </p>
    <p><strong>Título:</strong> {{ $titulo }}</p>

    <p><strong>Se ha cargado correctamente.</strong></p>

    <p>Saludos</p>

    <img src="{{ $message->embed($imagePath2) }}" alt="Firma" style="width: 100%; height: auto;">
</body>
</html>
