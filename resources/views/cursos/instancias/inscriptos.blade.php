@extends('layouts.app')

@section('content')

<div class="container">
    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger text-center">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                    <th>Legajo</th>
                    <th>Nombre y Apellido</th>
                    <th>Evaluacion</th>
                    <th>Acciones</td>
                    
                </tr>
            </thead>
            <tbody>
    @foreach($inscritos as $enrolamiento)
    <tr>
        <td>{{ $enrolamiento->persona->legajo }}</td>
        <td>
            @if ($enrolamiento->persona)
                {{ $enrolamiento->persona->nombre_p }} {{ $enrolamiento->persona->apellido }}
            @else
                Persona no encontrada
            @endif
        </td>
        
        <td>{{$enrolamiento->evaluacion}}</td>
        <td>
        <form action="{{ route('desinscribir', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id]) }}" method="POST">
                                    @csrf
                                    @method('POST') 
                                    <button type="submit" class="btn btn-danger">Desinscribir</button>
                                </form>
                                
                                <!-- Formulario para aprobar -->
                        @if($enrolamiento->evaluacion == "Aprobado") 
                            <form action="{{ route('evaluarInstancia', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'bandera' => 1]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Desaprobar</button>
                            </form>
                        @else
                            <form action="{{ route('evaluarInstancia', ['userId' => $enrolamiento->id_persona, 'instanciaId' => $instancia->id_instancia, 'cursoId' => $curso->id, 'bandera' => 0]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Aprobar</button>
                            </form>
                        @endif
                        
                        
                        
        </td>
       
        
    </tr>
    @endforeach
</tbody>
        </table>
    @endif
</div>
@section('scripts')
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            
            setTimeout(function() {
                $('#success-message').fadeOut('slow');
            }, 3000);

            
            setTimeout(function() {
                $('#error-message').fadeOut('slow');
            }, 3000);
        });
    </script>
@endsection

@endsection
