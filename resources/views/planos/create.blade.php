<!-- Modal Agregar-->
<div class="modal fade" id="agregar" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <form action="{{ action('PlanoController@store_planos') }}" method="POST" enctype="multipart/form-data">
      {{csrf_field()}}
      <div class="modal-body">
       <div class="row">
         <div class="col-md-12">
          <input type="hidden" name="id">
          <div class="form-group col-md-12">
            <label for="title"><strong>Titulo:</strong></label>
            <textarea rows="3" type="text" class="form-control" name="titulo" id="titulo" required></textarea>
            <label for="title"><strong>Fecha de plano:</strong></label>
            <input type="date" name="fecha"  class="form-control col-md-5" step="1" min="2019-01-01" value="<?php echo date("Y-m-d");?>">
            <label for="title"><strong>Observación:</strong></label>
            <textarea rows="3" type="text" class="form-control" name="obs" id="obs"></textarea>
            <label for="title"><strong>PDF Original:</strong></label>
            <input type="file"  name="pdf" accept=".pdf" id="pdf">
            <label for="title"><strong>PDF Firmado:</strong></label>
            <input type="file"  name="pdf_firmado" accept=".pdf" id="pdf">
            <label for="title" class="col-md-10"><strong>DWG:</strong></label>
            <input type="file"  name="dwg" accept=".dwg" id="dwg">
            <label for="title" class="col-md-10"><strong>CTB:</strong></label>
            <input type="file"  name="ctb" accept=".ctb"  id="ctb">
          </div> 
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Agregar</button>
        </div>
      </div>
    </div>
  </form>                
</div>
</div>
</div>


