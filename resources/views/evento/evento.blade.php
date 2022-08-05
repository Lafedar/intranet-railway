<div class="modal fade" id="ver" role="dialog" align="center">
  <div class="modal-dialog">
    <div class="modal-content">
      <div align="right" class="col-md-12 ">           
        <a href="" data-dismiss="modal" class="btn btn-danger" style='text-decoration:none;color:black'>X</a>
      </div> 
  
      
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12" >
            <div class="form-group">



              <strong><h2><output class="headertekst" type="text"  name="Enca" id="Enca" ></h2></strong>
                <hr>
              </div>
              <div class="form-group">
                <strong>Titulo: </strong><output type="text"  name="titulo" id="titulo" >
                </div>
                
                <div class="form-group">
                <strong>Solicita: </strong><output type="text"  name="solicitado" id="solicitado">
                </div>

                <div class="form-group">
                  <strong>Descripcion: </strong><output type="text"  name="descripcion" id="descripcion">
                  </div>

                  <div class="form-group">
                    <strong>Ubicacion: </strong><output type="text"  name="ubicacion" id="ubicacion">
                    </div>
                    <div class="form-group">
                      <strong>fecha: </strong><output type="text"  name="fecha" id="fecha">
                      </div>
                      <div class="form-group">
                        <strong>hora: </strong><output type="time"  name="hora" id="hora">  
                      </div>
                  
                     
                      
                       @foreach ($data['calendar'] as $weekdata)
                          
          <!-- ciclo de dia por semana -->
                            @foreach  ($weekdata['datos'] as $dayweek)

                                 
              <!-- evento -->
                                         @foreach  ($dayweek['evento'] as $evento) 
                     <form method = "POST" action = "{{route('event.update', $evento)}}">
                     {{ method_field('PATCH')}} {{csrf_field()}}
            
 
                      <input type="hidden" name="id" id= "id" value="{{$evento->id}}">
                      <input type="hidden" name="activo" id="activo" value="{{$evento->activo}}">
             
                           @endforeach
                         @endforeach
                     @endforeach
                      <BUTTON type="submit" class="btn btn-info">Cancelar Evento</BUTTON>
                   </form>
                      </div> 
                                  
                    </div>
                  </div>
                </div>
                 
                 


