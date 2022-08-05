@extends('equipamiento.layouts.layout')


@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <h4 class="headertekst" align="center">Editar puesto</h4>
      <hr>
    </div>
  </div>
        
  <div class="row"> 
    <div class="col-md-3 field-label-responsive"></div>
    <div class="col-md-6">
      <div class="form-group has-danger">
         <form action="{{ action('PuestoController@update_puesto') }}" method="POST">
             {{csrf_field()}}

            <input type="hidden" name="id" value="{{{ isset($puesto->id_puesto) ? $puesto->id_puesto : ''}}}">          

          <div class="input-field col s12 ">Descripcion:
            <br>
            <input type="text" name="desc_puesto" class="form-control" id="desc_puesto" value="{{{ isset($puesto->desc_puesto) ? $puesto->desc_puesto : ''}}}" required>
          </div>

        <div class="input-field col s12 ">Area:
              <select class="form-control" name="area"  id="area" value="{{{ isset($area->nombre_a) ? $puesto->area : ''}}}" required>
                @foreach($area as $area)
                  @if($area->id_a == $puesto->area)
                    <option value="{{$area->id_a}}" selected="">{{$area->nombre_a}} </option>
                  @else
                    <option value="{{$area->id_a}}" >{{$area->nombre_a}} </option>
                  @endif
                @endforeach
              </select>
            </div> 


          <div class="input-field col s12 ">Persona:
              <select class="form-control" name="persona"  id="persona" value="{{{ isset($personas->nombre_p) ? $puesto->persona : ''}}}" required>
                @foreach($personas as $personas)
                  @if($personas->id_p == $puesto->persona)
                    <option value="{{$personas->id_p}}" selected="">{{$personas->apellido .' '. $personas->nombre_p}} </option>
                  @else
                    <option value="{{$personas->id_p}}">{{$personas->apellido .' '. $personas->nombre_p}} </option>
                  @endif
                @endforeach
              </select>
            </div>          
           <div class="input-field col s12 ">Observación:
          <br>
          <input type="text" name="obs" class="form-control" id="obs" value="{{{ isset($puesto->obs) ? $puesto->obs : ''}}}">
        </div>
            <p></p>
           <div class="col-md-6">
            <input type="checkbox" value="1" checked id="telefono_ip" name="telefono_ip">
            <label for="telefono_ip">Telefono</label>
            </div>
             <p></p>
          <div class="row">
            <div class="col-md-3 field-label-responsive"></div>
            <div class="col-md-1"></div>
            <a class="btn btn-secondary " href="{{ URL::previous() }}">Volver</a>
            &nbsp
            <button type="subitm" class="btn btn-info">Guardar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
  <div id="footer-lafedar"></div>

@stop





