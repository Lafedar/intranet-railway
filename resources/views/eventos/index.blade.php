@extends('layouts.app')

@push('styles')
    <link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
    
@endpush

@push('scripts')
    <!-- Elimina la carga duplicada de jQuery y otros archivos -->
    <script type="text/javascript">
        var url_ = "{{ url('/eventos') }}";
        var url_show = "{{ url('/eventos/show') }}";

        $(document).ready(function() {
            // Aquí se inicializa el calendario
            $('#calendar').fullCalendar({
                locale: 'es', // Ajusta el idioma según sea necesario
                events: url_,
                // Otros ajustes según tu necesidad
            });
        });
    </script>
@endpush

@section('content')
    <div class="container" id="calendar-container">
        <div class="row">
            <div class="col"></div>
            <div class="col-10">
                <div id="calendar"></div>
            </div>
            <div class="col"></div>
        </div>
    </div>

    @include('eventos.agregar')
@endsection
