@extends('almuerzo.layouts.layout')
@section('seccion')
<head>
  <link rel="stylesheet" type="text/css" href="/css/almuerzo.css">
</head>

 <form action= "{{action('AlmuerzoController@cargar')}}" method="POST">
  @csrf


  <div class="row md-12">
     <label class="col-sm-2"></label>
    <div class="col md-12">
      <label class="col-sm-8 menu"><i>Menu de la Semana</i></label>
    </div>
  </div>


<div class="row mb-3">
  <label class="col-sm-2"></label>
  <div class="col-sm-2">
    <p class="texto"><h5>desde</h5></p>
    <input type="date" name="fecha_desde" class="form-control">
  </div>
   <div class="col-sm-2">
    <p><h5>hasta</h5></p>
    <input type="date" name="fecha_hasta" class="form-control">
  </div>
</div>



 <center><h4> Tradicional (1)</h4></center>
 <div class="row mb-3">
    <label for="tlun" class="col-sm-2 texto1"><i><u>Lunes:</u></i></label>
    <div class="col-sm-10">
      <input type="text" class="form-control mb-8 " name="tlun" id="tlun">
    </div>
  </div>

  <div class="row mb-3">
    <label for="tmar" class="col-sm-2 col-form-label texto1"><i><u>Martes:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="tmar" id="tmar"  class="form-control mb-8"/>
    </div>
</div>
    <div class="row mb-3">
    <label for="tmier" class="col-sm-2 col-form-label texto1"><i><u>Miercoles:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="tmier" id="tmier"  class="form-control"/>
    </div>
</div>
     <div class="row mb-3">
    <label for="tjue" class="col-sm-2 col-form-label texto1"><i><u>Jueves:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="tjue" id="tjue"  class="form-control"/>
    </div>
</div>
     <div class="row mb-3">
    <label for="tvie" class="col-sm-2 col-form-label texto1"><i><u>Viernes:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="tvie" id="tvie" class="form-control" />
    </div>
   </div>


  <center><h4> Bajas Calorias (2)</h4></center>

   
    <div class="row mb-3">
    <label for="bclun" class="col-sm-2 col-form-label texto1"><i><u>Lunes:</u></i></label>
    <div class="col-sm-10">
      <input type="text" class="form-control mb-8" name="bclun" id="bclun">
    </div>
  </div>

    <div class="row mb-3">
    <label for="bcmar" class="col-sm-2 col-form-label texto1"><i><u>Martes:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="bcmar" id="bdmar"  class="form-control mb-8"/>
    </div>
</div>
    <div class="row mb-3">
    <label for="bcmier" class="col-sm-2 col-form-label texto1"><i><u>Miercole:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="bcmier" id="bcmier"  class="form-control"/>
    </div>
</div>
     <div class="row mb-3">
    <label for="bcjue" class="col-sm-2 col-form-label texto1"><i><u>Jueves:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="bcjue" id="bcjue"  class="form-control"/>
    </div>
</div>
     <div class="row mb-3">
    <label for="bcvie" class="col-sm-2 col-form-label texto1"><i><u>Viernes:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="bcvie" id="bcvie" class="form-control" />
    </div>
   </div>
 
 <center><h4> Menu de verano (3)</h4></center>
   
    <div class="row mb-3">
    <label for="mlun" class="col-sm-2 col-form-label texto1"><i><u>Lunes:</u></i></label>
    <div class="col-sm-10">
      <input type="text" class="form-control mb-8" name="mlun" id="mlun">
    </div>
  </div>

    <div class="row mb-3">
    <label for="mmar" class="col-sm-2 col-form-label texto1"><i><u>Martes:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="mmar" id="mmar"  class="form-control mb-8"/>
    </div>
    </div>
    <div class="row mb-3">
    <label for="mmie" class="col-sm-2 col-form-label texto1"><i><u>Miercole:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="mmier" id="mmier"  class="form-control"/>
    </div>
     </div>
      <div class="row mb-3">
    <label for="mjue" class="col-sm-2 col-form-label texto1"><i><u>Jueves:</u></i></label>
      <div class="col-sm-10">
      <input type="text" name="mjue" id="mjue"  class="form-control"/>
    </div>
</div>
     <div class="row mb-3">
    <label for="mvie" class="col-sm-2 col-form-label texto1"><i><u>Viernes:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="mvie" id="mvie" class="form-control" />
    </div>
   </div>

   <center><h4> yogurt y frutas (4)</h4></center>
   
   <center><h4> Ensalada (5)</h4></center>
   
    <div class="row mb-3">
    <label for="elun" class="col-sm-2 col-form-label texto1"><i><u>Lunes:</u></i></label>
    <div class="col-sm-10">
      <input type="text" class="form-control mb-8" name="elun" id="elun">
    </div>
  </div>

    <div class="row mb-3">
    <label for="emar" class="col-sm-2 col-form-label texto1"><i><u>Martes:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="emar" id="emar"  class="form-control mb-8"/>
    </div>
</div>
    <div class="row mb-3">
    <label for="emier" class="col-sm-2 col-form-label texto1"><i><u>Miercole:</i></u></label>
    <div class="col-sm-10">
      <input type="text" name="emier" id="emier"  class="form-control"/>
    </div>
</div>
     <div class="row mb-3">
    <label for="ejue" class="col-sm-2 col-form-label texto1"><i><u>Jueves:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="ejue" id="ejue"  class="form-control"/>
    </div>
</div>
     <div class="row mb-3">
    <label for="evie" class="col-sm-2 col-form-label texto1"><i><u>Viernes:</u></i></label>
    <div class="col-sm-10">
      <input type="text" name="evie" id="evie" class="form-control" />
    </div>
   </div>

   <center><h4> Colacion (6)</h4></center>
   <div class="row mb-3">
    <label for="colacion" class="col-sm-2 col-form-label texto1"></label>
    <div class="col-sm-10">
      <input type="text" class="form-control mb-8" placeholder="Ensalada de frutas con barra de cereales." value="Ensalada de frutas con barra de cereales." name="colacion" id="clolacion">
    </div>
  </div>


  <center><h4> Merienda (7)</h4></center>
   <div class="row mb-3">
    <label for="merienda" class="col-sm-2 col-form-label texto1"></label>
    <div class="col-sm-10">
      <input type="text" class="form-control mb-8" name="merienda" id="merienda" placeholder ="Factura/medias lunas con chocolatada, jugo o café con leche." value="Factura/medias lunas con chocolatada, jugo o café con leche.">
    </div>
  </div>
  
  
    <div class="container">
      <div class="row">
        <div class="col-8">
  <button class="btn btn-primary btn-lg btn-block botones1" type="submit"> Agregar</button>
        </div>
        <div class="col-4">
  <a class="btn btn-success btn-lg btn-block botones1" href="{{route('almuerzo.inicio')}}">Inicio</a>
        </div>
      </div>
    </div>


 

 </form>


 




@endsection