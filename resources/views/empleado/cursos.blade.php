@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Listado de Cursos y Evaluaciones de: <!--insertar nombre de la persona--></h1>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Curso</th>
                <th>Fecha</th>
                <th>Evaluación</th>
                <th>Capacitador</th>
                <th>Modalidad</th>
                <th>Fecha</th>
                <th>Evaluacion</th>

            </tr>
        </thead>
       
    </table>
</div>
@endsection
