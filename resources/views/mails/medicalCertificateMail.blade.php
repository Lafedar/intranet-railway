<!DOCTYPE html>
<html>
<body>
    <p>Hola,</p>
    <p>{{ $nombre }} ha cargado un nuevo certificado médico.</p>
    <p><strong>Título:</strong> {{ $titulo }}</p>

    @if ($descripcion)
        <p><strong>Descripción:</strong> {{ $descripcion }}</p>
    @endif

    <p>Saludos</p>

    <img src="{{ $message->embed($imagePath2) }}" alt="Firma" style="width: 100%; height: auto;">
</body>
</html>
