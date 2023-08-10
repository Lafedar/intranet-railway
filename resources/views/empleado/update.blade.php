<div class="form-group col-md-12">
  <table id="test" class="table table-striped table-bordered table-condensed" role="grid" cellspacing="0" cellpadding="2" border="10">
    <thead>
      <th class="text-center">Area</th>
      <th class="text-center">Turno</th>
      <th class="text-center">Eliminar</th>
    </thead>        
    <tbody id="tablaJefesAreas">
      @if(count($idsJAs))
        @foreach($idsJAs as $idsJA) 
          <tr align="center">
            <td>{{$idsJA->nombreArea}}</td>
            <td>{{$idsJA->nombreTurno}}</td>
            <td width="175">
              <button type="button" class="btn btn-danger btn-sm btn-borrar" data-tooltip="Borrar" onclick='fnEliminarJefeXArea({{$idsJA->id_ja}}, {{$idsJA->idJefe}})'> X</button>
            </td>
          </tr>
        @endforeach  
      @endif  
    </tbody>
  </table>
    <div style="display: flex; align-items: center;">
      <p style="margin-right: 10px;"><strong>Agregar:</strong></p>
      <select class="form-control col-md-8" name="nuevoPermiso" id="nuevoPermiso" style="margin-right: 10px;"></select>
      <button type="button" class="btn btn-info" id="saveButton" onclick='fnAgregarJefeXArea()'>Guardar</button>
    </div>
</div>
      

