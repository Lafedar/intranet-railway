@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

@section('content')
    <div id="software-container">
        <h1 class="text-center">Items de la Solicitud de Medicamentos: {{ $medicationRequest->id }} </h1>

        <table>
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Medicamento</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Aprobado</th>
                    <th class="text-center">Lote</th>
                    <th class="text-center">Vencimiento</th>
                    @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh'))
                        <th class="text-center">Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($itemsMedicationsRequests as $item)
                    <tr class="text-center">
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->medicamento }}</td>
                        <td>{{ $item->cantidad }}</td>
                        <td>
                            @if($item->aprobado == 1)
                                Si
                            @else
                                No
                            @endif
                        <td>{{ $item->lote_med }}</td>
                         <td>{{ \Carbon\Carbon::parse($item->vencimiento_med)->format('d/m/Y') }}</td>

                        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh'))
                            <td>
                                @if($medicationRequest->estado != "Aprobada")
                                    @if($item->aprobado == 0)
                                        <form action="{{ route('medications.items.approve', [$item->id, $medicationRequest->id]) }}"
                                            class="forms-medication-requests">
                                            @csrf
                                            @method('GET')
                                            <button type="submit" title="Aprobar item" id="icono">
                                                <img src="{{ asset('storage/cursos/aprobar.png') }}" loading="lazy" alt="Aprobar item"
                                                    id="img-icono">
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('medications.items.desapprove', [$item->id, $medicationRequest->id]) }}"
                                            class="forms-medication-requests">
                                            @csrf
                                            @method('GET')
                                            <button type="submit" title="Desaprobar item" id="icono">
                                                <img src="{{ asset('storage/cursos/exit.png') }}" loading="lazy" alt="Desaprobar item"
                                                    id="img-icono">
                                            </button>
                                        </form>
                                    @endif
                                    @if($item->aprobado != 1)
                                        <form action="{{ route('medications.show', $item->id) }}" class="forms-medication-requests">
                                            @csrf
                                            @method('GET')
                                            <button title="Editar item" id="icono">
                                                <img src="{{ asset('storage/cursos/editar.png') }}" loading="lazy" alt="Editar item"
                                                    id="img-icono">
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    Solicitud Aprobada
                                @endif

                            </td>

                        @endif


                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('medications.index') }}" id="asignar-btn" class="btn btn-primary">Volver</a>


    </div>

@endsection