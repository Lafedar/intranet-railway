<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Notificación de solicitud</title>
</head>

<body>
    <p>Hola, {{ $name }}</p>

    <p><strong>Tu solicitud de medicamentos fue creada:</strong></p>

    <ul>
        <li>{{ $data['medication'] }} - Cantidad: {{ $data['amount'] }}</li>

        @if (!empty($data['medication2']) && !empty($data['amount2']))
            <li>{{ $data['medication2'] }} - Cantidad: {{ $data['amount2'] }}</li>
        @endif

        @if (!empty($data['medication3']) && !empty($data['amount3']))
            <li>{{ $data['medication3'] }} - Cantidad: {{ $data['amount3'] }}</li>
        @endif
    </ul>

    <br>
    <p>Saludos</p>

    <img src="{{ $message->embed($imagePath2) }}" alt="Firma" style="width: 100%; height: auto;">
</body>

</html>