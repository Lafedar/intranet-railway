<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos</title>
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
/*BOTON CREAR CURSO*/
#BCC {
    background: linear-gradient(90deg, #206190 0%, #357AAB 44.5%, #3D83B5 54%, #5098CD 100%);
    border: none; 
    color: white; 
    padding: 10px 20px; 
    font-size: 18px; 
    font-weight: bold;
    border-radius: 5px; 
    cursor: pointer; 
    transition: background 0.3s ease; 
    margin-top: 50px;
    margin-left: 12px;
    margin-bottom: 10px;
    width: 200px;
    height: 60px;
    font-family: 'Inter', sans-serif;
   
}


#BCC:hover {
    background: linear-gradient(90deg, #5098CD 0%, #3D83B5 44.5%, #357AAB 54%, #206190 100%);
}

/* LOGO LAFEDAR */
.img-logo img {
    position: absolute;
    top: 0; 
    left: 0; 
    margin-left: 20px;
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

</head>
<body>

<a href="{{ url('/home') }}" class="img-logo">
    <img src="{{ asset('storage/cursos/logo-cursos.png') }}" loading="lazy" alt="Logo Cursos">
</a>

    <div class="container mt-5 table-container">
        @role(['administrador', 'Gestor-cursos'])
        <form action="{{ route('cursos.index') }}" method="GET" class="mb-4">
        <div class="filter-container">
    <div class="filter-item">
        <input type="text" name="nombre_curso" class="form-control" placeholder="Buscar por título"
            value="{{ old('nombre_curso', $nombreCurso) }}">
    </div>
    <div class="filter-item">
        <select name="area_id" class="form-control">
            <option value="" {{ old('area_id', $areaId) === null ? 'selected' : '' }}>Seleccionar un área</option>
            <option value="all" {{ old('area_id', $areaId) == 'all' ? 'selected' : '' }}>Todas las áreas</option> 

            @foreach ($areas as $area)
                <option value="{{ $area->id_a }}" {{ old('area_id', $areaId) == $area->id_a ? 'selected' : '' }}>
                    {{ $area->nombre_a }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="filter-item">
        <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
    </div>
</div>

        </form>
        @endrole

        <a href="{{ route('cursos.create') }}" class="btn btn-warning btn-sm" id="BCC">
            Crear Curso
        </a>

        <div class="row justify-content-center">
            <div class="col-md-15"> 
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Obligatorio</th>
                            @role(['administrador', 'Gestor-cursos'])
                            <th>Codigo</th>
                            <th>Area</th>
                            @endrole
                            <th>Fecha de Creación</th>
                            @role(['administrador', 'Gestor-cursos'])
                            <th>Cant. Inscriptos</th>
                            <th>% Aprobados</th>
                            @endrole
                            @if(!Auth::user()->hasRole('administrador') && !Auth::user()->hasRole('Gestor-cursos'))
                            <th>Certificado</th>
                            @else
                            <th>Instancias</th>
                            @endrole
                            @role(['administrador', 'Gestor-cursos'])
                            <th>Acciones</th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cursosData as $curso)
                        <tr>
                            <td>{{ $curso->titulo }}</td>
                            <td>{{ $curso->descripcion }}</td>
                            <td>{{ $curso->obligatorio ? 'Sí' : 'No' }}</td>
                            @role(['administrador', 'Gestor-cursos'])
                            <td>{{ $curso->codigo ?? 'N/A'}}</td>
                            <td>
                                @if($curso->areas->isEmpty()) 
                                    <span>N/A</span>
                                @else
                                    @if($curso->areas->count() == $totalAreas)
                                        <span>Todas las áreas</span>
                                    @else
                                        @foreach($curso->areas as $area)
                                            <span>{{ $area->nombre_a ?? 'N/A' }}/</span><br>
                                        @endforeach
                                    @endif
                                @endif
                            </td>
                            @endrole
                            <td>{{ $curso->created_at->format('d/m/Y') }}</td>
                            @role(['administrador', 'Gestor-cursos'])
                            <td>{{ $curso->cantInscriptos}}</td>
                            <td>{{ number_format($curso->porcentajeAprobados, 2) }}%</td>
                            @endrole
                            <td>            
                            @role(['administrador', 'Gestor-cursos'])                
                                <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" class="btn" title="Ver Instancia">
                                <img src="{{ asset('storage/cursos/tocar.png') }}"  loading="lazy" alt="Ver Instancia">
                                </a>
                            @endrole
                                @if(Auth::user()->dni == $personaDni->dni && $curso->evaluacion == "Aprobado") 
                                    @if(!Auth::user()->hasRole('administrador') && !Auth::user()->hasRole('Gestor-cursos'))
                                    <form action="{{ route('generarCertificado', ['cursoId' => $curso->id, 'personaId' => $personaDni->id_p]) }}" method="POST" title="Ver Certificado">
                                        @csrf
                                        <button type="submit" class="btn btn-success" style="border: none; background: none; padding: 0;"><img src="{{ asset('storage/cursos/ver.png') }}" alt="Ver" style="width:30px; height:30px;"></button>
                                    </form>
                                    @endif
                                @endif
                            </td>
                            @role(['administrador', 'Gestor-cursos'])
                            <td>
                                <a href="{{ route('cursos.edit', $curso->id) }}" class="btn" title="Editar Curso">
                                    <img src="{{ asset('storage/cursos/editar.png') }}" loading="lazy" alt="Editar">
                                </a>
                                @if($curso->cantInscriptos == 0)
                                    <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este curso y sus instancias?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar Curso">
                                            <img src="{{ asset('storage/cursos/eliminar.png') }}" loading="lazy" alt="Eliminar">
                                        </button>

                                    </form>
                                @endif
                            </td>
                            @endrole
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <footer >
        <p>​LABORATORIOS LAFEDAR S.A | LABORATORIOS FEDERALES ARGENTINOS S.A</p>
    </footer> 
    <script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#successMessage').fadeOut('slow');
            }, 3000);

            setTimeout(function() {
                $('#errorMessage').fadeOut('slow');
            }, 3000);
        });
    </script>

</body>
</html>




