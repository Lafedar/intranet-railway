<!-- Modal Agregar-->
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="col-md-12">
  <input type="hidden" name="id">
  <div class="form-group col-md-12">
    <label for="title"><strong>Titulo:</strong></label>
    <textarea rows="3" type="text" class="form-control" name="tituloCreate" id="tituloCreate" required></textarea>

    <label for="title"><strong>Tipo:</strong></label>
    <select class="form-control" name="tipo_instructivo" id="tipo_instructivo" required></select>
    
    <br>
    <label for="title"><strong>Instructivo:</strong></label>
    <input type="file" name="archivo" id="archivo" required>
    <small id="archivoError" class="text-danger"></small>
  </div> 
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>//filtro de tamaño de archivos
    $(document).ready(function() {
        $.ajax({//obtener los megabytes máximos
            url: "{{ route('obtener_megabytes_maximos') }}",
            type: "GET",
            success: function(response) {
                var megabytesMaximos = response.megabytesMaximos || 0;
              
                $('#myForm').submit(function(event) {
                    //obtener el archivo seleccionado por el usuario
                    var archivo = $('#archivo')[0].files[0];

                    if (archivo && archivo.size / (1024 * 1024) > megabytesMaximos) {
                        alert("El tamaño del archivo excede el límite permitido de " + megabytesMaximos + " MB");
                        event.preventDefault(); //detener el envío del formulario
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener los megabytes máximos:", error);
            }
        });
    });
</script>


