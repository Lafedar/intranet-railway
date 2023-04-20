
<div class="form-group col-md-12">
  <div class="row">
    <div class="col">
      <label for="title"><strong>ID:</strong></label>
      <input type="text" name="id" class="form-control" id="id_e" autocomplete="off" value="{{old('id')}}" maxlength="5" required>
    </div>
    <div class="col">
      <label for="title"><strong>Tipo:</strong></label>
      <select class="form-control" name="tipo" id="tipo" required></select>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <label for="title"><strong>Marca:</strong></label>
      <input type="text" name="marca" class="form-control" autocomplete="off" id="marca" value="{{old('marca')}}" required>
    </div>
    <div class="col">
      <label for="title"><strong>Modelo:</strong></label>
      <input type="text" name="modelo" class="form-control" autocomplete="off" id="modelo" value="{{old('modelo')}}">
    </div>
  </div>
  <label for="title"><strong>Numero de Serie:</strong></label>
  <input type="text" name="num_serie" class="form-control" autocomplete="off" id="num_serie" value="{{old('num_serie')}}"> 
  <label for="title"><strong>Descripcion:</strong></label>
  <textarea rows="3" type="text" class="form-control" name="descripcion" id="descripcion"></textarea>
  <div class="row">
    <div class="col-6">
      <label for="title"><strong>Area:</strong></label>
      <select class="form-control" name="area" id="area" required></select>
    </div>
    <div class="col-6" style="display:none;" id="div_localizacion">
      <label for="title"><strong>Localizacion:</strong></label>
      <select class="form-control" name="localizacion" id="localizacion"></select>
    </div>
  </div>
  <div>
    <br>
    <label for="title"><strong>Uso:</strong></label>
    <input type="checkbox" name="uso" class="from-control" autocomplete="off" id="uso">
  </div>
</div> 
        