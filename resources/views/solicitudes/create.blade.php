<!-- Modal Agregar-->
<div class="col-md-12">
  <input type="hidden" name="id">
  <div class="form-group col-md-12">
    <label for="title"><strong>Titulo:</strong></label>
    <input type="text" name="titulo" class="form-control"  autocomplete="off" id="titulo" minlength="10" maxlength="50" required>

    <label for="title"><strong>Descripcion:</strong></label>
    <textarea rows="3" type="text" class="form-control" name="descripcion" id="descripcion"  minlength="10" maxlength="500" required></textarea>
                
    <div class="row" >
      <div class="col-6" id="div_tipo_solicitud">
        <label for="title"><strong>Tipo de solicitud:</strong></label>
        <select class="form-control" name="tipo_solicitud" id="tipo_solicitud" required></select>
      </div>
      <div class="col-6" id="div_equipo">
        <label for="title"><strong>Equipo:</strong></label>&nbsp&nbsp&nbsp&nbsp<a role="button" class="fa-solid fa-magnifying-glass" 
        href="#" title="Mostrar Equipos" data-toggle="modal" data-target="#mostrar" onclick="fnOpenModalShowEquipos()"></a>
        <br>
        <select class="form-control select2" name="equipo" id="equipo" style="width: 100%;" required></select>
      </div>
      <div class="col-12" id="div_descripcion">
        <label for="title"><strong>Descripcion de equipo:</strong></label>
        <textarea rows="3" type="text" class="form-control" name="descripcion_equipo" id="descripcion_equipo"  minlength="10" maxlength="500" required></textarea>
      </div>
      <div class="col-6">
        <label for="title"><strong>Area:</strong></label>
        <select class="form-control" name="area" id="area" required></select>
      </div>
      <div class="col-6" style="display:none;" id="div_localizacion">
        <label for="title"><strong>Localizacion:</strong></label>
        <select class="form-control" name="localizacion" id="localizacion" required></select>
      </div>
      <div class="col-6" style="display:none;" id="div_falla">
        <label for="title"><strong>Fallas:</strong></label>
        <br>
        <select class="form-control" name="falla" id="falla" required></select>
      </div>
    </div>
    <input type="hidden" name="solicitante" value="{{ Auth::id() }}">
  </div> 
</div>
      
  <div class="modal fade" id="show3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog estilo" role="document">
      <div class="modal-content">
        <form id="myForm" method="POST" enctype="multipart/form-data">
          {{csrf_field()}}
          <div id="modalshow3" class="modal-body">
            <!-- Datos -->
          </div>
          <div id="modalfooter3" class="modal-footer">
            <!-- Footer -->
          </div>
        </form>
      </div>
    </div>
  </div>

<script>
  var ruta = '{{ route('mostrar_equipos_mant') }}';
  var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');
  //modal store
  function fnOpenModalShowEquipos() {
    var myModal = new bootstrap.Modal(document.getElementById('show3'));
    $.ajax({
      url: window.location.protocol + '//' + window.location.host + "/show_mostrar_equipos_mant/",
      type: 'GET',
      success: function(data) {
        // Borrar contenido anterior
        $("#modalshow3").empty();
        // Establecer el contenido del modal
        $("#modalshow3").html(data);

        // Borrar contenido anterior
        $("#modalfooter3").empty();

        // Agregar el botón "Cerrar" al footer
        $("#modalfooter3").append(closeButton);

        // Agregar listener al botón "Cerrar" del modal secundario
        closeButton.click(function(event) {
          event.stopPropagation();
          myModal.hide();
        });

        // Mostrar el modal
        myModal.show();

        var modalDialog = myModal._element.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm');
        modalDialog.classList.add('modal-lg');
      },
      error: function(xhr, status, error) {
    console.log(xhr.responseText);
  }
    });

    $('#show3').on('show.bs.modal', function (event){
      $.get('select_equipos/',function(data){
        var htmlSelectEquipo = '<option value="">Seleccione </option>'
      });
    });
  }

</script>