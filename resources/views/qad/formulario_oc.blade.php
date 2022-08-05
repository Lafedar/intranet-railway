<div class="modal fade" id="formulario_oc" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    
    <form method="get">
      {{csrf_field()}}
      <div class="modal-body">
         <div class="col-md-12">

          <div class="row">
            <div class="col-md-6">
              <label for="title"> Orden</label>
              <input type="text" name="oc" id="orden" autocomplete="off" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="title">Nro artículo</label>
              <input type="text" name="Articulo" id="nro_articulo" autocomplete="off" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label for="title">Fecha desde</label>
              <input type="date" name="fecha1" id="fe_des" class="form-control" step="1"  required>
            </div>
            <div class="col-md-6">
              <label for="title">Fecha hasta</label>
              <input type="date" name="fecha" id="fe_has" class="form-control" step="1" required>
            </div>
          </div>
           <div class="row">
            <div class="col-md-6">
              <label for="title">Nro proveedor</label>
              <input type="text" name="prov" id="nro_proveedor" autocomplete="off" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="title">CUIT proveedor</label>
              <input type="text" name="cuit_prov" id="cuit" autocomplete="off" class="form-control">
            </div>
          </div>
          <p></p>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info" id="descargar">BuScAr</button>
        </div>
      </div>
    </div>
  </form>                
</div>
</div>
</div>
