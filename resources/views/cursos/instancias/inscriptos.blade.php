@extends('layouts.app')

@section('content')
<div class="container">
<h1>Inscriptos en el Curso: </h1><h3>{{ $curso->titulo }}</h3>
    <br>
    <h2>ID del Curso: </h2><h4>{{ $curso->id }}</h4>
    <a href="{{ url()->previous() }}" class="btn btn-secondary" style="margin-bottom: 10px;">Volver</a>
    @if($inscritos->isEmpty())
        <p>No hay inscriptos en este curso.</p>
    @else
    
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre y Apellido</th>
                    <th>Area</th>
                    <th>Acciones</td>
                </tr>
            </thead>
            <tbody>
    @foreach($inscritos as $enrolamiento)
    <tr>
        <td>
            @if ($enrolamiento->persona)
                {{ $enrolamiento->persona->nombre_p }} {{ $enrolamiento->persona->apellido }}
            @else
                Persona no encontrada
            @endif
        </td>
        <td>{{$enrolamiento->persona->area}}</td>
        
    </tr>
    @endforeach
</tbody>
        </table>
    @endif
</div>
@endsection
