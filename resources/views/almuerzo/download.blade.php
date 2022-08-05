<div class="modal fade download-modal-sm" id="download" role="dialog" align="center">
   <div class="modal-dialog modal-sm">
     <div class="modal-content"> 
	   <body>
	   
	   	<style type="text/css">
	   	
	   	.redondeado {
  		border-radius: 5px;
 		}
 		.confondo {
 		background-color: #blue;
 		}
 		</style>

		 <div class="modal-body">
			  <div class="row">
			    <div class="col-md-12">
				  <form action= "{{action('AlmuerzoController@export')}}" method="post">
        			{{csrf_field()}}
        			<div class="form-group">	
        				<div class="form-group" >
        				<p><h5><i>Fecha Inicio de semana</i></h5></p>
        				<i><input type="date" name="fecha_desde" class= "redondeado"></i>
        				</div>
        				<!--
        				<div class="form-group">
        				<p>Fecha fin de semana</p>
        				<input type="date" name="fecha_hasta">
        				</div>
						-->
        				<div class="form-group">
            			<button class="btn btn-primary btn-block" type="submit">exportar</button>
            			</div>

    	        	</div>
    	          </form>
    	    	</div>
    	      </div>
            </div>
	     </div>
	   </body>
	 </div>
  </div>
</div>