@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

@endpush
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert" id="errorMessage">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
        <script>
            setTimeout(function () {
                var errorMessage = document.getElementById('errorMessage');
                if (errorMessage) {
                    errorMessage.classList.remove('show');
                    errorMessage.classList.add('fade');
                }
            }, 3000);
        </script>
    @endif
    <div class="container mt-5">
        <div id="cursos-instancias-edit-container">
            <h1 class="mb-4 text-center">Editar Solicitud de Medicamentos</h1>
            
            <form id="courseForm"
                action="{{ route('medications.edit', ['id' => $medication->id]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="start_date"><b>Medicamentos</b></label>
                    <input type="text" class="form-control" id="medicamento" name="medicamento"
                        value="{{ $medication->medicamento }}" required>
                </div>
                <div class="form-group">
                    <label for="end_date"><b>Cantidad</b></label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad"
                        value="{{ $medication->cantidad}}">
                </div>
                
             
                <a href="{{ route('medications.index') }}" id="asignar-btn">Cancelar</a>

                <button type="submit" id="asignar-btn">Guardar</button>


            </form>
        </div>

    </div>

@endsection