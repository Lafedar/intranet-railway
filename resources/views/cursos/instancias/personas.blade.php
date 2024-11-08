@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success" style="text-align: center">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="text-align: center">
            {{ session('error') }}
        </div>
    @endif

    <h1>Inscripción para el curso: {{ $curso->titulo }}</h1>
    <br>
    <h2>Número de Instancia: {{ $instancia->id_instancia }}</h2>
    <h5>Cupo disponible: <span id="cupoDisponible">{{ $restantes }}</span></h5> 
    
    <div class="form-group">
        
        <input type="text" id="filtro" class="form-control" placeholder="Filtrar por Nombre, Apellido o Legajo" autocomplete="off" style="width: 300px">
    </div>
    
    <form action="{{ route('inscribir.varias.personas', ['instancia_id' => $instancia->id, 'numInstancia' => $instancia->id_instancia]) }}" method="POST">
            @csrf
            <table class="table table-bordered table-striped">
                <thead>
                
                <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" class="btn btn-secondary" style="margin-bottom: 10px;">Volver</a>


                <button type="submit" class="btn btn-primary" style="margin-bottom: 10px;">Inscribir seleccionados</button>
                    <tr>
                        <th>Legajo</th>
                        <th>Nombre y Apellido</th>
                        <th>Area</th>
                        <th style="text-align: center">Inscribir</th>
                        <th style="text-align: center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($personasConEstado as $persona)
                    <tr>
                        <td>{{ $persona->legajo }} </td>
                        <td>{{ $persona->nombre_p }} {{ $persona->apellido }}</td>
                        
                        
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
        </form>
                        <td>
                            @if($persona->estadoEnrolado)
                            <form action="{{ route('desinscribir', ['userId' => $persona->id_p, 'instanciaId' => $instancia->id, 'numInstancia' => $instancia->id_instancia]) }}" method="POST">
                                    @csrf
                                    @method('POST') <!-- Esto indica que es una solicitud POST -->
                                    <button type="submit" class="btn btn-danger">Desinscribir</button>
                                </form>
                            @else
                            N/A 
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            
        
    </div>

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
                var nombreApellido = $(this).find('td:nth-child(2)').text().toLowerCase(); // Nombre y apellido en la segunda columna
                var legajo = $(this).find('td:nth-child(1)').text().toLowerCase(); // Legajo en la primera columna
                
                // Si el filtro no coincide ni con nombre/apellido ni con legajo, ocultar la fila
                if (nombreApellido.indexOf(filtro) === -1 && legajo.indexOf(filtro) === -1) {
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

@endsection
