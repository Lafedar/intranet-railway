@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Inscriptos en el Course: </h1><h3>{{ $curso->titulo }}</h3>
    <br>
    <h2>ID del Course: </h2><h4>{{ $curso->id }}</h4>
    
    @if($inscritos->isEmpty())
        <p>No hay inscritos en este curso.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre y Apellido</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inscritos as $enrolamiento)
                <tr>
                    <td>{{ $enrolamiento->persona->nombre_p }} {{ $enrolamiento->persona->apellido }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
