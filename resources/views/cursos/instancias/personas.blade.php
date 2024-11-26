<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción al curso</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap&italic=true" rel="stylesheet">

    <style>
        #encabezados {
            display: flex;
            flex-direction: column; 
            align-items: center;    
            gap: 10px;  
            margin-left: 500px;            
        }
        #titulo {
            margin-top: 100px; 
            font-family: 'Poppins';
            font-size: 39px;
            font-weight: 600;
            line-height: 46.8px;
            letter-spacing: -0.03em;
            color: rgba(0, 51, 102, 1);
            text-align: center; 
            text-underline-position: from-font;
            text-decoration-skip-ink: none;
        }
        #titulo-sec {
            font-family: 'Poppins';
            font-size: 39px;
            font-weight: 600;
            line-height: 46.8px;
            letter-spacing: -0.03em;
            color: rgba(0, 51, 102, 1);
            text-align: center; 
            text-underline-position: from-font;
            text-decoration-skip-ink: none;
            max-width: 450px;  
        }

        /*CUPO*/
        h5{
            margin-top: -70px;
            font-family: 'Poppins';
            font-size: 25px;
            font-weight: 600;
            line-height: 46.8px;
            letter-spacing: -0.03em;
            text-underline-position: from-font;
            text-decoration-skip-ink: none;
            color: rgba(0, 51, 102, 1);
        }

        /*BOTON VOLVER*/
        #volver{
            margin-left: 12px;
            height:45px;
            background: linear-gradient(90deg, #206190 0%, #357AAB 44.5%, #3D83B5 54%, #5098CD 100%);
            border: none; 
            color: white; 
            font-size: 18px; 
            font-weight: bold;
            border-radius: 5px; 
            cursor: pointer; 
            transition: background 0.3s ease; 
            margin-top: 12px;
            margin-left: 4px;
            padding: 10px 20px; 
        
            width: 100px;
            height: 47px;
            font-family: 'Inter', sans-serif;
        }
        #volver:hover {
            background: linear-gradient(90deg, #5098CD 0%, #3D83B5 44.5%, #357AAB 54%, #206190 100%);
        }
    
        /*BOTON INSCRIBIR*/
        #BI {
            background: linear-gradient(90deg, #206190 0%, #357AAB 44.5%, #3D83B5 54%, #5098CD 100%);
            border: none; 
            color: white; 
            padding: 10px 20px; 
            font-size: 18px; 
            font-weight: bold;
            border-radius: 5px; 
            cursor: pointer; 
            transition: background 0.3s ease; 
            margin-top: 10px;
            margin-right: 0px;
            margin-bottom: 10px;
            width: 250px;
            font-family: 'Inter', sans-serif;
        }


        #BI:hover {
            background: linear-gradient(90deg, #5098CD 0%, #3D83B5 44.5%, #357AAB 54%, #206190 100%);
        }
        
        /*FILTRO*/
        #filtro {
            background: linear-gradient(90deg, #206190 0%, #357AAB 44.5%, #3D83B5 54%, #5098CD 100%);
            border: 1px solid #357AAB;  
            color: rgba(255, 255, 255, 1) !important; 
            padding: 10px; 
            font-size: 16px; 
            border-radius: 5px;
            width: 150%; 
            height: 45px;
            margin-top: 20px;
        }
        #filtro::placeholder {
            color: rgba(255, 255, 255, 1); 
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
    </style>

</head>
<body>
    <div class="container mt-4">
    @if(session('success'))
    <div class="alert alert-success" style="text-align: center; display: inline-block; position: fixed; top: 5%; left: 50%; transform: translate(-50%, -50%);">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" style="text-align: center; display: inline-block; position: fixed; top: 5%; left: 50%; transform: translate(-50%, -50%);">
        {{ session('error') }}
    </div>
@endif

        <a href="{{ url('/home') }}" class="img-logo">
            <img src="{{ asset('storage/cursos/logo-cursos.png') }}" alt="Logo Cursos">
        </a>
        <div id="encabezados" style="text-align: center;">
            <h1 id="titulo">Inscripción para el curso: {{ $curso->titulo }}</h1>
            <h2 id="titulo-sec">Número de Instancia: {{ $instancia->id_instancia }}</h2>
        </div>
        <h5>Cupo disponible: <span id="cupoDisponible">{{ $restantes }}</span></h5> 
        
        <div class="form-group">
            <input type="text" id="filtro" class="form-control" placeholder="Filtrar por Nombre, Apellido, Area o Legajo" autocomplete="off" style="width: 366px">
        </div>
        
        <form action="{{ route('inscribir.varias.personas', ['instancia_id' => $instancia->id_instancia, 'cursoId' => $curso->id]) }}" method="POST">
            @csrf
            <table class="table table-bordered table-striped text-center">
                <thead>
                    <button type="submit" class="btn btn-primary" style="margin-bottom: 10px; margin-right: 10px" id="BI">Inscribir seleccionados</button>
                    <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" class="btn btn-secondary" style="margin-bottom: 10px;" id="volver">Volver</a>
                    
                    <tr>
                        <th>Legajo</th>
                        <th>Nombre y Apellido</th>
                        <th>Área</th>
                        <th style="text-align: center">Inscribir</th>
                        <th style="text-align: center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($personasConEstado as $persona)
                        <tr>
                            <td>{{ $persona->legajo }}</td>
                            <td>{{ $persona->apellido }} {{ $persona->nombre_p }}</td>
                            <td>
                                @if($persona->area)
                                    {{ $persona->area ? $persona->area->nombre_a : 'Sin área asignada' }}
                                @else
                                    N/A
                                @endif
                            </td>

                            <td style="text-align: center">
                                @if($persona->estadoEnrolado)
                                    <p>Ya inscripto</p>
                                @else
                                    <input type="checkbox" class="persona-checkbox" name="personas[{{ $persona->id_p }}]" value="1">
                                @endif
                            </td>
                            
                            <td>
                                @if($persona->estadoEnrolado)
                                
                                    <form action="{{ route('desinscribir', ['userId' => $persona->id_p, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id]) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-danger">Desuscribir</button>
                                        
                                    </form>
                                @else
                                    N/A 
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Función para actualizar el cupo disponible
        function actualizarCupo() {
            var totalSeleccionados = $("input[name^='personas']:checked").length;
            var cupoMaximo = {{ $restantes}};  // El valor del cupo original
            var cupoDisponible = cupoMaximo - totalSeleccionados;
            $("#cupoDisponible").text(cupoDisponible);

            // Si el cupo es 0, deshabilitar solo los checkboxes que no están seleccionados
            if (cupoDisponible <= 0) {
                // Deshabilitar los checkboxes no seleccionados
                $("input[name^='personas']:not(:checked)").prop('disabled', true);
                $("#cupoDisponible").css('color', 'red'); // Cambiar color a rojo cuando el cupo sea 0
            } else {
                // Habilitar todos los checkboxes si hay cupo
                $("input[name^='personas']").prop('disabled', false);
                $("#cupoDisponible").css('color', ''); // Restaurar el color original
            }
        }

        // Llamar a la función al cargar la página por si ya hay checkboxes seleccionados
        actualizarCupo();

        // Escuchar el cambio en los checkboxes
        $("input[name^='personas']").change(function() {
            actualizarCupo();
        });

        // Filtrado en tiempo real
        $('#filtro').on('input', function() {
            var filtro = $(this).val().toLowerCase();

            // Iterar sobre las filas de la tabla
            $('table tbody tr').each(function() {
                var nombreApellido = $(this).find('td:nth-child(2)').text().toLowerCase(); //segunda columna
                var legajo = $(this).find('td:nth-child(1)').text().toLowerCase(); //primera columna
                var area = $(this).find('td:nth-child(3)').text().toLowerCase(); //tercera columna
                
                // Si el filtro no coincide ni con nombre/apellido ni con legajo, ocultar la fila
                if (nombreApellido.indexOf(filtro) === -1 && legajo.indexOf(filtro) === -1 && area.indexOf(filtro) === -1 ) {
                    $(this).hide();  // Si no coincide, ocultar la fila
                } else {
                    $(this).show();  // Si coincide, mostrar la fila
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Ocultar los mensajes de éxito y error después de 3 segundos
        setTimeout(function() {
            $('.alert').fadeOut('slow'); // 'slow' es la duración de la animación
        }, 3000); // 3000 milisegundos = 3 segundos
    });
</script>

