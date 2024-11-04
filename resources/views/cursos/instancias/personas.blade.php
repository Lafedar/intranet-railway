@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success" style="text-align: center">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="text-align: center">
            {{ session('error') }}
        </div>
    @endif

    <h1>Inscripción para el curso: {{ $curso->titulo }}</h1>
    <br>
    <h2>Número de Instancia: {{ $instancia->id_instancia }}</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nombre y Apellido</th>
                <th style="text-align: center">Acciones</th>
            </tr>
        </thead>
        <tbody>
    @foreach($personas as $persona)
        <tr>
            <td>
                {{ $persona->nombre_p }} {{ $persona->apellido }}
            </td>
            <td style="text-align: center" class="inscripcion-status-{{ $persona->id_p }}">
                
                    <form action="{{ route('inscribir.persona', ['id_persona' => $persona->id_p, 'instancia_id' => $instancia->id, 'numInstancia' => $instancia->id_instancia]) }}" method="POST" style="display:inline;" class="inscripcion-form" data-persona-id="{{ $persona->id_p }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Inscribir</button>
                    </form>
                
            </td>
        </tr>
    @endforeach
</tbody>

    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000); // 3000 milisegundos = 3 segundos
    });
</script>


@endsection
