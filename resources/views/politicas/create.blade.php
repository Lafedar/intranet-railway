<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="modal fade" id="agregar" role="dialog" aling="center">
  <div class="modal-dialog">
    <div class="modal-content">           
      <form action="{{ action('PoliticaController@store_politica') }}" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <input type="hidden" name="id">
              <div class="form-group col-md-12">
                <label for="title"><strong>Titulo:</strong></label>
                <textarea rows="3" type="text" class="form-control" name="titulo" id="titulo" required></textarea>
                <label for="title"><strong>Fecha de Archivo:</strong></label>
                <input type="date" name="fecha"  class="form-control col-md-5" step="1" min="2019-01-01" value="<?php echo date("Y-m-d");?>">
                <br>
                <label for="title"><strong>Politica:</strong></label>
                <input type="file"  name="pdf" accept=".pdf" id="pdf">
              </div> 
              <button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cerrar</button>
              <button type="submit" class="btn btn-info" id="asignar-btn">Agregar</button>
            </div>
          </div>
        </div>
      </form>                
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>//filtro de tamaño de archivos
    $(document).ready(function() {
        
        $.ajax({ //obtengo los mg maximos
            url: "{{ route('obtener_megabytes_maximos') }}",
            type: "GET",
            success: function(response) {
                var megabytesMaximos = response.megabytesMaximos || 0;

                $('#agregar form').submit(function(event) {
                    var archivos = $(this).find('input[type="file"]');
                    archivos.each(function() {
                        var archivo = this.files[0];
                        if (archivo && archivo.size / (1024 * 1024) > megabytesMaximos) {
                            var tipoArchivo = $(this).attr('accept');
                            alert("El tamaño del archivo " + tipoArchivo + " excede el límite permitido de " + megabytesMaximos + " MB");
                            event.preventDefault(); //detener el envío del formulario
                        }
                    });
                });
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener los megabytes máximos:", error);
            }
        });
    });
</script>


