@extends('layouts.app')
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos del Curso</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cursos.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap&italic=true" rel="stylesheet">



</head>

<body>
    <div class="container mt-5">

        <header id="encabezados">
            <h1 id="titulo">Documentos de la Instancia: {{$instancia->id_instancia}}</h1>
        </header>

        <main>
            <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" class="btn btn-secondary"
                id="volver">Volver</a>
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Formulario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documentos as $doc)
                        <tr>
                            <td>{{ $doc->formulario_id ?? "No hay anexos" }}</td>
                            <td>
                                @if($doc->formulario_id)
                                    <!-- El formulario ahora tiene el formulario_id correspondiente a cada fila -->
                                    <form
                                        action="{{ route('verPlanillaPrevia', ['formularioId' => $doc->formulario_id, 'cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia]) }}"
                                        method="GET">
                                        @csrf
                                        <button type="submit" style="border: none; background: none; padding: 0;"
                                            title="Ver Documento">
                                            <img src="{{ asset('storage/cursos/ver.png') }}" alt="Ver"
                                                style="width:30px; height:30px;">
                                        </button>

                                    </form>
                                @else
                                    <!-- Si no hay formulario_id, no se muestra el botón -->
                                    <span>No disponible</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>