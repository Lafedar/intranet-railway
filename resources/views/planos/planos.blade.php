@extends('planos.layouts.layout')
@section('content')

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

<div class="col-md-12 ml-auto">
    <h1>
     <div class="form-inline pull-right">
      <form  method="GET">
        <div class="form-group">
          <label><h6>ID:</h6></label>
          <input type="text" name="id_plano" class="form-control col-md-1" id="id_plano" value="{{$id_plano}}">
          &nbsp
          <label><h6>Título:</h6></label>
          <input type="text" name="titulo_plano" class="form-control col-md-3" id="titulo_plano" value="{{$titulo_plano}}">
          &nbsp
          <label><h6>Observación:</h6></label>
          <input type="text" name="obs_plano" class="form-control" id="obs_plano" value="{{$obs_plano}}">
          &nbsp
          <label><h6>Fecha:</h6></label>
          <input type="date" name="fecha_plano" class="form-control" step="1" value="{{$fecha_plano}}"> 
        &nbsp
        <button type="submit" class="btn btn-default"> Buscar</button>
    </form>
</div>
</h1>            
</div>

<div class="col-md-12">             
  <table class="table table-striped table-bordered ">
    <thead>
        <th class="text-center">ID</th>
        <th class="text-center">Título</th>
        <th class="text-center">Observación</th>
        <th class="text-center">Fecha</th>
        <th class="text-center">Acciones</th>
    </thead>  
    <tbody>
        @if(count($planos))
        @foreach($planos as $plano)
        <tr>
            <td width="60">{{sprintf('%05d',$plano->id)}}</td>
            <td >{{$plano->titulo}}</td>
            <td>{{$plano->obs}}</td>
            <td width="107">{!! \Carbon\Carbon::parse($plano->fecha)->format("d-m-Y") !!}</td>
            <td  width="222">
                <div>
                    @if($plano->pdf != null)
                    <a href="{{ Storage::url($plano->pdf) }}" class="btn btn-primary btn-sm" title="Descargar PDF original" data-position="top" data-delay="50" data-tooltip="Descargar PDF original" download>PDF Original</a>
                    @else
                    <a class="btn btn-secondary btn-sm" data-position="top" data-delay="50"download >PDF Original</a>
                    @endif

                    @if($plano->pdf_firmado != null)
                    <a href="{{ Storage::url($plano->pdf_firmado) }}" class="btn btn-primary btn-sm" title="Descargar PDF firmado" data-position="top" data-delay="50" data-tooltip="Descargar PDF firmado" download>PDF Firmado</a>
                    @else
                    <a class="btn btn-secondary btn-sm" data-position="top" data-delay="50"download >PDF Firmado</a>
                    @endif
                </div>
                <h6></h6>
                <div align="center">
                    @if($plano->dwg != null)
                    <a href="{{ Storage::url($plano->dwg) }}" class="btn btn-primary btn-sm" title="Descargar DWG" data-position="top" data-delay="50" data-tooltip="Descargar DWG" download>DWG</a>
                    @else
                    <a class="btn btn-secondary btn-sm"  data-position="top" data-delay="50"download >DWG</a>
                    @endif

                    @if($plano->ctb != null)
                    <a href="{{ Storage::url($plano->ctb) }}" class="btn btn-primary btn-sm" title="Descargar CTB" data-position="top" data-delay="50" data-tooltip="Descargar CTB" download>CTB</a>
                    @else
                    <a class="btn btn-secondary btn-sm"  data-position="top" data-delay="50" download >CTB</a>
                    @endif
                    
                    <button class="btn btn-info btn-sm" data-id="{{$plano->id}}" data-titulo="{{$plano->titulo}}" data-fecha="{{$plano->fecha}}" data-obs="{{$plano->obs}}" data-pdf="{{$plano->pdf}}" data-pdf_firmado="{{$plano->pdf_firmado}}" data-dwg="{{$plano->dwg}}" data-ctb="{{$plano->ctb}}" data-toggle="modal" data-target="#editar"> Editar</button>
                    @can('eliminar-planos')
                    <a href="{{url('destroy_plano', $plano->id)}}" class="btn btn-danger btn-sm" title="Borrar" onclick="return confirm ('Está seguro que desea eliminar el plano?')"data-position="top" data-delay="50" data-tooltip="Borrar">X</a>
                    @endcan
                </div>
            </td>            
        </tr>
        @endforeach  
        @endif                    
    </tbody>
</table>

@include('planos.edit')


{{ $planos->appends($_GET)->links() }}

</div>

<script> 
    $("document").ready(function(){
        setTimeout(function(){
           $("div.alert").fadeOut();
    }, 5000 );

    });
</script>

<script>
  $('#editar').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 
    var id = button.data('id')
    var titulo = button.data('titulo')
    var fecha = button.data('fecha') 
    var obs = button.data('obs')
    var pdf = button.data('pdf')
    var pdf_firmado = button.data('pdf_firmado')
    var dwg = button.data('dwg')
    var ctb = button.data('ctb') 
    var modal = $(this)

    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #titulo').val(titulo);
    modal.find('.modal-body #fecha').val(fecha);
    modal.find('.modal-body #obs').val(obs);

    console.log(pdf_firmado.length);
    if(pdf.length == 0){
        $("div.elim_pdf").hide()
    }
    else{
     $("div.elim_pdf").show()   
    }
    if(pdf_firmado.length == 0){
        $("div.elim_pdf_firmado").hide()
    }
    else{
     $("div.elim_pdf_firmado").show()   
    }
    if(dwg.length == 0){
        $("div.elim_dwg").hide()
    }
    else{
        $("div.elim_dwg").show()   
    }
    if(ctb.length == 0){
        $("div.elim_ctb").hide()
    }
    else{
     $("div.elim_ctb").show()   
    }
})
</script>

@stop