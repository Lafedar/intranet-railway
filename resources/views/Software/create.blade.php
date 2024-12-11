<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="modal fade" id="agregar_software" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <form action="{{action('SoftwareController@soft_store')}}" method="post" autocomplete="off">
      {{csrf_field()}}
      <div class="modal-body">
       <div class="row">
         <div class="col-md-12">
          
          <div class="row">
            <div class="col-md-6">
              <label for="title">Software:</label>
              <input type="text" name="Software" class="form-control" id="Software"  value="{{old('Software')}}" >
            </div>
            <div class="col-md-6">
              <label for="title">Version:</label>
              <input type="text" name="Version" class="form-control" id="Version" autocomplete="off" value="{{old('version')}}" >
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label for="title">Licencia:</label>
              <input type="text" name="Licencia" class="form-control" id="Licencia" autocomplete="off" value="{{old('Licencia')}}">
            </div>
            <div class="col-md-1"></div>

            <div class="col-md-4">
              <label for="title">Tipo de licencia:</label>
              <input type="text" name="t_Licencia" class="form-control" id="t_Licencia" autocomplete="off" value="{{old('t_Licencia')}}" >
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label for="title">Fecha de Licencia:</label>
              <input type="date" name="fecha_inst"  id="fecha_inst" class="form-control"-tep="1" value="{{old('fecha_inst')}}">
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label for="title">Observaciones:</label>
              <input type="text" name="Obs" class="form-control" id="Obs" value="{{old('Obs')}}" >
            </div>
          </div>
          <p></p>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cerrar</button>
          <button type="submit" class="btn btn-info" id="asignar-btn">Agregar</button>
        </div>
      </div>
    </div>
  </form>                
</div>
</div>
</div>