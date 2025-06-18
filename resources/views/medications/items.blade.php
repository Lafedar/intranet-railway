@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

@section('content')
    <div id="software-container">
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
        <h1 class="text-center">Items de la Solicitud de Medicamentos: {{ $medicationRequest->id }} </h1>

        <table>
            <thead>
                <tr>
                    <th class="text-center">Medicamento</th>
                    <th class="text-center">Cantidad Solicitada</th>
                    <th class="text-center">Cantidad Aprobada</th>
                    <th class="text-center">Aprobado</th>
                    <th class="text-center">Lote</th>
                    <th class="text-center">Vencimiento</th>
                    @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh'))
                        @if($medicationRequest->estado != "Aprobada")
                            <th class="text-center">Aprobar</th>
                        @endif
                        <th class="text-center">Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <form
                    action="{{ route('medications.approveMedicationRequest', ['request_id' => $medicationRequest->id, 'user_dni' => $medicationRequest->dni_persona]) }}"
                    method="POST" id="approve-form">
                    @csrf
                    @method('POST')

                    @foreach($itemsMedicationsRequests as $item)
                        <tr class="text-center">

                            <td>{{ $item->medicamento }}</td>
                            <td>{{ $item->cantidad_solicitada }}</td>
                            <td>{{ $item->cantidad_aprobada }}</td>
                            <td>
                                @if($item->aprobado == 1)
                                    Si
                                @else
                                    No
                                @endif
                            </td>
                            <td>{{ $item->lote_med ?? 'N/A' }}</td>
                            <td>
                                {{ $item->vencimiento_med ? \Carbon\Carbon::parse($item->vencimiento_med)->format('d/m/Y') : 'N/A' }}
                            </td>
                            @if($medicationRequest->estado != "Aprobada")
                                <td>
                                    <input type="checkbox" name="items[]" value="{{ $item->id }}" class="item-checkbox">
                                </td>
                            @endif
                            @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh'))
                                <td>
                                    @if($medicationRequest->estado != "Aprobada")

                                        <button type="button" class="btn-edit-item" data-item-id="{{ $item->id }}" id="icono"
                                            title="Editar item">
                                            <img src="{{ asset('storage/cursos/editar.png') }}" loading="lazy" alt="Editar item"
                                                id="img-icono">
                                        </button>

                                    @else
                                        Solicitud Aprobada
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach

                    <a href="{{ route('medications.index') }}" id="asignar-btn">Volver</a>
                    @if($medicationRequest->estado != "Aprobada")
                        <button type="submit" class="approve-selected-items ml-2" id="asignar-btn" disabled>Aprobar
                            Solicitud</button>
                    @endif
                </form>
            </tbody>

        </table>



    </div>

@endsection
@push('scripts')
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
        // Activar o desactivar el botón de aprobar según los checkboxes seleccionados
        $(document).ready(function () {
            // Deshabilitar el botón de aprobar inicialmente
            $('.approve-selected-items').prop('disabled', true);

            // Comprobar si el checkbox está marcado
            $('.item-checkbox').change(function () {
                // Si al menos un checkbox está seleccionado, habilitar el botón de aprobar
                if ($('.item-checkbox:checked').length > 0) {
                    $('.approve-selected-items').prop('disabled', false);
                } else {
                    $('.approve-selected-items').prop('disabled', true);
                }
            });
            // Manejar el clic en el botón de editar
            $('.btn-edit-item').click(function () {
                var itemId = $(this).data('item-id');
                // Redirigir a la página de edición del item
                window.location.href = '{{ url("medications/show") }}/' + itemId;
            });
        });

    </script>


@endpush