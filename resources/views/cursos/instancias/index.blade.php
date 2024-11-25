<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instancias del Curso</title>
    <!-- Link de Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap&italic=true" rel="stylesheet">
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <div id="modalContainer"></div>

        <!-- Mensajes de éxito y error -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <a href="{{ url('/home') }}" class="img-logo">
            <img src="{{ asset('storage/cursos/logo-cursos.png') }}" alt="Logo Cursos">
        </a>


        
        <div class="buttons-title-container">
            <h1 id="titulo">Instancias del Curso: {{ $curso->titulo }}</h1>

            @role(['administrador', 'Gestor-cursos'])
                <a href="{{ route('cursos.instancias.create', ['instanciaId' => $cantInstancias, 'curso' => $curso->id]) }}" class="btn btn-warning btn-sm mb-3" id="BCI">
                    Crear Nueva Instancia
                </a>
            @endrole

            <a href="{{ route('cursos.index') }}" class="btn btn-secondary" id="volver">Volver</a>
        </div>
   

        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    @role(['administrador', 'Gestor-cursos'])
                        <th>Cupo</th>
                        <th>Cupos Restantes</th>
                        <th>Modalidad</th>
                        <th>Capacitador</th>
                        <th>Lugar</th>
                        <th>Estado</th>
                        <th>Version</th>
                    @endrole
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($instancesEnrollment as $instance)
                    <tr>
                        <td>{{ $instance->id_instancia }}</td>
                        <td>{{ \Carbon\Carbon::parse($instance->fecha_inicio)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($instance->fecha_inicio)->format('d/m/Y') }}</td>
                        @role(['administrador', 'Gestor-cursos'])
                            <td>{{ $instance->cupo }}</td>
                            <td>
                                @if ($instance->restantes == null)
                                    <span class="badge bg-danger text-dark">
                                        <i class="bi bi-x-circle-fill"></i> completo
                                    </span>
                                @elseif ($instance->restantes === 0)
                                    <span class="badge bg-danger text-dark">
                                        <i class="bi bi-x-circle-fill"></i> completo
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill"></i> {{ $instance->restantes }} disponibles
                                    </span>
                                @endif
                            </td>
                            <td>{{ $instance->modalidad }}</td>
                            <td>{{ $instance->capacitador }}</td>
                            <td>{{ $instance->lugar }}</td>
                            <td>{{ $instance->estado }}</td>
                            <td>{{ $instance->version }}</td>
                        @endrole
                        <td>
    @role(['administrador', 'Gestor-cursos'])
        @php
            // Verificar la disponibilidad de la instancia
            $availabilityItem = $availability->firstWhere('idInstance', $instance->id);
        @endphp

        @if ($availabilityItem)
            @if ($availabilityItem['enabled'])
                @if ($instance->restantes > 0)
                    <a href="{{ route('cursos.instancias.personas', ['cursoId' => $curso->id, 'instanceId' => $instance->id_instancia]) }}" style="margin: 5px" title="Inscribir personas">
                        <img src="{{ asset('storage/cursos/inscribir.png') }}" alt="Inscribir" style="width:30px; height:30px;">
                    </a>
                @endif
            @endif
        @else
        @endif
        <a href="{{ route('cursos.instancias.edit', ['instancia' => $instance->id_instancia, 'cursoId' => $curso->id]) }}" style="margin: 5px" title="Editar">
            <img src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" style="width:30px; height:30px;">
        </a>
        
        @if ($instance->restantes == $instance->cupo)
            <form action="{{ route('cursos.instancias.destroy', ['cursoId' => $curso->id, 'instanciaId' => $instance->id_instancia]) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta instancia?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" style="margin: 5px">
                    <img src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar" style="width:30px; height:30px;">
                </button>
            </form>
        @endif

        <a href="{{ route('verDocumentos', [$instance->id_instancia, $curso->id]) }}" title="Ver Documentos" style="margin: 5px;">
            <img src="{{ asset('storage/cursos/documentos.png') }}" alt="Inscriptos" style="width:30px; height:30px;">
        </a>

        <a href="{{ route('cursos.instancias.inscriptos', [$instance->id_instancia, $curso->id, 'tipo'=> 'ane']) }}" title="Ver Inscriptos" style="margin: 5px;">
            <img src="{{ asset('storage/cursos/inscriptos.png') }}" alt="Inscriptos" style="width:35px; height:35px;">
        </a>
    @endrole
</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <footer >
        <p>​LABORATORIOS LAFEDAR S.A | LABORATORIOS FEDERALES ARGENTINOS S.A</p>
    </footer> 

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zyfU7lmcd6CU9Lyj6UXbdfABpCkBTSXELRkJG4xA" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0v8FqFjcJzK0wthp8A5OGwWxFme6m9HuCSKhPz4vF3T21tGo" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // Ocultar los mensajes de éxito y error después de 3 segundos
            $('.alert').each(function() {
                var alert = $(this);
                setTimeout(function() {
                    alert.fadeOut('slow');
                }, 3000);
            });
        });
    </script>

</body>
</html>

<style>
#volver{
    margin-top: 43px;
    margin-left: 12px;
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
    margin-left: 12px;
    margin-bottom: 10px;
    width: 100px;
    height: 47px;
    font-family: 'Inter', sans-serif;
    
}
#volver:hover {
    background: linear-gradient(90deg, #5098CD 0%, #3D83B5 44.5%, #357AAB 54%, #206190 100%);
}
  



#titulo{
    
    margin-top: 100px;
    text-align: center;
    margin-left: 600px;
   
font-family: 'Poppins';
font-size: 39px;
font-weight: 600;
line-height: 46.8px;
letter-spacing: -0.03em;
text-align: center;
text-underline-position: from-font;
text-decoration-skip-ink: none

    
}

h1{
    color: rgba(0, 51, 102, 1);

}
/*BOTON CREAR CURSO*/
#BCI {
    background: linear-gradient(90deg, #206190 0%, #357AAB 44.5%, #3D83B5 54%, #5098CD 100%);
    border: none; 
    color: white; 
    padding: 10px 20px; 
    font-size: 18px; 
    font-weight: bold;
    border-radius: 5px; 
    cursor: pointer; 
    transition: background 0.3s ease; 
    margin-top: 60px;
    margin-left: 5px;
    margin-bottom: 10px;
    width: 300px;
    font-family: 'Inter', sans-serif;
   
}


#BCI:hover {
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

/*FILTRO*/
.filter-container {
    display: flex;              
    justify-content: center;    
    align-items: center;        
    gap: 10px;                  
    height: 10vh;              
}

.filter-item {
    width: 300px;                
    min-width: 100px;          
}

.filter-item input {
    background: linear-gradient(90deg, #206190 0%, #357AAB 44.5%, #3D83B5 54%, #5098CD 100%);
    border: 1px solid #357AAB;  
    color: rgba(255, 255, 255, 1) !important; 
    padding: 10px; 
    font-size: 16px; 
    border-radius: 5px;
    width: 150%; 
    height: 45px;
    margin-top: 85px;
}
.filter-item input::placeholder {
    color: rgba(255, 255, 255, 1); 
}

.filter-item select {
    background: linear-gradient(90deg, #206190 0%, #357AAB 44.5%, #3D83B5 54%, #5098CD 100%);
    border: 1px solid #357AAB;  
    color: rgba(255, 255, 255, 1); 
    padding: 10px;
    font-size: 16px; 
    border-radius: 5px;
    width: 150%; 
    height: 45px;
    margin-left: 150px;
    margin-top: 85px;
}

.filter-item button {
    background: linear-gradient(90deg, #206190 0%, #357AAB 44.5%, #3D83B5 54%, #5098CD 100%);
    color: rgba(255, 255, 255, 1); 
    border: none; 
    padding: 10px 20px; 
    font-size: 16px; 
    border-radius: 5px; 
    cursor: pointer; 
    transition: background 0.3s ease; 
    height: 45px;
    width: 150%;
    margin-left: 300px;
    margin-top: 85px;
}

.filter-item button:hover {
    background: linear-gradient(90deg, #5098CD 0%, #3D83B5 44.5%, #357AAB 54%, #206190 100%);
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

footer {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: rgba(15, 79, 141, 0.83);
    color: white;
    text-align: center;
    padding: 20px;
    font-family: 'Inter', sans-serif;
    font-weight: 200;
}

footer p {
    margin: 0; 
    padding: 0;
    font-family: Spline Sans;
    font-size: 14px;
    font-weight: 400;
    line-height: 16.8px;
    letter-spacing: 0.06em;
    text-align: center;
    text-underline-position: from-font;
    text-decoration-skip-ink: none;
    color: rgba(255, 255, 255, 0.6);

}
</style>
