<div class="col-md-12">
    <div class="row">
        <label for="title"><strong>Equipos:</strong></label>
        <table class="table table-striped table-bordered">
            <thead>
                <th class="text-center">ID</th>
                <th class="text-center">Marca</th>
                <th class="text-center">Modelo</th>
                <th class="text-center">Descripcion</th>
                <th class="text-center">Area</th>
                <th class="text-center">Localizacion</th>
            </thead>
            <tbody>
                @foreach($equipos as $equipo)
                    <tr onclick="manejarSeleccion(this.dataset.id)" data-id="{{ $equipo->id }}" data-dismiss="modal">
                        <td>{{ $equipo->id }}</td>
                        <td>{{ $equipo->marca }}</td>
                        <td>{{ $equipo->modelo }}</td>
                        <td>{{ $equipo->descripcion }}</td>
                        <td>{{ $equipo->id_area }}</td>
                        <td>{{ $equipo->id_localizacion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
