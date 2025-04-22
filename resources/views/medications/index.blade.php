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
                        <th class="text-center">Persona</th>
                        <th class="text-center">Medicamentos</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicationsRequests as $medication)

                        <tr class="text-center">
                            <td>{{ $medication->id }}</td>
                            <td>{{ $medication->dni_persona}}</td>
                            <td>{{ $medication->medicamento}}</td>
                            <td>{{$medication->cantidad}}</td>
                            <td>{{ $medication->created_at}}</td>
                            <td>

                                <form action="{{ route('medications.delete', $medication->id) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta solicitud ?');">
                                    @csrf
                                    @method('GET')
                                    <button type="submit" title="Eliminar solicitud" id="icono">
                                        <img src="{{ asset('storage/cursos/eliminar.png') }}" loading="lazy" alt="Eliminar">
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