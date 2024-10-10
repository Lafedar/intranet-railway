@extends('cursos.layouts.layout')

@section('content')
<div class="container mt-5 table-container">
    <h1 class="mb-4 text-center">Listado de Cursos</h1>
    <div class="row justify-content-center">
        <div class="col-md-10"> 
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Obligatorio</th>
                        <th>Fecha de Creación</th>
                        <th>Instancias</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cursos as $curso)
                    <tr>
                        <td>{{ $curso->titulo }}</td>
                        <td>{{ $curso->descripcion }}</td>
                        <td>{{ $curso->obligatorio ? 'Sí' : 'No' }}</td>
                        <td>{{ $curso->created_at->format('d/m/Y') }}</td>
                        <td>                            
                            <a href="{{ route('cursos.instancias.index', ['cursoId' => $curso->id]) }}" class="btn btn-primary btn-sm">
                                Ver Instancias
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="footer-lafedar"></div>
@endsection
