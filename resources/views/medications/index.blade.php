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



        <a href="https://forms.office.com/r/SuSDALHbtx" target="_blank" type="button" class="btn btn-primary"
            id="btn-agregar">
            Agregar Solicitud de Medicamentos
        </a>

        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh'))
            <form method="GET" action="{{ route('medications.index') }}" class="mb-4 flex gap-4 items-center">
                <input type="text" name="persona" placeholder="Buscar por persona..." value="{{ request('persona') }}"
                    class="filter-item" />
                <input type="text" name="medicamento" placeholder="Buscar por medicamento..."
                    value="{{ request('medicamento') }}" class="filter-item" />
                <button type="submit" id="asignar-btn">Filtrar</button>
                <a href="{{ route('medications.index') }}" class="text-sm text-red-500 ml-2">Quitar filtros</a>
            </form>
        @endrole


        <div>
            <table>
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Solicitante</th>
                        <th class="text-center">Medicamento 1</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Aprobado</th>
                        <th class="text-center">Medicamento 2</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Aprobado</th>
                        <th class="text-center">Medicamento 3</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Aprobado</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Estado</th>
                        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh'))
                            <th class="text-center">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicationsRequests as $medication)
                                    <tr class="text-center">
                                        <td>{{ $medication->id }}</td>
                                        @php
                                            $matchedPerson = $persons->firstWhere('dni', $medication->dni_persona);
                                        @endphp
                                        @if(is_object($matchedPerson))
                                            <td>{{ $matchedPerson->apellido . ' ' . $matchedPerson->nombre_p }}</td>
                                        @else
                                            <td>{{ $medication->dni_persona }}</td>
                                        @endif

                                        <td>{{ $medication->medicamento1 }}</td>
                                        <td>{{ $medication->cantidad1 }}</td>

                                        <td>
                                            @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh'))
                                                @if($medication->estado != "Completada")

                                                    <input type="hidden" name="approved_checkbox" value="0"
                                                        form="approve-form-{{ $medication->id }}">
                                                    <input type="checkbox" name="approved_checkbox" value="1" {{ $medication->aprobado1 == 1 ? 'checked' : '' }} form="approve-form-{{ $medication->id }}">
                                                @else
                                                    <input type="checkbox" name="approved_checkbox" value="1" {{ $medication->aprobado1 == 1 ? 'checked' : '' }} form="approve-form-{{ $medication->id }}" disabled>
                                                @endif

                                            @else
                                                <input type="checkbox" disabled>
                                            @endif
                                        </td>



                                        <td>{{ $medication->medicamento2 }}</td>
                                        <td>{{ $medication->cantidad2}}</td>
                                        <td>
                                            @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh'))
                                                @if($medication->medicamento2 != null && $medication->cantidad2 != null)
                                                    @if($medication->estado != "Completada")
                                                        <input type="hidden" name="approved2_checkbox" value="0"
                                                            form="approve-form-{{ $medication->id }}">
                                                        <input type="checkbox" name="approved2_checkbox" value="1" {{ $medication->aprobado2 == 1 ? 'checked' : '' }} form="approve-form-{{ $medication->id }}">
                                                    @else
                                                        <input type="checkbox" name="approved2_checkbox" value="1" {{ $medication->aprobado2 == 1 ? 'checked' : '' }} form="approve-form-{{ $medication->id }}" disabled>
                                                    @endif
                                                @endif
                                            @else
                                                <input type="checkbox" disabled>
                                            @endif
                                        </td>
                                        <td>{{ $medication->medicamento3 }}</td>
                                        <td>{{ $medication->cantidad3 }}</td>
                                        <td>
                                            @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh'))
                                                @if($medication->medicamento3 != null && $medication->cantidad3 != null)
                                                    @if($medication->estado != "Completada")
                                                        <input type="hidden" name="approved3_checkbox" value="0"
                                                            form="approve-form-{{ $medication->id }}">
                                                        <input type="checkbox" name="approved3_checkbox" value="1" {{ $medication->aprobado3 == 1 ? 'checked' : '' }} form="approve-form-{{ $medication->id }}">
                                                    @else
                                                        <input type="checkbox" name="approved3_checkbox" value="1" {{ $medication->aprobado3 == 1 ? 'checked' : '' }} form="approve-form-{{ $medication->id }}" disabled>
                                                    @endif
                                                @endif
                                            @else
                                                <input type="checkbox" disabled>
                                            @endif

                                        </td>
                                        <td>{{ $medication->created_at }}</td>
                                        <td>{{ $medication->estado }}</td>
                                        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh'))
                                            <td>
                                                @if($medication->estado != 'Aprobacion Pendiente')
                                                    <form action="{{ route('medications.delete', $medication->id) }}"
                                                        onsubmit="return confirm('¿Estás seguro de que deseas pasar a Aprobación Pendiente esta solicitud ?');"
                                                        class="forms-medication-requests">
                                                        @csrf
                                                        @method('GET')
                                                        <button type="submit" title="Pasar a Aprobacion Pendiente" id="icono">
                                                            <img src="{{ asset('storage/cursos/exit.png') }}" loading="lazy"
                                                                alt="Aprobacion Pendiente" id="img-icono">
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($medication->estado != 'Completada')
                                                    @if(is_object($matchedPerson))
                                                        <form id="approve-form-{{ $medication->id }}"
                                                            action="{{ route('medications.approval', ['id' => $medication->id, 'id_p' => $matchedPerson->id_p]) }}"
                                                            onsubmit="return confirm('¿Estás seguro de que deseas aprobar esta solicitud ?');"
                                                            class="forms-medication-requests">
                                                            @csrf
                                                            @method('GET')
                                                            <button title="Completar solicitud" id="icono">
                                                                <img src="{{ asset('storage/cursos/aprobar.png') }}" loading="lazy" alt="Aprobar"
                                                                    id="img-icono">
                                                            </button>
                                                        </form>


                                                    @else
                                                        <form id="approve-form-{{ $medication->id }}"
                                                            action="{{ route('medications.approval', ['id' => $medication->id, 'id_p' => $medication->dni_persona]) }}"
                                                            onsubmit="return confirm('¿Estás seguro de que deseas aprobar esta solicitud ?');"
                                                            class="forms-medication-requests">
                                                            @csrf
                                                            @method('GET')
                                                            <button title="Aprobar solicitud" id="icono">
                                                                <img src="{{ asset('storage/cursos/aprobar.png') }}" loading="lazy" alt="Aprobar"
                                                                    id="img-icono">
                                                            </button>
                                                        </form>
                                                    @endif

                                                @else
                                                    @if(is_object($matchedPerson))
                                                        <form
                                                            action="{{ route('medications.certificate', ['id' => $medication->id, 'id_p' => $matchedPerson->id_p]) }}"
                                                            class="forms-medication-requests">
                                                            @csrf
                                                            @method('GET')
                                                            <button title="Ver Remito" id="icono">
                                                                <img src="{{ asset('storage/cursos/documentos.png') }}" loading="lazy" alt="Ver Remito"
                                                                    id="img-icono">
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form
                                                            action="{{ route('medications.certificate', ['id' => $medication->id, 'id_p' => $medication->dni_persona]) }}"
                                                            class="forms-medication-requests">
                                                            @csrf
                                                            @method('GET')
                                                            <button title="Ver Remito" id="icono">
                                                                <img src="{{ asset('storage/cursos/documentos.png') }}" loading="lazy" alt="Ver Remito"
                                                                    id="img-icono">
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                                @if($medication->estado != 'Completada')
                                                    <form action="{{ route('medications.show', $medication->id) }}"
                                                        class="forms-medication-requests">
                                                        @csrf
                                                        @method('GET')
                                                        <button title="Editar Solicitud" id="icono">
                                                            <img src="{{ asset('storage/cursos/editar.png') }}" loading="lazy"
                                                                alt="Editar Solicitud" id="img-icono">
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        @endif
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