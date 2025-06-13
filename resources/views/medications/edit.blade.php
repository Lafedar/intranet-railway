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
            <h1 class="mb-4 text-center">Editar Item</h1>

            <form id="courseForm" action="{{ route('medications.items.update', ['id' => $item->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="d-flex flex-column align-items-center" style="max-width: 400px; margin: 0 auto;">
                    <div class="d-flex mb-3 gap-3 w-100"> <!-- <--- agregar w-100 -->
                        <div class="flex-grow-1">
                            <label for="medicamento"><b>Medicamento</b></label>
                            <input type="text" class="form-control" id="medicamento" name="medicamento"
                                value="{{ $item->medicamento }}" required>
                        </div>

                        <div style="width: 120px;">
                            <label for="cantidad_solicitada"><b>Solicitado</b></label>
                            <input type="number" class="form-control" id="cantidad_solicitada" name="cantidad_solicitada"
                                value="{{ $item->cantidad_solicitada }}" min="1" required readonly>
                        </div>
                        <div style="width: 120px;">
                            <label for="cantidad_aprobada"><b>Aprobado</b></label>
                            <input type="number" class="form-control" id="cantidad_aprobada" name="cantidad_aprobada"
                                value="{{ $item->cantidad_aprobada }}" min="1" max="{{ $item->cantidad_solicitada }}" required>
                        </div>
                    </div>

                    <div class="w-100 mb-3">
                        <label for="lote_med"><b>Lote</b></label>
                        <input type="text" class="form-control" id="lote_med" name="lote_med" value="{{ $item->lote_med }}">
                    </div>

                    <div class="w-100 mb-3">
                        <label for="vencimiento_med"><b>Vencimiento</b></label>
                        <input type="date" class="form-control" id="vencimiento_med" name="vencimiento_med"
                            value="{{ $item->vencimiento_med }}">
                    </div>
                </div>





                <input type="hidden" name="previous_url" value="{{ url()->previous() }}">
                <div class="text-center mt-4">
                    <a href="#" id="asignar-btn" class="cancelar-btn">Cancelar</a>

                    <button type="submit" id="asignar-btn">Guardar</button>
                </div>

            </form>
        </div>
    </div>



@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cancelarBtns = document.querySelectorAll('.cancelar-btn');

        cancelarBtns.forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const previousUrl = document.querySelector('input[name="previous_url"]')?.value;
                if (previousUrl) {
                    window.location.href = previousUrl;
                } else {
                    alert('No se encontró la URL anterior.');
                }
            });
        });
    });

</script>