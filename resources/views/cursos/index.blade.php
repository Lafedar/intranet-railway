@extends('cursos.layouts.layout')
@yield('modal')  <!-- Asegúrate de incluir esto -->
<script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>
<div id="modalContainer"></div>
@section('content')
<div class="container mt-5 table-container">
<a href="{{ route('cursos.create') }}" class="btn btn-warning btn-sm">
    Crear Curso
</a>

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
                        <th>Acciones</th>
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
                        <td>
            
                        <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-warning btn-sm">
    Editar
</a>


                            <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este curso?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
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

