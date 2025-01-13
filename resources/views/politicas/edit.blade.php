<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="modal fade" id="editar" tabindex="-1" role="dialog" aria-labelledby="editarLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('update_politicas')}}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <input type="hidden" name="id" id="id" value="">

              <div class="form-group">
                <label for="titulo"><strong>Título:</strong></label>
                <textarea rows="3" class="form-control" name="titulo" id="titulo" required></textarea>
              </div>

              <div class="form-group">
                <label for="fecha"><strong>Fecha de archivo:</strong></label>
                <input type="date" name="fecha" class="form-control col-md-5" id="fecha" required>
              </div>

              <div class="form-group">
                <label for="pdf"><strong>Política (PDF):</strong></label>
                <input type="file" name="pdf" accept=".pdf" id="pdf">
              </div>

              <div class="elim_pdf" style="display: none;">
                <label><strong>Archivo PDF:</strong> <a href="#" id="pdf_link" target="_blank">Ver archivo</a></label>
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
