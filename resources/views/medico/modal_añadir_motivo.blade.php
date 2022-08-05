 <!-- Modal Añadir_motivo-->
  <div class="modal fade" id="añadir_motivo" role="dialog" align="center">
    <div class="modal-dialog">
     <div class="modal-content">           
      <div class="container">
        <form action="{{action('MedicoController@añadir_motivo', '')}}" method="POST" >
          {{csrf_field()}}
          <div class="modal-body">
           <div class="row">
             <div class="col-md-12">
               <h4 class="headertekst">Agregar motivo</h4>
               <hr>
               <input class="form-control" type="text" name="motivo" required>
               <br>
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
               <button type="submit" class="btn btn-info" >Añadir</button>
             </div>
           </div>
         </div>
       </form>                
     </div>
   </div>
 </div>
</div>
