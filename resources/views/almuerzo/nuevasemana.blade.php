
  <!DOCTYPE html>
  <html>
  <head>
    <title></title>
  </head>
  <body>
  
  

  <body>
    <form action="{{action('AlmuerzoController@carga_inicial')}}" method="POST"  id="cargar">
    @csrf
          
          @foreach($personas as $personas)
          
        
         <?php  
           $nom=$personas->apellido." ".$personas->nombre_p;
           $salida=str_replace(" ","", $nom);
           $dni=$personas->dni;
           
         ?>
<tr>

          <td><input type="text" name="id_e[]" value="{{$nom}}" hidden="true"></td>
         <!-- <td><input type="text" name="lunes[]" class="form-control" value="1" ></td>
          <td><input type="text" name="martes[]" class="form-control" value="1" ></td>
          <td><input type="text" name="miercoles[]" class="form-control" value="1" ></td>
          <td><input type="text" name="jueves[]" class="form-control" value="1" ></td>
          <td><input type="text" name="viernes[]" class="form-control" value="1"  ></td>
          <td><input type="text" name="id_sem" value="{{$comidam->id}}" ></td>-->
         <td> <input type="text" name="fecha_hasta" value="{{$comidam->fecha_hasta}}" hidden="tru"></td>
         <td><input type="text" name="fecha_desde" value="{{$comidam->fecha_desde}}" hidden="true"></td>
         <td> <input type="text" name="dni[]" value="{{$dni}}" hidden="true"></td>

          @endforeach

          
          
    </tr>      
    <!--<button type="submit" class="btn btn-info">Aceptar</button>-->
      </form>
      
 </body>
  </html>
  
  

       
 
 

  

  <script type="text/javascript">
    document.getElementById("cargar").submit();
  </script>

  
   