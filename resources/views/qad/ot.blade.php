@extends('qad.layouts.layout')
@section('content')

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
              <label><h6>OT:</h6></label>
              <input type="text" name="ot" class="form-control col-md-1" id="ot" value="{{$ot}}" >
              &nbsp
              <label><h6>Cód Producto:</h6></label>
              <input type="text" name="cod_prod" class="form-control col-md-1" id="cod_prod" value="{{$cod_prod}}" >
              &nbsp
              <label><h6>Producto:</h6></label>
              <input type="text" name="nom_prod" class="form-control col-md-2" id="nom_prod" value="{{$nom_prod}}" >
              &nbsp
              <label><h6>Lote:</h6></label>
              <input type="text" name="lote" class="form-control col-md-1" id="lote" value="{{$lote}}" >
              &nbsp
              <label><h6>Estado:</h6></label>
              <input type="text" name="estado" class="form-control col-md-1" id="estado" value="{{$estado}}" >
              &nbsp
              <button type="submit" class="btn btn-default"> Buscar</button>
          </form>
      </div>
  </h1>            
</div>

<div class="col-md-12">             
  <table class="table table-striped table-bordered ">
    <thead>
        <th class="text-center">OT</th>
        <th class="text-center">Cód Producto</th>
        <th class="text-center">Producto</th>
        <th class="text-center">Lote</th>
        <th class="text-center">Estado</th>
        <th class="text-center">Fecha orden</th>
        <th class="text-center">Fecha lib</th>
    </thead>  
    <tbody>
        @for($i=0; $i< count($row); $i++)
        <tr>
            <td align="center" width="80">{{$row[$i][0]}}</td>
            <td align="center">{{$row[$i][1]}}</td>
            <td align="center">{{$row[$i][2]}}</td>
            <td align="center" width="80">{{$row[$i][3]}}</td>
            <td align="center" width="80">{{$row[$i][4]}}</td>
            <td align="center">{{$row[$i][5]}}</td>
            <td align="center">{{$row[$i][6]}}</td>       
        </tr>
        @endfor                   
    </tbody>
</table>

{{-- {{ $row->appends($_GET)->links() }} --}}

</div>

@stop