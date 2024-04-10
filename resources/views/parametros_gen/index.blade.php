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
@if (session('success'))
    <div class="alert alert-success alert-message">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-message">
        {{ session('error') }}
    </div>
@endif


<!-- tabla de datos -->
             
<div class="col-md-12"> 
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Informacion</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
    @foreach($parametros as $parametro)
        <tr class="text-center">
            <td>{{ $parametro->Id }}</td>
            <td>{{ $parametro->Nombre }}</td>
            <td>{{ $parametro->Informacion }}</td>
            <td>
                <div class="btn-group" role="group" aria-label="Acciones">
                    <!-- Botón para abrir la modal de edición -->
                    <form action="{{ route('parametros.update', $parametro->Id) }}" method="POST">
                        <button class="btn btn-info btn-sm action-button" data-toggle="modal" data-target="#editarModal{{ $parametro->Id }}">Editar</button>
                    
                    </form>
                    
                    <!-- Botón para eliminar el parámetro -->
                    <form action="{{ route('parametros.destroy', $parametro->Id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm action-button" onclick="return confirm('¿Estás seguro de que deseas eliminar este parámetro?')">Eliminar</button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
</tbody>
    </table>
</div>



        <!-- Modal -->
        <div class="modal fade" id="agregarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="agregarFormulario" action="{{ route('guardar_datos') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar Parámetro</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="Nombre" name="Nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="informacion">Información:</label>
                                <input type="text" class="form-control" id="Informacion" name="Informacion" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal de edicion -->
        @foreach($parametros as $parametro)
        <div class="modal fade" id="editarModal{{ $parametro->Id }}" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel{{ $parametro->Id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('parametros.update', ['parametro' => $parametro->Id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel{{ $parametro->Id }}">Editar Parámetro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $parametro->Nombre }}" required>
                    </div>
                    <div class="form-group">
                        <label for="informacion">Información:</label>
                        <input type="text" class="form-control" id="informacion" name="informacion" value="{{ $parametro->Informacion }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
    
@stop
<script>
    function confirmarEliminacion(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este parámetro?')) {
            // Realizar la solicitud para eliminar el registro
            window.location.href = "{{ url('/eliminar_parametro') }}/";
        }
    }
</script>
<style>
    form{
        margin: 2px;
    }
    /* Estilos para centrar texto en mensajes */
    .alert-message {
        text-align: center;
    }
</style>