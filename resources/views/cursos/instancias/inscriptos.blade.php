@extends('layouts.app')

@section('content')
<div class="container">
<h1>Inscriptos en el Curso: </h1><h3>{{ $curso->titulo }}</h3>
    <br>
    <h2>ID del Curso: </h2><h4>{{ $curso->id }}</h4>
    <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" class="btn btn-secondary" style="margin-bottom: 10px;">Volver</a>
    @if($inscritos->isEmpty())
        <p>No hay inscriptos en este curso.</p>
    @else
    
        <table class="table table-bordered table-striped text-center">
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
        <td>
        <form action="{{ route('desinscribir', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id]) }}" method="POST">
                                    @csrf
                                    @method('POST') 
                                    <button type="submit" class="btn btn-danger">Desinscribir</button>
                                </form>
        </td>
       
        
    </tr>
    @endforeach
</tbody>
        </table>
    @endif
</div>
@endsection
