@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid" id="documentos-proyectos-container">
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
    <button class="btn btn-info" data-toggle="modal" data-target="#agregar" id="proyectos-btn-agregar"> Agregar</button>
    <div>
        <h1>
            <div class="form-inline pull-right">
                <form method="GET">
                    <div class="form-group">
                        <label>
                            <h6>ID:</h6>
                        </label>
                        <input type="text" name="id_proyecto" class="form-control col-md-1" id="id_proyecto"
                            value="{{$id_proyecto}}">
                        &nbsp
                        <label>
                            <h6><b>Título:</b></h6>
                        </label>
                        <input type="text" name="titulo_proyecto" class="form-control col-md-2" id="titulo_proyecto"
                            value="{{$titulo_proyecto}}">
                        &nbsp
                        <label>
                            <h6><b>Observación:</b></h6>
                        </label>
                        <input type="text" name="obs_proyecto" class="form-control col-md-2" id="obs_proyecto"
                            value="{{$obs_proyecto}}">
                        &nbsp
                        <label>
                            <h6><b>Fecha:</b></h6>
                        </label>
                        <input type="date" name="fecha_proyecto" class="form-control" step="1"
                            value="{{$fecha_proyecto}}">
                        &nbsp
                        <button type="submit" class="btn btn-default" id="asignar-btn"> Buscar</button>
                </form>
            </div>
        </h1>
    </div>

    <div id="proyectos-table">
        <table>
            <thead>
                <th class="text-center">ID</th>
                <th class="text-center">Título</th>
                <th class="text-center">Observación</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Acciones</th>
            </thead>
            <tbody>
                @if(count($proyectos))
                    @foreach($proyectos as $proyecto)
                        <tr>
                            <td width="80">{{sprintf('%05d', $proyecto->id)}}</td>
                            <td>{{$proyecto->titulo}}</td>
                            <td>{{$proyecto->obs}}</td>
                            <td width="107">{!! \Carbon\Carbon::parse($proyecto->fecha)->format("d-m-Y") !!}</td>
                            <td width="200" align="center">
                                <form action="{{route('destroy_proyecto', $proyecto->id)}}" method="put">

                                    <div>
                                        @if($proyecto->asm != null)
                                            <a href="{{ Storage::url($proyecto->asm) }}" class="btn btn-primary btn-sm" title="ASM"
                                                data-position="top" data-delay="50" data-tooltip="ASM" download>ASM</a>
                                        @else
                                            <a class="btn btn-secondary btn-sm" data-position="top" data-delay="50" download>ASM</a>
                                        @endif

                                        @if($proyecto->dwg != null)
                                            <a href="{{ Storage::url($proyecto->dwg) }}" class="btn btn-primary btn-sm" title="DWG"
                                                data-position="top" data-delay="50" data-tooltip="DWG" download>DWG</a>
                                        @else
                                            <a class="btn btn-secondary btn-sm" data-position="top" data-delay="50" download>DWG</a>
                                        @endif

                                        @if($proyecto->par != null)
                                            <a href="{{ Storage::url($proyecto->par) }}" class="btn btn-primary btn-sm" title="PAR"
                                                data-position="top" data-delay="50" data-tooltip="PAR" download>PAR</a>
                                        @else
                                            <a class="btn btn-secondary btn-sm" data-position="top" data-delay="50" download>PAR</a>
                                        @endif
                                        <h6></h6>
                                        @if($proyecto->stl != null)
                                            <a href="{{ Storage::url($proyecto->stl) }}" class="btn btn-primary btn-sm" title="STL"
                                                data-position="top" data-delay="50" data-tooltip="STL" download>STL</a>
                                        @else
                                            <a class="btn btn-secondary btn-sm" data-position="top" data-delay="50" download>STL</a>
                                        @endif

                                        @if($proyecto->pdf != null)
                                            <a href="{{ Storage::url($proyecto->pdf) }}" class="btn btn-primary btn-sm" title="PDF"
                                                data-position="top" data-delay="50" data-tooltip="PDF" download>PDF</a>
                                        @else
                                            <a class="btn btn-secondary btn-sm" data-position="top" data-delay="50" download>PDF</a>
                                        @endif

                                        @if($proyecto->mpp != null)
                                            <a href="{{ Storage::url($proyecto->mpp) }}" class="btn btn-primary btn-sm" title="MPP"
                                                data-position="top" data-delay="50" data-tooltip="MPP" download>MPP</a>
                                        @else
                                            <a class="btn btn-secondary btn-sm" data-position="top" data-delay="50" download>MPP</a>
                                        @endif

                                        @can('editar-proyectos')
                                            <a href="#" class="btn btn-info btn-sm" data-id="{{$proyecto->id}}"
                                                data-titulo="{{$proyecto->titulo}}" data-fecha="{{$proyecto->fecha}}"
                                                data-obs="{{$proyecto->obs}}" data-asm="{{$proyecto->asm}}"
                                                data-dwg="{{$proyecto->dwg}}" data-par="{{$proyecto->par}}"
                                                data-stl="{{$proyecto->stl}}" data-pdf="{{$proyecto->pdf}}"
                                                data-mpp="{{$proyecto->mpp}}" data-toggle="modal" data-target="#editar"> Editar</a>
                                        @endcan

                                        @can('eliminar-proyectos')
                                            <button type="submit" class="btn btn-danger btn-sm btn-borrar" data-tooltip="Borrar">
                                                X</button>
                                        @endcan
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        @push('modales')
            @include('proyectos.create')
        @endpush
        @include('proyectos.edit')

        {{ $proyectos->appends($_GET)->links() }}

    </div>
</div>


<script>
    $("document").ready(function () {
        setTimeout(function () {
            $("div.alert").fadeOut();
        }, 5000);

    });
</script>

<script>
    $('#editar').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var titulo = button.data('titulo')
        var fecha = button.data('fecha')
        var obs = button.data('obs')
        var asm = button.data('asm')
        var dwg = button.data('dwg')
        var par = button.data('par')
        var stl = button.data('stl')
        var pdf = button.data('pdf')
        var pdf = button.data('mpp')
        var modal = $(this)

        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #titulo').val(titulo);
        modal.find('.modal-body #fecha').val(fecha);
        modal.find('.modal-body #obs').val(obs);

        if (asm.length == 0) {
            $("div.elim_asm").hide()
        }
        else {
            $("div.elim_asm").show()
        }
        if (dwg.length == 0) {
            $("div.elim_dwg").hide()
        }
        else {
            $("div.elim_dwg").show()
        }
        if (par.length == 0) {
            $("div.elim_par").hide()
        }
        else {
            $("div.elim_par").show()
        }
        if (stl.length == 0) {
            $("div.elim_stl").hide()
        }
        else {
            $("div.elim_stl").show()
        }
        if (pdf.length == 0) {
            $("div.elim_pdf").hide()
        }
        else {
            $("div.elim_pdf").show()
        }
        if (mpp.length == 0) {
            $("div.elim_mpp").hide()
        }
        else {
            $("div.elim_mpp").show()
        }
    })
</script>

<script>
    $(document).ready(function () {
        $('#alert').hide();
        $('.btn-borrar').click(function (e) {
            e.preventDefault();
            if (!confirm("¿Está seguro de eliminar?")) {
                return false;
            }
            var row = $(this).parents('tr');
            var form = $(this).parents('form');
            var url = form.attr('action');

            $.get(url, form.serialize(), function (result) {
                row.fadeOut();
                $('#alert').show();
                $('#alert').html(result.message)
                setTimeout(function () { $('#alert').fadeOut(); }, 5000);
            }).fail(function () {
                $('#alert').show();
                $('#alert').html("Algo salió mal");
            });
        });
    });
</script>