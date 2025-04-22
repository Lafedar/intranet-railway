@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

@section('content')
    <div id="software-container">
        <!-- Mostrar mensaje flash si existe -->
        @if(session('success'))
            <div class="container" id="div-alert">
                <div class="row">
                    <div class="col-1"></div>
                    <div class="alert alert-success col-10 text-center" role="alert">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container" id="div-alert">
                <div class="row">
                    <div class="col-1"></div>
                    <div class="alert alert-danger col-10 text-center" role="alert">
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        @endif



        <a href="https://forms.office.com/r/SuSDALHbtx" type="button" class="btn btn-primary" data-toggle="modal"
            data-target="#agregarModal" id="btn-agregar">
            Agregar Solicitud de Medicamentos
        </a>

        <!-- tabla de datos -->
        <div>
            <table>
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Solicitante</th>
                        <th class="text-center">Medicamentos</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicationsRequests as $medication)
                                    <tr class="text-center">
                                        <td>{{ $medication->id }}</td>

                                        @php
                                            $person = $person->firstWhere('dni', $medication->dni_persona);
                                        @endphp

                                        @if($person != null)
                                            <td>{{ $person->apellido . ' ' . $person->nombre_p }}</td>
                                        @else
                                            <td>{{ $medication->dni_persona }}</td>
                                        @endif

                                        <td>{{ $medication->medicamento }}</td>
                                        <td>{{ $medication->cantidad }}</td>
                                        <td>{{ $medication->created_at }}</td>
                                        <td>{{ $medication->estado }}</td>
                                        <td>
                                            @if($medication->estado != 'No Aprobada')
                                                <form action="{{ route('medications.delete', $medication->id) }}"
                                                    onsubmit="return confirm('¿Estás seguro de que deseas desaprobar esta solicitud ?');"
                                                    style="display: inline-block; margin-right: 5px;">
                                                    @csrf
                                                    @method('GET')
                                                    <button type="submit" title="Desaprobar solicitud" id="icono">
                                                        <img src="{{ asset('storage/cursos/exit.png') }}" loading="lazy" alt="Desaprobar"
                                                            id="img-icono">
                                                    </button>
                                                </form>
                                            @endif

                                            @if($medication->estado != 'Aprobada')
                                                <form action="{{ route('medications.approval', $medication->id) }}"
                                                    onsubmit="return confirm('¿Estás seguro de que deseas aprobar esta solicitud ?');"
                                                    style="display: inline-block; margin-right: 5px;">
                                                    @csrf
                                                    @method('GET')
                                                    <button title="Aprobar solicitud" id="icono">
                                                        <img src="{{ asset('storage/cursos/aprobar.png') }}" loading="lazy" alt="Aprobar"
                                                            id="img-icono">
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('medications.certificate', $medication->id) }}"
                                                    style="display: inline-block; margin-right: 5px;">
                                                    @csrf
                                                    @method('GET')
                                                    <button title="Ver Remito" id="icono">
                                                        <img src="{{ asset('storage/cursos/documentos.png') }}" loading="lazy" alt="Ver Remito"
                                                            id="img-icono">
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('medications.show', $medication->id) }}"
                                                    style="display: inline-block; margin-right: 5px;">
                                                    @csrf
                                                    @method('GET')
                                                    <button title="Editar Solicitud" id="icono">
                                                        <img src="{{ asset('storage/cursos/editar.png') }}" loading="lazy" alt="Editar Solicitud"
                                                            id="img-icono">
                                                    </button>
                                                </form>
                                        </td>
                                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    

@endsection

@push('scripts')
    <!-- Carga de Bootstrap -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Ocultar los mensajes de éxito y error después de 3 segundos
            setTimeout(function () {
                $('.alert').fadeOut('slow'); // 'slow' es la duración de la animación
            }, 3000); // 3000 milisegundos = 3 segundos
        });
    </script>
    


@endpush