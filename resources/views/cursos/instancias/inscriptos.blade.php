<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscriptos en el Curso</title>

    <!-- Enlaces a fuentes y Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap&italic=true" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        #encabezados {
            display: flex;
            flex-direction: column; 
            align-items: center;    
            gap: 10px;  
            margin-left: 500px;            
        }
        #titulo {
            margin-top: 50px; 
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
            margin-top: 10px;
            
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


        @media (max-width: 1366px) and (min-width: 1280px), 
       (max-width: 1360px) and (min-width: 1280px), 
       (max-width: 1280px) {
    
    .form-acciones {
        display: flex;
        gap: 10px;
        justify-content: flex-start;  
        margin-top: 20px;
    }
   
    
    #BI {
        width: auto;  
        font-size: 16px;  
        padding: 8px 16px;  
    }

    #BI:hover {
        background: linear-gradient(90deg, #5098CD 0%, #3D83B5 44.5%, #357AAB 54%, #206190 100%);
    }

    
    form button {
        margin-left: 0px; 
    }

    
    .row.justify-content-center {
        display: flex;
        justify-content: flex-start; 
    }
}
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Mensaje de éxito -->
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
            <h1 id="titulo">Inscriptos en el Curso: {{ $curso->titulo }}</h1>
            <h2 id="titulo-sec">Número de Instancia: {{ $instancia->id_instancia }}</h2>
        </div>
        <!-- Información del curso e instancia -->
        
        
        <br>
        
        <br>
        <!-- Si no hay inscriptos -->
        @if($inscriptos->isEmpty())
            <p><b>No hay inscriptos en este curso.</b></p>
        @else
            <!-- Formulario para crear planilla -->
            @role(['administrador', 'Gestor-cursos'])
                <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" id="BI" class="btn btn-secondary mb-3">Volver</a>
                    <form action="{{ route('exportarInscriptos', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia]) }}" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-success" id="BI">Exportar a Excel</button>
                    </form>
                    @if($anexos != null)
                        <form action="{{ route('verPlanilla', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia, 'tipo' => 'ane' ]) }}" method="GET">
                            <button type="submit" class="btn btn-success" id="BI">Ver Anexo</button>
                        </form>
                    @else
                        <p><b>Agregue un Anexo para ver la planilla</b></p>
                    @endif
                    @if($cantAprobados > 0)
                        <form action="{{ route('enviarMail', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary" id="BI">Enviar Certificado</button>
                        </form>
                    @else
                        <form action="{{ route('enviarMail', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary" id="BI" disabled>Enviar Certificado</button>
                        </form>
                    @endif


                    <form action="{{ route('evaluarInstanciaTodos', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia, 'bandera' => 0]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" id="BI" style="margin-left: 480px">Aprobar a todos</button>
                    </form>

                    <form action="{{ route('evaluarInstanciaTodos', ['cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia, 'bandera' => 1]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" id="BI">Desaprobar a todos</button>
                    </form>
                    
                    
                </div>
            @endrole

            <!-- Tabla de inscriptos -->
            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Legajo</th>
                        <th>Nombre y Apellido</th>
                        <th>Fecha de Inscripción</th>
                        <th>Versión de Instancia</th>
                        <th>Evaluación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscriptos as $enrolamiento)
                        <tr>
                            <td>{{ $enrolamiento->persona->legajo }}</td>
                            <td>
                                @if ($enrolamiento->persona)
                                    {{ $enrolamiento->persona->nombre_p }} {{ $enrolamiento->persona->apellido }}
                                @else
                                    Persona no encontrada
                                @endif
                            </td>
                            <td>{{ $enrolamiento->fecha_enrolamiento ? $enrolamiento->fecha_enrolamiento->format('d/m/Y H:i') : 'No disponible' }}</td>
                            <td>{{ $instancia->version ?? 'N/A' }}</td>
                            <td>{{ $enrolamiento->evaluacion }}</td>
                            <td>
                                @role(['administrador', 'Gestor-cursos'])
                                    @if($enrolamiento->evaluacion == "No Aprobado")
                                        <form action="{{ route('desinscribir', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id]) }}" method="POST" style="display: inline-block; margin-right: 10px;">
                                            @csrf
                                            <button type="submit" title="Desuscribir" style="border: none; background: none; padding: 0;"><img src="{{ asset('storage/cursos/desuscribir.png') }}" loading="lazy" alt="Desuscribir" style="width: 27px; height:27px;"></button>
                                        </form>
                                    @endif
                                    @if($enrolamiento->evaluacion == "N/A")
                                        <form action="{{ route('evaluarInstancia', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'bandera' => 1]) }}" method="POST" style="display: inline-block; margin-right: 10px;">
                                            @csrf
                                            <button type="submit"  title="Desaprobar" style="border: none; background: none; padding: 0;"><img src="{{ asset('storage/cursos/exit.png') }}" loading="lazy" alt="Desaprobar" style="width: 27px; height:27px;"></button>
                                        </form>
                                        <form action="{{ route('evaluarInstancia', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'bandera' => 0]) }}" method="POST" style="display: inline-block; margin-right: 10px;">
                                            @csrf
                                            <button type="submit" title="Aprobar" style="border: none; background: none; padding: 0;"><img src="{{ asset('storage/cursos/aprobar.png') }}" loading="lazy" alt="Aprobar" style="width: 27px; height:27px;"></button>
                                        </form>
                                    @elseif($enrolamiento->evaluacion == "Aprobado") 
                                        <form action="{{ route('evaluarInstancia', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'bandera' => 1]) }}" method="POST" style="display: inline-block; margin-right: 10px;">
                                            @csrf
                                            <button type="submit"  title="Desaprobar" style="border: none; background: none; padding: 0;"><img src="{{ asset('storage/cursos/exit.png') }}" loading="lazy" alt="Desaprobar" style="width: 27px; height:27px;"></button>
                                        </form>
                                    @else
                                        <form action="{{ route('evaluarInstancia', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'bandera' => 0]) }}" method="POST" style="display: inline-block; margin-right: 10px;">
                                            @csrf
                                            <button type="submit" title="Aprobar" style="border: none; background: none; padding: 0;"><img src="{{ asset('storage/cursos/aprobar.png') }}" loading="lazy" alt="Aprobar" style="width: 27px; height:27px;"></button>
                                        </form>
                                    @endif
                                @endrole

                                @role(['administrador', 'Gestor-cursos'])
                                    @if($enrolamiento->evaluacion == "Aprobado")
                                        <form action="{{ route('generarCertificado', ['instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'personaId' => $enrolamiento->id_persona]) }}" method="POST" style="display: inline-block; margin-right: 10px;">
                                            @csrf
                                            <button type="submit" title="Ver Certificado" style="border: none; background: none; padding: 0;"><img src="{{ asset('storage/cursos/documentos.png') }}" loading="lazy" alt="Documentos" style="width: 27px; height:27px;"></button>
                                        </form>
                                    @endif
                                @endrole
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        // Ocultar los mensajes de éxito y error después de 3 segundos
        setTimeout(function() {
            $('.alert').fadeOut('slow'); // 'slow' es la duración de la animación
        }, 3000); // 3000 milisegundos = 3 segundos
    });
</script>
</body>
</html>
