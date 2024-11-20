@extends('cursos.layouts.layout')
@section('content')


<table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Formulario</th>
                        <th>Acciones</th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach($documentos as $doc)
                    <tr>
                        <td>{{ $doc->valor_formulario }}</td>
                        <td>
                        <form action="{{ route('verPlanillaPrevia', ['formularioId' => $doc->formulario_id, 'cursoId' => $curso->id]) }}" method="GET" style="margin-bottom: 20px;">
    @csrf
    <button type="submit" class="btn btn-success">Ver</button>
</form>

                        </td>
                        
                       
                    @endforeach
                </tbody>
            </table>
@endsection