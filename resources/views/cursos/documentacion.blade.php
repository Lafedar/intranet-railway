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
        <td>{{ $doc->formulario_id ?? "No hay anexos" }}</td>
        <td>
            @if($doc->formulario_id) 
                <form action="{{ route('verPlanillaPrevia', ['formularioId' => $doc->formulario_id, 'cursoId' => $curso->id, 'instanciaId' => $instancia->id_instancia]) }}" method="GET" style="margin-bottom: 20px;">
                    @csrf
                    <button type="submit" class="btn btn-success">Ver</button>
                </form>
            @else
                
            @endif
        </td>
    </tr>
@endforeach

                </tbody>
            </table>
@endsection