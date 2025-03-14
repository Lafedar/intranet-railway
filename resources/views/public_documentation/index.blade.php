@extends('layouts.app')
@push('styles')

<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

@section('content')
@if(Session::has('message'))
    <div class="alert {{ Session::get('alert-class') }}">
        {{ Session::get('message') }}
    </div>
@endif

<!-- alertas -->
<div id="documentos-politicas-container" >
  <div class="content">
    <div class="row" style="justify-content: center">
      <div id="alert" class="alert alert-success col-md-10 text-center" style="display: none"></div>
    </div>
  </div>

  @if(Session::has('message'))
    <div class="container" id="div.alert">
    <div class="row">
      <div class="col-1"></div>
      <div class="alert {{Session::get('alert-class')}} col-10 text-center" role="alert">
      {{Session::get('message')}}
      </div>
    </div>
    </div>
  @endif

  <!-- tabla de datos -->
  @role('administrador')
  <button class="btn btn-info" data-toggle="modal" data-target="#agregar" id="btn-agregar">Agregar</button>
  @endrole
  @role('politicas')
  <button class="btn btn-info" data-toggle="modal" data-target="#agregar" id="btn-agregar">Agregar</button>
  @endrole
  <div>
    <table>
      <thead>
        <th class="text-center">ID</th>
        <th class="text-center">Título</th>
        <th class="text-center">Fecha</th>
        <th class="text-center">Acciones</th>
      </thead>
      <tbody>
        @if(count($documentation))
      @foreach($documentation as $document)
      <tr>
      <td width="60">{{sprintf('%05d', $document->id)}}</td>
      <td width="500">{{$document->titulo}}</td>
      <td class="text-center" width="107">{!! \Carbon\Carbon::parse($document->fecha)->format("d-m-Y") !!}</td>
      <td width="100">
      <div class="text-center">
        <!-- Boton de descargar archivo -->
        @if($document->pdf != null)
      <a href="{{ Storage::url($document->pdf) }}" title="Descargar Archivo" data-position="top" data-delay="50"
      data-tooltip="Descargar Archivo" download><img src="{{ asset('storage/cursos/descargar.png') }}"
      alt="Descargar" id="img-icono"></a>
    @else
    <a data-position="top" data-delay="50" download title="Descargar Archivo"><img
    src="{{ asset('storage/cursos/descargar.png') }}" alt="Descargar" id="img-icono"></a>
  @endif
        <!-- Boton de editar archivo -->
        @can('editar-politica')
      <button data-id="{{$document->id}}" data-titulo="{{$document->titulo}}" data-fecha="{{$document->fecha}}"
      data-pdf="{{$document->pdf}}" data-toggle="modal" data-target="#editar" title="Editar" id="icono"><img
      src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono"></button>
    @endcan
        <!-- Boton de eliminar archivo -->
        @can('eliminar-politica')
      <a href="{{url('destroy_public_documentation', $document->id)}}" class="btn btn-danger btn-sm" title="Borrar"
      onclick="return confirm ('Está seguro que desea eliminar el documento ?')" data-position="top"
      data-delay="50" data-tooltip="Borrar" title="Eliminar" id="icono"><img
      src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar" id="img-icono"></a>
    @endcan
      </div>
      </td>
      </tr>
    @endforeach
    @endif
      </tbody>
    </table>
    @include('public_documentation.edit')
    {{ $documentation->appends($_GET)->links() }}
  </div>
  @push('modales')
    @include('public_documentation.create')
  @endpush

</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  $("policys").ready(function () {
    setTimeout(function () {
      $("div.alert").fadeOut();
    }, 3000); //  secs

  });
</script>

<script>
  // Script para el modal de edición
  $('#editar').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var titulo = button.data('titulo');
    var fecha = button.data('fecha');
    var pdf = button.data('pdf');
    var modal = $(this);
    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #title').val(titulo);
    modal.find('.modal-body #date').val(fecha);

    if (pdf && pdf.length > 0) {
      modal.find('.elim_pdf').show();
      modal.find('#pdf_link').attr('href', '{{ Storage::url('') }}' + pdf);
    } else {
      modal.find('.elim_pdf').hide();
    }
  });

  // Script para el modal de creación (si es necesario)
  $('#agregar').on('show.bs.modal', function (event) {
    var modal = $(this);
    // Aquí puedes manejar el formulario de creación, si es necesario
  });


</script>
@endpush
