<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nueva solicitud de Medicamentos</title>
</head>
<body>
    <p>Hola,</p>
    <p><strong>Hay una nueva solicitud de medicamentos:</strong></p>

    <p>Solicitante: <strong>{{ $name }}</strong></p>

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
