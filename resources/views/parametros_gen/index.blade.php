@extends('parametros_gen.layouts.layout')
@section('content')

<!-- alertas -->

<div class="content">
  <div class="row" style="justify-content: center">
    <div id="alert" class="alert alert-success col-md-10 text-center" style="display: none"></div>
  </div>
</div>

@if(Session::has('message'))
  <div class="container" id="div.alert">
    <div class="row">
      <div class="col-1"></div>
      <div class="alert {{Session::get('alert-class')}} col-10 text-center" role="alert">
        {{Session::get('message')}}
      </div>
    </div>
  </div>
@endif

<!-- tabla de datos -->
             
<div class="col-md-12">
    <form method="POST" action="{{ route('parametros_gen.store') }}">
        @csrf
        <table class="table table-striped table-bordered mx-auto"> <!-- Añadimos la clase mx-auto para centrar horizontalmente la tabla -->
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Informacion</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $dato)
                    <tr>
                        <td class="text-center">{{ $dato->Id }}</td>
                        <td><input type="text" name="Nombre" class="form-control" value="{{ $dato->Nombre }}"></td>
                        <td><input type="text" name="Informacion " class="form-control" value="{{ $dato->Informacion }}"></td>
                        <td class="text-center">
                        <button type="submit" class="btn btn-info">Aceptar</button>
                            <button type="button" onclick="cancelar(this.parentNode.parentNode)" class="btn btn-danger">Cancelar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>

  
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
</style>
<script>
    function guardar(fila) {
        // Lógica para guardar la información de la fila
        // Por ejemplo:
        alert('Información guardada: ' + fila.cells[1].querySelector('input').value + ' - ' + fila.cells[2].querySelector('input').value);
    }

    function cancelar(fila) {
        // Lógica para cancelar la edición y borrar los datos de la fila
        // Por ejemplo:
        fila.cells[1].querySelector('input').value = '';
        fila.cells[2].querySelector('input').value = '';
    }
</script>
<script>
    function agregarFila() {
        var tbody = document.querySelector('table tbody');
        var rowCount = tbody.rows.length;
        var newRow = tbody.insertRow(rowCount);

        // Inserta las celdas para cada columna
        var idCell = newRow.insertCell(0);
        var nombreCell = newRow.insertCell(1);
        var datosCell = newRow.insertCell(2);
        var accionesCell = newRow.insertCell(3);

        // Aplica estilos a las celdas de la nueva fila para centrar el contenido verticalmente
        idCell.className = "text-center align-middle"; 
        nombreCell.className = "align-middle"; 
        datosCell.className = "align-middle"; 
        accionesCell.className = "text-center align-middle"; 

        // Agrega los campos de entrada en las celdas correspondientes
        idCell.innerHTML = rowCount + 1; // El ID de la nueva fila
        nombreCell.innerHTML = '<input type="text" name="nombre" class="form-control">';
        datosCell.innerHTML = '<input type="text" name="datos" class="form-control">';

        // Agrega los botones de acciones con estilos de Bootstrap
        accionesCell.innerHTML = '<button onclick="guardar(this.parentNode.parentNode)" class="btn btn-info">Aceptar</button>' +
                                  '<button onclick="cancelar(this.parentNode.parentNode)" class="btn btn-danger">Cancelar</button>';
    }
</script>
<!-- <script>
  $(document).ready(function() {
    $('.btn-aceptar').click(function(event) {
        event.preventDefault(); // Prevenir el envío del formulario por defecto
        
        var fila = $(this).closest('tr'); // Obtener la fila más cercana al botón "Aceptar"
        var Nombre = fila.find('input[name="Nombre"]').val(); // Obtener el valor del input de nombre
        var Infomarcion = fila.find('input[name="Informacion"]').val(); // Obtener el valor del input de datos

        // Realizar la solicitud al servidor para guardar la información en la base de datos
        $.ajax({
            type: 'POST',
            url: '{{ route("parametros_gen.store") }}', // Ruta de la acción "store" en el controlador
            data: {
                '_token': '{{ csrf_token() }}',
                'Nombre': Nombre,
                'Informacion': Informacion
            },
            success: function(response) {
                // Bloquear los inputs después de guardar la información
                fila.find('input').prop('disabled', true);
            },
            error: function(xhr, status, error) {
                // Manejar errores si la solicitud falla
                console.error(xhr.responseText);
            }
        });
    });
});
</script> -->
@stop