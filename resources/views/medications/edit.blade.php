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
    <div class="container" id="container-edit-medications">
        <div id="cursos-instancias-edit-container">
            <h1 class="mb-4 text-center">Editar Solicitud de Medicamentos</h1>

            <form id="courseForm" action="{{ route('medications.update', ['id' => $medication->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row justify-content-center mb-3">
                    <div class="col-md-5">
                        <label for="medication1"><b>Medicamento 1</b></label>
                        <input type="text" class="form-control" id="medication1" name="medication1"
                            value="{{ $medication->medicamento1 }}" required oninput="toggleamount()">
                    </div>
                    <div class="col-md-1">
                        <label for="amount1"><b>Cantidad</b></label>
                        <input type="number" class="form-control" id="amount1" name="amount1"
                            value="{{ $medication->cantidad1 }}" required>
                    </div>
                    <div class="col-md-1">
                        <label for="approved1"><b>Aprobar</b></label>
                        <input type="hidden" name="approved1" value=0>
                        <input type="checkbox" id="approved1" name="approved1" value=1
                            style="margin-top: 10px; margin-left: 25px;" @checked($medication->aprobado1 == 1)>
                    </div>



                </div>

                <div class="row justify-content-center mb-3">
                    <div class="col-md-5">
                        <label for="medication2"><b>Medicamento 2</b></label>
                        <input type="text" class="form-control" id="medication2" name="medication2"
                            value="{{ $medication->medicamento2 }}" oninput="toggleamount()">
                    </div>
                    <div class="col-md-1">
                        <label for="amount2"><b>Cantidad</b></label>
                        <input type="number" class="form-control" id="amount2" name="amount2"
                            value="{{ $medication->cantidad2 }}">
                    </div>
                    <div class="col-md-1">
                        <label for="approved2"><b>Aprobar</b></label>
                        <input type="hidden" name="approved2" value=0>
                        <input type="checkbox" id="approved2" name="approved2" value=1
                            style="margin-top: 10px; margin-left: 25px;" @checked($medication->aprobado2 == 1)>
                    </div>
                </div>

                <div class="row justify-content-center mb-3">
                    <div class="col-md-5">
                        <label for="medication3"><b>Medicamento 3</b></label>
                        <input type="text" class="form-control" id="medication3" name="medication3"
                            value="{{ $medication->medicamento3 }}" oninput="toggleamount()">
                    </div>
                    <div class="col-md-1">
                        <label for="amount3"><b>Cantidad</b></label>
                        <input type="number" class="form-control" id="amount3" name="amount3"
                            value="{{ $medication->cantidad3 }}">
                    </div>
                    <div class="col-md-1">
                        <label for="approved3"><b>Aprobar</b></label>
                        <input type="hidden" name="approved3" value=0>
                        <input type="checkbox" id="approved3" name="approved3" value=1
                            style="margin-top: 10px; margin-left: 25px;" @checked($medication->aprobado3 == 1)>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('medications.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" id="asignar-btn" class="btn btn-primary">Guardar</button>
                </div>

            </form>
        </div>
    </div>



@endsection
@push('scripts')

    <script>
        function toggleamount() {
            var medication1 = document.getElementById('medication1').value;
            var amount1 = document.getElementById('amount1');
            var approved1 = document.getElementById('approved1');

            var medication2 = document.getElementById('medication2').value;
            var amount2 = document.getElementById('amount2');
            var approved2 = document.getElementById('approved2');

            var medication3 = document.getElementById('medication3').value;
            var amount3 = document.getElementById('amount3');
            var approved3 = document.getElementById('approved3');



            if (medication1.trim() !== "") {
                amount1.disabled = false;
                approved1.disabled = false;

            } else {
                amount1.disabled = true;
                amount1.value = ''; //borrar el contenido cuando se deshabilita
                approved1.disabled = true;
                approved1.checked = false; //desmarcar el checkbox cuando se deshabilita
            }

            if (medication2.trim() !== "") {
                amount2.readOnly = false;
                amount2.required = true;
                approved2.disabled = false;

            } else {
                amount2.readOnly = true;
                amount2.required = false;
                amount2.value = '';
                approved2.disabled = true;
                approved2.checked = false;
            }

            if (medication3.trim() !== "") {
                amount3.readOnly = false;
                amount3.required = true;
                approved3.disabled = false;

            } else {
                amount3.readOnly = true;
                amount3.required = false;
                amount3.value = '';
                approved3.disabled = true;
                approved3.checked = false;
            }
        }

        //ejecuto al cargar la página para que refleje el estado inicial
        document.addEventListener('DOMContentLoaded', toggleamount);
    </script>

@endpush