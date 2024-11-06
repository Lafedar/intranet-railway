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

    <h1>Inscripción para el curso: {{ $curso->id }}</h1>
    <br>
    <h2>Número de Instancia: {{ $instancia->id_instancia }}</h2>
    <h5>Cupo disponible: <span id="cupoDisponible">{{ $instancia->cupo }}</span></h5> 

    <form action="{{ route('inscribir.varias.personas', ['instancia_id' => $instancia->id, 'numInstancia' => $instancia->id_instancia]) }}" method="POST">
        @csrf
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre y Apellido</th>
                    <th>Area</th>
                    <th style="text-align: center">Inscribir</th>
                </tr>
            </thead>
            <tbody>
            @foreach($personasConEstado as $persona)
                <tr>
                    <td>{{ $persona->nombre_p }} {{ $persona->apellido }}</td>
                    <td>{{ $persona->area }}</td>
                    <td style="text-align: center">
                        @if($persona->estadoEnrolado)
                            <p>Ya inscripto</p>
                        @else
                            <input type="checkbox" class="persona-checkbox" name="personas[{{ $persona->id_p }}]" value="1">
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary" style="margin-bottom: 10px;">Inscribir seleccionados</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Función para actualizar el cupo disponible
        function actualizarCupo() {
            var totalSeleccionados = $("input[name^='personas']:checked").length;
            var cupoMaximo = {{ $instancia->cupo }};  // El valor del cupo original
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
    });
</script>

@endsection
