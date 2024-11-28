<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos del Curso</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap&italic=true" rel="stylesheet">
    

    <style>
        /*BOTON VOLVER*/
        #volver{
            margin-top: 43px;
            margin-left: 3px;
            height:45px;
            background: linear-gradient(90deg, #206190 0%, #357AAB 44.5%, #3D83B5 54%, #5098CD 100%);
            border: none; 
            color: white; 
            padding: 10px 20px; 
            font-size: 18px; 
            font-weight: bold;
            border-radius: 5px; 
            cursor: pointer; 
            transition: background 0.3s ease; 
            margin-top: 55px;
           
            margin-bottom: 10px;
            width: 100px;
            height: 47px;
            font-family: 'Inter', sans-serif;
            
        }
        #volver:hover {
            background: linear-gradient(90deg, #5098CD 0%, #3D83B5 44.5%, #357AAB 54%, #206190 100%);
        }
        
        #titulo{
            
            margin-top: 90px;
            margin-bottom: 20px;
            text-align: center;
            margin-left: 600px;
            font-family: 'Poppins';
            font-size: 39px;
            font-weight: 600;
            line-height: 46.8px;
            letter-spacing: -0.03em;
            text-align: center;
            text-underline-position: from-font;
            text-decoration-skip-ink: none;

        }
        h1{
            color: rgba(0, 51, 102, 1);

        }

        /* LOGO LAFEDAR */
        .img-logo img {
            position: absolute;
            top: 0; 
            left: 0; 
            margin-left: 15px;
            margin-top: 50px;
            width: 400px; 
            height: auto;
        }

        /* ENCABEZADO */
        table thead {
            background: rgba(15, 79, 141, 0.83)!important;

        }

        table thead th {
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            font-weight: 800;
            line-height: 19.2px;
            letter-spacing: -0.03em;
            text-align: center !important;  /* Centrado horizontal */
            vertical-align: middle !important; /* Centrado vertical */
            color: rgba(255, 255, 255, 1); 
        }


        /* FILAS */
        table {
            border: none !important;
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0 10px; /* Espacio entre filas (10px vertical) */
            margin-top: 20px;
            
        }

        table td, table th {
            border: none !important; 
            padding: 10px; 
        }

        table thead th {
            background: rgba(15, 79, 141, 0.83)!important;
        }


        table tbody tr {
            background: rgba(217, 217, 217, 0.6); 
        }


        table tbody tr {
            margin-bottom: 10px; 
        }

        /* Para redondear las celdas de la primera y última columna */
        table thead th:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        table thead th:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        /* Asegurarse que el contenedor de la tabla ocupe todo el ancho */
        .container {
            width: 100% !important; 
            max-width: none !important; 
            padding: 0 15px; 
        }

        /* Centrar el contenedor de la tabla */
        .row.justify-content-center {
            display: flex;
            justify-content: center; 
            width: 100%;
        }

        .col-md-15 {
            width: 100%;
            padding-left: 25px;
            padding-right: 0;
        }

        /* Espaciado entre las filas */
        table tbody tr {
            margin-bottom: 10px; 
        }

        /*ESTILO PARA EL CONTENIDO*/
        table tbody td {
            font-family: 'Inter', sans-serif;   
            font-size: 17px;                    
            font-weight: 500;                  
            line-height: 18px;                 
            letter-spacing: -0.03em;            
            text-align: left;                   
            text-underline-position: from-font; 
            text-decoration-skip-ink: none;     
            color: rgba(15, 79, 141, 0.83);  
            background: rgba(217, 217, 217, 0.6);
        
            text-align: center;
        }

        /* Estilos para el encabezado de la tabla */
        table thead th {
            background: rgba(15, 79, 141, 0.83) !important; 
            color: white;
        }

        

        /*BOTON ELIMINAR*/
        button.btn-danger {
            padding: 0; 
            border: none; 
            background: none; 
            cursor: pointer; 
        }

        button.btn-danger img {
            width: 25px; 
            height: 25px; 
        }


    </style>
</head>

<body>
    <div class="container mt-5">
    <a href="{{ url('/home') }}" class="img-logo">
            <img src="{{ asset('storage/cursos/logo-cursos.png') }}" alt="Logo Cursos">
        </a>
        <header>
            <h1 class="text-center mb-4" id="titulo">Documentos de la Instancia: {{$instancia->id_instancia}}</h1>
        </header>

        <main>
        <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" class="btn btn-secondary" id="volver">Volver</a>
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
                            <form action="{{ route('verPlanillaPrevia', ['formularioId' => $doc->formulario_id, 'cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia]) }}" method="GET" >
                                @csrf
                                <button type="submit" style="border: none; background: none; padding: 0;" title="Ver Documento">
                                    <img src="{{ asset('storage/cursos/ver.png') }}" alt="Ver" style="width:30px; height:30px;">
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
