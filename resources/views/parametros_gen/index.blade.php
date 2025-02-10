@extends('layouts.app')

@push('styles')

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush


@section('content')
<!-- alertas -->
<div id="software-container">
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
    @if ($errors->has('correo_no_existe'))
        <div class="alert alert-danger text-center">
            {{ $errors->first('correo_no_existe') }}
        </div>
    @endif
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#agregarModal" id="btn-agregar">
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
                @foreach($parametros as $parametro)
                    @if($parametro->origen == "Mantenimiento")
                        <tr class="text-center">
                            <td>{{ $parametro->id_param }}</td>
                            <td>{{ $parametro->descripcion_param }}</td>
                            <td>{{ $parametro->valor_param}}</td>
                            <td>{{ $parametro->origen}}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Acciones">
                                    <!-- Botón para abrir la modal de edición -->
                                    <button data-toggle="modal" data-target="#editarModal{{ $parametro->id_param }}"
                                        title="Editar" id="icono"><img src="{{ asset('storage/cursos/editar.png') }}"
                                            alt="Editar" id="img-icono">
                                    </button>

                                    @if($parametro->id_param !== 'PMAIL' && $parametro->id_param !== 'PDIAS')
                                        <!-- Enlace para eliminar el parámetro -->
                                        <a href="{{ route('parametros.destroy', $parametro->id_param) }}"
                                            class="btn btn-danger btn-sm action-button ml-2 rounded"
                                            onclick="event.preventDefault(); if(confirm('¿Estás seguro de que deseas eliminar este parámetro?')) { document.getElementById('eliminar-parametro-{{ $parametro->id_param }}').submit(); }"
                                            id="icono" title="Eliminar"><img src="{{ asset('storage/cursos/eliminar.png') }}"
                                                alt="Eliminar" id="img-icono">
                                        </a>


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
                            <input type="text" class="form-control" id="origen" name="origen" value="Mantenimiento"
                                readonly>
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
</div>


<!-- Modal de edicion -->
@foreach($parametros as $parametro)
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
                        <div class="form-group">
                            <label for="valor_param">Valor:</label>
                            @if($parametro->id_param === 'PDIAS')
                                <input type="number" min="2" max="7" class="form-control" id="valor_param" name="valor_param"
                                    value="{{ $parametro->valor_param }}" required>
                            @elseif($parametro->id_param === 'PMAIL')
                                <input type="email" class="form-control" id="valor_param" name="valor_param"
                                    value="{{ $parametro->valor_param }}" required>

                            @else
                                <input type="text" class="form-control" id="valor_param" name="valor_param"
                                    value="{{ $parametro->valor_param }}" required>
                            @endif
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
@endforeach
</div>


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function confirmarEliminacion(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este parámetro?')) {
                window.location.href = "{{ url('/eliminar_parametro') }}/";
            }
        }
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
@endsection