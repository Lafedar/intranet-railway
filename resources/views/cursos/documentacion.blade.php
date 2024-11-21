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
                        <td>{{ $doc->formulario_id ?? "No hay anexos"}}</td>
                        <td>
                        

                        </td>
                        
                       
                    @endforeach
                </tbody>
            </table>
@endsection