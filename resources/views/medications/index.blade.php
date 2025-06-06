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



        <a href="https://extranetlafedar.netlify.app" target="_blank" type="button" class="btn btn-primary" id="btn-agregar">
            Agregar Solicitud de Medicamentos
        </a>


        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh'))
            <form method="GET" action="{{ route('medications.index') }}" class="mb-4 flex gap-4 items-center">
                <input type="text" name="persona" placeholder="Buscar por persona..." value="{{ request('persona') }}"
                    class="filter-item" />
                
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
                        <th class="text-center">Estado</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Items</th>
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

                                        <td>{{ $medication->estado }}</td>
                                        <td>{{ \Carbon\Carbon::parse($medication->created_at)->format('d/m/Y') }}</td>


                                        <td><form action="{{ route('medications.items', $medication->id) }}"
                                                        class="forms-medication-requests">
                                                        @csrf
                                                        @method('GET')
                                                        <button type="submit" title="Ver Items" id="icono">
                                                            <img src="{{ asset('storage/cursos/tocar.png') }}" loading="lazy"
                                                                alt="items" id="img-icono">
                                                        </button>
                                                    </form></td>
                                        
                                        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh'))
                                            <td>
                                                @if($medication->estado != 'Aprobación Pendiente')
                                                    <form action="{{ route('medications.delete', $medication->id) }}"
                                                        onsubmit="return confirm('¿Estás seguro de que deseas pasar a Aprobación Pendiente esta solicitud ?');"
                                                        class="forms-medication-requests">
                                                        @csrf
                                                        @method('GET')
                                                        <button type="submit" title="Pasar a Aprobación Pendiente" id="icono">
                                                            <img src="{{ asset('storage/cursos/exit.png') }}" loading="lazy"
                                                                alt="Aprobación Pendiente" id="img-icono">
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($medication->estado != 'Aprobada')
                                                    @if(is_object($matchedPerson))
                                                        <form id="approve-form-{{ $medication->id }}"
                                                            action="{{ route('medications.approval', ['id' => $medication->id, 'id_p' => $matchedPerson->id_p]) }}"
                                                            onsubmit="return confirm('¿Estás seguro de que deseas aprobar esta solicitud ?');"
                                                            class="forms-medication-requests">
                                                            @csrf
                                                            @method('GET')
                                                            <button title="Aprobar solicitud" id="approve-btn-{{ $medication->id }}" class="btn-disabled-med">
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
                                                            <button title="Aprobar solicitud" id="approve-btn-{{ $medication->id }}" class="btn-disabled-med">
                                                                <img src="{{ asset('storage/cursos/aprobar.png') }}" loading="lazy" alt="Aprobar"
                                                                    id="img-icono">
                                                            </button>
                                                        </form>
                                                    @endif

                                                @else
                                                @if(is_object($matchedPerson))
                                                    <a href="{{ route('medications.certificate', ['id' => $medication->id, 'id_p' => $matchedPerson->id_p]) }}"
                                                    target="_blank" class="forms-medication-requests" title="Ver Remito" id="icono">
                                                        <img src="{{ asset('storage/cursos/documentos.png') }}" loading="lazy" alt="Ver Remito" id="img-icono">
                                                    </a>
                                                @else
                                                    <a href="{{ route('medications.certificate', ['id' => $medication->id, 'id_p' => $medication->dni_persona]) }}"
                                                    target="_blank" class="forms-medication-requests" title="Ver Remito" id="icono">
                                                        <img src="{{ asset('storage/cursos/documentos.png') }}" loading="lazy" alt="Ver Remito" id="img-icono">
                                                    </a>
                                                @endif

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
<script>
    document.querySelectorAll('.approval-checkbox').forEach(checkbox => {
        const requestId = checkbox.dataset.id;

        function toggleApproveButton(id) {
            const checkboxes = document.querySelectorAll(`.approval-checkbox[data-id="${id}"]`);
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            const button = document.getElementById(`approve-btn-${id}`);
            if (button) {
                button.disabled = !anyChecked;
            }
        }

        checkbox.addEventListener('change', () => toggleApproveButton(requestId));

        // Evaluar al cargar la página
        toggleApproveButton(requestId);
    });
</script>



@endpush


