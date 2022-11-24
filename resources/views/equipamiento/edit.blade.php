<div class="modal fade" id="editar_equipamiento" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <form action="{{route('equipamiento.update', 'equipamiento')}}" method="POST">
      {{ method_field('PUT')}} {{csrf_field()}}
      <div class="modal-body">
       <div class="row">
         <div class="col-md-12">

          <div class="row">
            <div class="col-md-6">
              <label for="title">Id equipamiento:</label>
              <input type="text" name="id_e" class="form-control" id="id_e" autocomplete="off" value="{{old('id_e')}}" min="6" required>
            </div>
            <div class="col-md-6">
              <label for="title">Tipo de equipamiento:</label>
              <select class="form-control" name="tipo_equipamiento"  id="tipo_equipamiento_editar" required></select>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label for="title">Marca:</label>
              <input type="text" name="marca" class="form-control" autocomplete="off" id="marca" value="{{old('marca')}}">
            </div>
            <div class="col">
              <label for="title">Modelo:</label>
              <input type="text" name="modelo" class="form-control" autocomplete="off" id="modelo" value="{{old('modelo')}}">
            </div>
          </div>
          
          <div class="row"> 
            <div class="col-md-6">
              <label for="title">Número de serie:</label>
              <input type="text" name="num_serie" class="form-control" autocomplete="off" id="num_serie" value="{{old('num_serie')}}"> 
            </div>
            <div class="col-md-3">
              <label for="title">Disco (GB):</label>
              <input type="number" name="disco" class="form-control"  autocomplete="off" id="disco" value="{{old('disco')}}">
            </div>
            <div class="col-md-3">
              <label for="title">RAM (GB):</label>
              <input type="number" name="memoria" class="form-control" autocomplete="off" id="memoria" value="{{old('memoria')}}">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label for="title">Procesador:</label>
              <input type="text" name="procesador" class="form-control" id="procesador"  autocomplete="off" value="{{old('procesador')}}">
            </div>
            <div class="col-md-3">
              <label for="title">Toner:</label>
              <input type="text" name="toner" class="form-control" id="toner" autocomplete="off" value="{{old('toner')}}">
            </div>
            <div class="col-md-3">
              <label for="title">DR:</label>
              <input type="text" name="unidad_imagen" class="form-control" id="unidad_imagen" autocomplete="off" value="{{old('unidad_imagen')}}">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label for="title">Pulgadas:</label>
              <input type="number" name="pulgadas" class="form-control"  autocomplete="off" id="pulgadas" value="{{old('pulgadas')}}">
            </div>
            <div class="col-md-6">
              <label for="title">Subred:</label>
              <select class="form-control" name="ips" id="ips_editar"></select>
            </div>
          </div>

          <div class="row">
            <div class ="col-md-4">
              <label for="title">ID de red:</label>
              <select class="form-control" name="id_red" id="id_red_editar"></select>
            </div>
            <div class="col-md-3">
              <label for="title">ID de host:</label>
              <input type="number" name="ip" class="form-control" id="ip" autocomplete="off" max="254" min="2" value="{{old('ip')}}">
            </div> 
            <div class="col-md-5">
              <label for="title">Orden de compra:</label>
              <input type="text" name="oc" class="form-control" id="oc" autocomplete="off" value="{{old('oc')}}">
            </div>
          </div>
          

          <div class="row">
            <div class="col">
              <label for="title">Observación:</label>
              <input type="text" name="obs" class="form-control" id="obs" autocomplete="off"  value="{{old('obs')}}">
            </div>
          </div>

          <p></p>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Editar</button>
        </div>
      </div>
    </div>
  </form>                
</div>
</div>
</div>

<script>

</script>