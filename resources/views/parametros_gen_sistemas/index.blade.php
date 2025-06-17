@extends('layouts.app')

@push('styles')
    

<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush



@section('content')


<!-- alertas -->
<div id="parametros-gen-container">
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


    <button type="button" id="btn-agregar" class="btn btn-primary" data-toggle="modal"
        data-target="#agregarModal">
        Agregar Parámetro
    </button>




    <!-- tabla de datos -->

    <div>
        <table>
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Descripcion</th>
                    <th class="text-center">Valor</th>
                    <th class="text-center">Origen</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($parameters as $parametro)
                    @if($parametro->origen == "Sistemas")
                        <tr class="text-center">
                            <td>{{ $parametro->id_param }}</td>
                            <td>{{ $parametro->descripcion_param }}</td>
                            <td>{{ $parametro->valor_param}}</td>
                            <td>{{ $parametro->origen}}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Acciones">

                                    <!-- Botón para abrir la modal de edición -->
                                    <button style="width: 70px;" data-toggle="modal"
                                        data-target="#editarModal{{ $parametro->id_param }}" id="icono"><img
                                            src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono"></button>
                                    @if($parametro->id_param != "PMB")
                                        <!-- Enlace para eliminar el parámetro -->
                                        <a href="{{ route('parametros.destroy', $parametro->id_param) }}"
                                            onclick="event.preventDefault(); if(confirm('¿Estás seguro de que deseas eliminar este parámetro?')) { document.getElementById('eliminar-parametro-{{ $parametro->id_param }}').submit(); }"
                                            id="icono"><img src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar"
                                                id="img-icono"></a>

                                        <!-- Formulario oculto para eliminar el parámetro -->
                                        <form id="eliminar-parametro-{{ $parametro->id_param }}"
                                            action="{{ route('parametros.destroy', $parametro->id_param) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                            </td>
                        </tr>

                    @endif
                @endforeach
    </div>


    </tbody>
    </table>
</div>



<!-- Modal -->
<div class="modal fade" id="agregarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="agregarFormulario" action="{{ route('guardar_datos') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Parámetro</h5>
                  
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Id:</label>
                        <input type="text" class="form-control" id="id_param" name="id_param" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_param">Descripcion:</label>
                        <input type="text" class="form-control" id="descripcion_param" name="descripcion_param"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="valor_param">Valor:</label>
                        <input type="text" class="form-control" id="valor_param" name="valor_param" required>
                    </div>
                    <div class="form-group">
                        <label for="origen">Origen:</label>
                        <input type="text" class="form-control" id="origen" name="origen" value="Sistemas" readonly>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        id="asignar-btn">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="asignar-btn">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal de edicion -->
@foreach($parameters as $parametro)
    <div class="modal fade" id="editarModal{{ $parametro->id_param }}" tabindex="-1" role="dialog"
        aria-labelledby="editarModalLabel{{ $parametro->id_param }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('parametros.update', ['parametro' => $parametro->id_param]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarModalLabel{{ $parametro->id_param }}">Editar Parámetro</h5>
                       
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="descripcion_param">Descripcion:</label>
                            <input type="text" class="form-control" id="descripcion_param" name="descripcion_param"
                                value="{{ $parametro->descripcion_param }}" required>
                        </div>
                        @if($parametro->id_param == "PMB")
                            <div class="form-group">
                                <label for="valor_param">Valor:</label>
                                <input type="number" class="form-control" id="valor_param" name="valor_param"
                                    value="{{ $parametro->valor_param }}" step="0.01" min="0" required>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="valor_param">Valor:</label>
                                <input type="text" class="form-control" id="valor_param" name="valor_param"
                                    value="{{ $parametro->valor_param }}" required>
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            id="asignar-btn">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="asignar-btn">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function confirmarEliminacion(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este parámetro?')) {
            // Realizar la solicitud para eliminar el registro
            window.location.href = "{{ url('/eliminar_parametro') }}/";
        }
    }

    $("document").ready(function () {
        setTimeout(function () {
            $("div.alert").fadeOut();
        }, 3000); // 5 secs

    });

</script>
<style>
    form {
        margin: 2px;
    }

    /* Estilos para centrar texto en mensajes */
    .alert-message {
        text-align: center;
    }

    .button-container {
        display: inline-block;
    }
</style>
@endpush
