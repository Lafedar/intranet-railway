<!-- Modal Editar-->
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="modal fade" id="editar" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <form action="{{ route('update_planos')}}" method="POST" enctype="multipart/form-data">
     {{csrf_field()}}
     <div class="modal-body">
       <div class="row">
         <div class="col-md-12">
          <input type="hidden" name="id" id="id" value="">
          <div class="form-group col-md-12">
            @can('editar-planos')
            <label for="title"><strong>Titulo:</strong></label>
            <textarea rows="3" type="text" class="form-control" name="titulo" id="titulo" required></textarea>
            <label for="title"><strong>Fecha de plano:</strong></label>
            <input type="date" name="fecha"  class="form-control col-md-5" step="1" min="2019-01-01" value="<?php echo date("Y-m-d");?>">
            <label for="title"><strong>Observación:</strong></label>
            <textarea rows="3" type="text" class="form-control" name="obs" id="obs"></textarea>
            <label for="title"><strong>PDF Original:</strong></label>
            <input type="file"  name="pdf" accept=".pdf" id="pdf">
            <div class="elim_pdf">
              <label for="eliminar_pdf">Eliminar</label>
              <input type="checkbox"value="1" id="eliminar_pdf" name="eliminar_pdf">
            </div>
            @endcan
            <label for="title"><strong>PDF Firmado:</strong></label>
            <input type="file"  name="pdf_firmado" accept=".pdf" id="pdf">
            @can('editar-planos')
            <div class="elim_pdf_firmado">
             <label for="eliminar_pdf_firmado">Eliminar</label>
             <input type="checkbox"value="1" id="eliminar_pdf_firmado" name="eliminar_pdf_firmado">
           </div>
           <label for="title" class="col-md-10"><strong>DWG:</strong></label>
           <input type="file"  name="dwg" accept=".dwg" id="dwg">
           <div class="elim_dwg">
            <label for="eliminar_dwg">Eliminar</label>
            <input type="checkbox"value="1" id="eliminar_dwg" name="eliminar_dwg">
          </div>
          <label for="title" class="col-md-10"><strong>CTB:</strong></label>
          <input type="file"  name="ctb" accept=".ctb"  id="ctb">
          <div class="elim_ctb">
            <label for="eliminar_ctb">Eliminar</label>
            <input type="checkbox"value="1" id="eliminar_ctb" name="eliminar_ctb">
          </div>
          @endcan
        </div> 
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cancelar</button>
        <button type="submit" class="btn btn-info" id="asignar-btn">Guardar</button>
      </div>
    </div>
  </div>
</form>                
</div>
</div>
</div>