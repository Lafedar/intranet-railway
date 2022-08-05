<div class="modal fade" id="añadir_empresa" role="dialog" align="center">
  <div class="modal-dialog">
   <div class="modal-content">           
    <div class="container">
      <form action="{{action('VisitaController@añadir_empresa', '')}}" method="POST" >
        {{csrf_field()}}
        <div class="modal-body">
         <div class="row">
           <div class="col-md-12">
             <h5 class="headertekst">Agregar Empresa</h5>
             <hr>
             <label for="title">Razón Social:</label>
             <input class="form-control" type="text" name="razon_social" autocomplete="off" id="razon_social" value="{{old('razon_social')}}" required>
             <br>
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
             <button type="submit" class="btn btn-info">Agregar</button>
           </div>
         </div>
       </div>
     </form>                
   </div>
 </div>
</div>
</div>