@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<div id="historia-cli-container">
  <div class="row">
    <div class="col-12">
      <h4 class="headertekst" align="center">Historia clinica</h4>
      <hr>
    </div>
  </div>
  <div class="row"> 
    <div class="form-group has-danger col 12">
     <form action="{{ action('MedicoController@store_historia_clinica') }}" method="POST">
      {{csrf_field()}}
      <input type="hidden" name="id" id="id" value="">
      <div class="row">
        <div class="col-1"></div>
        <div class="input-field col-4">Paciente
          <select class="form-control"name="paciente"  id="paciente" required>
            @foreach($personas as $personas)
            <option value="{{$personas->id_p}}">{{$personas->apellido}}&nbsp{{$personas->nombre_p}} </option>
            @endforeach
          </select>
          
        </div>   
        <div class="input-field col-2">Grupo Sanguineo
          <select class="form-control"name="grupo_sang"  id="grupo_sang">
            <option value="0-">0-</option>
            <option value="0+">0+</option>
            <option value="A-">A-</option>
            <option value="A+">A+</option>
            <option value="B-">B-</option>
            <option value="B+">B+</option>
            <option value="AB-">AB- </option>
            <option value="AB+">AB+ </option>
          </select>
        </div>
        <div class="input-field col-4">Educación
          <select class="form-control"name="educacion"  id="educacion">
            @foreach($educacion as $edu)
            <option value="{{$edu->id}}">{{$edu->desc_edu}} </option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="row">
        <div class="input-field col-4"></div>    
        <div class="input-field col-8" >Habitos
         <div class="col-6">
          <label for="tabaco">Tabaco</label>
          <input type="checkbox"value="1" id="tabaco" name="tabaco">
          &nbsp
          <label for="alcohol">Alcohol</label>
          <input type="checkbox"value="1" id="alcohol" name="alcohol">
          &nbsp
          <label for="droga">Drogas</label>
          <input type="checkbox"value="1" id="droga" name="droga">
          &nbsp
          <label for="act_fisica">Act Física</label>
          <input name="chec" type="checkbox" value="1" id="act_fisica" name="act_fisica" onChange="comprobar(this);">
          <div class="input-field col-12" id="descripcion" style="display:none">Describe
            <input type="text" class="form-control" id="desc_act_fisica" name="desc_act_fisica"/>
          </div>
        </div>
      </div>
    </div>
    <br>

    <div class="row">
      <div class="input-field col-4 ">Antecedentes personales
        <textarea class="form-control" rows="3" name="ant_per" id="ant_per" ></textarea>
        <br>
      </div>
      <div class="input-field col-4 ">Antecedentes familiares
        <textarea class="form-control" rows="3" name="ant_fam" id="ant_fam" ></textarea>
      </div>
      <div class="input-field col-4 ">Antecedentes quirurgicos
        <textarea class="form-control" rows="3" name="ant_quir" id="ant_quir" ></textarea>
      </div>
    </div>

    <div class="row">
      <div class="col-3"></div>
      <div class="input-field col-6 ">Observaciones
        <textarea class="form-control" rows="3" name="obs" id="obs" ></textarea>
      </div>
    </div>
    <p></p>
    <div class="row">
      &nbsp
      &nbsp
      <div class="col-md-5"></div>
      <a class="btn btn-secondary " href="{{ URL::previous() }}" id="asignar-btn">Cancelar</a>
&nbsp
&nbsp
      <button type="subitm" class="btn btn-info" id="asignar-btn">Guardar</button>
    </div>
  </div>
</form>
</div>
</div>
<div id="footer-lafedar"></div>

<script type="text/javascript">
 function comprobar(obj)
 {   
  if (obj.checked){

    document.getElementById('descripcion').style.display = "";

  } else{

    document.getElementById('descripcion').style.display = "none";
  }     
}
</script>

