@extends('qad.layouts.layout')
@section('content')

@if(Session::has('message'))
<div class="container" id="div.alert">
  <div class="row">
    <div class="col-1"></div>
    <div class="alert {{Session::get('alert-class')}} col-10 text-center" role="alert">
     {{Session::get('message')}}
 </div>
</div>
</div>
@endif
<?php
//$fecha1=date("Y-m-d",strtotime($fecha1."- 8 month"));
//$fecha=date("Y-m-d");

?>
<div class="col-md-12 ml-auto">
    <h1>
        <div class="form-inline pull-right">
          <form  method="GET">
            <div class="form-group">
              <label><h6>OC:</h6></label>
            <input type="text" name="oc" class="form-control col-md-2" id="oc" value="{{$oc}}" >
            &nbsp
             <label><h6>Fecha ini:</h6></label>
            <input type="date" name="fecha1" class="form-control"step="1" min="2019-01-11" value= "{{$fecha1}}"required>
            &nbsp
            <label><h6>Fecha fin:</h6></label>
            <input type="date" name="fecha" class="form-control"step="1" min="2019-01-01" value= "{{$fecha}}"required>
            &nbsp
            <button name ="button1" type="submit" class="btn btn-default"> Buscar</button>
            &nbsp
            
            </form>
      </div>
  </h1>            
</div>

<div class="col-md-12">  
       <tbody>
  @if(count($row) < 2500)
      
      <button type="submit" class="btn btn-default" onclick= "exportTaToEx('tabla', 'OCtabla')" enabled>Descargar</button>

  @elseif($fecha!=0 || $fecha1!=0 )     
     <?php
     if($fecha ="null")
        { $fecha=date("Y-m-d");}
  // dd($fecha1);
     $f=explode('-',$fecha1);
     $g=explode('-',$fecha);
     $h=((abs(($f[0]-$g[0])*12)-$f[1])+$g[1]);
         //dd($f[0]);     
      ?>
         @if ($h>8)

       <button type="submit" class="btn btn-default" onclick= "exportTaToEx('tabla', 'OCtabla')" disabled>Descargar</button>
       <font color="blue" face="Comic Sans MS,arial">
       <th class ="Text-Center"style="color:green;">demasiados regitros para bajar </th>
       </font>
         @else
       <button type="submit" class="btn btn-default" onclick= "exportTaToEx('tabla', 'OCtabla')" enabled>Descargar</button>
         @endif   
    @else   
    
    <button type="submit" class="btn btn-default" onclick= "exportTaToEx('tabla', 'OCtabla')" disabled>Descargar</button> 
    <font color="Red" face="Comic Sans MS,arial">
    <th class ="Text-Center">Ingrese un rango de fecha o seleccione numero de OC para bajar la tabla</th>
    </font>
    @endif 
     
     </tbody>           
 <table class="table table-striped table-bordered " rowspan="2" id="tabla" >
    <thead>
       
        
        <th class="text-center">OC</th>
        <th class="text-center">Fecha_Creacion</th>
        <th class="text-center">LIN</th>
        <th class="text-center">PROV</th>
        <th class="text-center">Cuit_Proveedor</th>
        <th class="text-center">Datos Prov</th>
        <th class="text-center">Requisicion</th>
        <th class="text-center">Solicitado_X</th>
        <th class="text-center">Articulo</th>
        <th class="text-center">Descripcion</th>
        <th class="text-center">Moneda</th>
        <th class="text-center">Cambio</th>
        <th class="text-center">Costo Compra</th>
        <th class="text-center">Cant Ord</th>
        <th class="text-center">Pendiente</th>
        <th class="text-center">UM</th>
        <th class="text-center">Fecha vencimiento</th>
        <th class="text-center">ID OT</th>
        <th class="text-center">ES</th>
        <th class="text-center" align="center">Fecha_Cierre</th>
        <th class="text-center">Opcion</th>
        <th class="text-center">Opcion Lis</th>
        <th class="text-center" align="center"colspan="4">Comenmtario</th>
        
        
    </thead>  
     
     
     @for($i=0; $i< count($row); $i++)
        
        @if($fecha1== "" && $fecha== "")
          
         <tr>
            
            <td align="center" width="80">{{$row[$i][0]}}</td>
           <td align="center" width="80">{!! \Carbon\Carbon::parse($row[$i][1])->format("d-m-Y") !!}</td>
           <td align="center" width="80">{{$row[$i][2]}}</td>
           <td align="center" width="80">{{$row[$i][3]}}</td>
          <?php
             $resultado = substr($row[$i][4], 0,13);
             $row[$i][4]=$resultado;
          ?>
            <td align="center" width="80">{{$row[$i][4]}}</td>
            <td align="center" width="80">{{$row[$i][5]}}</td>
            <td align="center" width="80">{{$row[$i][6]}}</td>
            <td align="center" width="80">{{$row[$i][7]}}</td>
            <td align="center" width="80">{{$row[$i][8]}}</td>
            <td align="center" width="80">{{$row[$i][9]}}</td>
            <td align="center" width="80">{{$row[$i][10]}}</td>
            <?php
            $first=explode(".", $row[$i][11]); 
            $var=$first[0].".".substr($first[1],0,2);
            $row[$i][11]=$var;
            ?>
            <td align="center" width="80">{{$row[$i][11]}}</td>
            <?php
            $first=explode(".", $row[$i][12]); 
            $var=$first[0].".".substr($first[1],0,2);
            $row[$i][12]=$var;
            ?>
            <td align="center" width="80">{{$row[$i][12]}}</td>
            <?php
            $first=explode(".", $row[$i][13]); 
            $var=$first[0].".".substr($first[1],0,2);
            $row[$i][13]=$var;
            ?>
            <td align="center" width="80">{{$row[$i][13]}}</td>
            <?php
            if($row[$i][18]=="x" || $row[$i][18]=="c" ||$row[$i][18]=="X" || $row[$i][18]=="C" ){
                $row[$i][14]=0;     
            }else{$row[$i][14]=$row[$i][13]-$row[$i][14];}
            ?>
            <td align="center" width="80">{{$row[$i][14]}}</td>
            <td align="center" width="80">{{$row[$i][15]}}</td>
            <td align="center" width="110">{!! \Carbon\Carbon::parse($row[$i][16])->format("d-m-Y") !!}</td>
            <td align="center" width="80">{{$row[$i][17]}}</td>
            <td align="center" width="80">{{$row[$i][18]}}</td>
            <td align="center" width="80">{!! \Carbon\Carbon::parse($row[$i][19])->format("d-m-Y") !!}</td>
            <td align="center" width="80">{{$row[$i][20]}}</td>
            <td align="center" width="80">{{$row[$i][21]}}</td>
            <td align="center" width="80">{{$row[$i][22]}}</td>
            
            
        </tr>
                   
       @elseif($row[$i][1]>= $fecha1 && $fecha== "" )


        <tr>
            <td align="center" width="80">{{$row[$i][0]}}</td>
           <td align="center" width="80">{!! \Carbon\Carbon::parse($row[$i][1])->format("d-m-Y") !!}</td>
           <td align="center" width="80">{{$row[$i][2]}}</td>
           <td align="center" width="80">{{$row[$i][3]}}</td>
          <?php
             $resultado = substr($row[$i][4], 0,13);
             $row[$i][4]=$resultado;
          ?>
            <td align="center" width="80">{{$row[$i][4]}}</td>
            <td align="center" width="80">{{$row[$i][5]}}</td>
            <td align="center" width="80">{{$row[$i][6]}}</td>
            <td align="center" width="80">{{$row[$i][7]}}</td>
            <td align="center" width="80">{{$row[$i][8]}}</td>
            <td align="center" width="80">{{$row[$i][9]}}</td>
            <td align="center" width="80">{{$row[$i][10]}}</td>
            <?php
            $first=explode(".", $row[$i][11]); 
            $var=$first[0].".".substr($first[1],0,2);
            $row[$i][11]=$var;
            ?>
            <td align="center" width="80">{{$row[$i][11]}}</td>
            <?php
            $first=explode(".", $row[$i][12]); 
            $var=$first[0].".".substr($first[1],0,2);
            $row[$i][12]=$var;
            ?>
            <td align="center" width="80">{{$row[$i][12]}}</td>
            <?php
            $first=explode(".", $row[$i][13]); 
            $var=$first[0].".".substr($first[1],0,2);
            $row[$i][13]=$var;
            ?>
            <td align="center" width="80">{{$row[$i][13]}}</td>
            <?php
            if($row[$i][18]=="x" || $row[$i][18]=="c" ||$row[$i][18]=="X" || $row[$i][18]=="C" ){
                $row[$i][14]=0;     
            }else{$row[$i][14]=$row[$i][13]-$row[$i][14];}
            ?>
            <td align="center" width="80">{{$row[$i][14]}}</td>
            <td align="center" width="80">{{$row[$i][15]}}</td>
            <td align="center" width="110">{!! \Carbon\Carbon::parse($row[$i][16])->format("d-m-Y") !!}</td>
            <td align="center" width="80">{{$row[$i][17]}}</td>
            <td align="center" width="80">{{$row[$i][18]}}</td>
            <td align="center" width="110">{!! \Carbon\Carbon::parse($row[$i][19])->format("d-m-Y") !!}</td>
            <td align="center" width="80">{{$row[$i][20]}}</td>
            <td align="center" width="80">{{$row[$i][21]}}</td>
            <td align="center" width="80">{{$row[$i][22]}}</td>
            
        </tr>

        @elseif($fecha1== "" && $row[$i][1]<= $fecha)
        <tr>
            <td align="center" width="80">{{$row[$i][0]}}</td>
           <td align="center" width="80">{!! \Carbon\Carbon::parse($row[$i][1])->format("d-m-Y") !!}</td>
           <td align="center" width="80">{{$row[$i][2]}}</td>
           <td align="center" width="80">{{$row[$i][3]}}</td>
          <?php
             $resultado = substr($row[$i][4], 0,13);
             $row[$i][4]=$resultado;
          ?>
            <td align="center" width="80">{{$row[$i][4]}}</td>
            <td align="center" width="80">{{$row[$i][5]}}</td>
            <td align="center" width="80">{{$row[$i][6]}}</td>
            <td align="center" width="80">{{$row[$i][7]}}</td>
            <td align="center" width="80">{{$row[$i][8]}}</td>
            <td align="center" width="80">{{$row[$i][9]}}</td>
            <td align="center" width="80">{{$row[$i][10]}}</td>
            <?php
            $first=explode(".", $row[$i][11]); 
            $var=$first[0].".".substr($first[1],0,2);
            $row[$i][11]=$var;
            ?>
            <td align="center" width="80">{{$row[$i][11]}}</td>
            <?php
            $first=explode(".", $row[$i][12]); 
            $var=$first[0].".".substr($first[1],0,2);
            $row[$i][12]=$var;
            ?>
            <td align="center" width="80">{{$row[$i][12]}}</td>
            <?php
            $first=explode(".", $row[$i][13]); 
            $var=$first[0].".".substr($first[1],0,2);
            $row[$i][13]=$var;
            ?>
            <td align="center" width="80">{{$row[$i][13]}}</td>
            <?php
            if($row[$i][18]=="x" || $row[$i][18]=="c" ||$row[$i][18]=="X" || $row[$i][18]=="C" ){
                $row[$i][14]=0;     
            }else{$row[$i][14]=$row[$i][13]-$row[$i][14];}
            ?>
            <td align="center" width="80">{{$row[$i][14]}}</td>
            <td align="center" width="80">{{$row[$i][15]}}</td>
            <td align="center" width="110">{!! \Carbon\Carbon::parse($row[$i][16])->format("d-m-Y") !!}</td>
            <td align="center" width="80">{{$row[$i][17]}}</td>
            <td align="center" width="80">{{$row[$i][18]}}</td>
            <td align="center" width="110">{!! \Carbon\Carbon::parse($row[$i][19])->format("d-m-Y") !!}</td>
            <td align="center" width="80">{{$row[$i][20]}}</td>
            <td align="center" width="80">{{$row[$i][21]}}</td>
            <td align="center" width="200">{{$row[$i][22]}}</td>
       
        </tr>


        @elseif ($row[$i][1]> $fecha1 && $row[$i][1]< $fecha)
       
        <tr>
           <td align="center" width="80">{{$row[$i][0]}}</td>
           <td align="center" width="80">{!! \Carbon\Carbon::parse($row[$i][1])->format("d-m-Y") !!}</td>
           <td align="center" width="80">{{$row[$i][2]}}</td>
           <td align="center" width="80">{{$row[$i][3]}}</td>
          <?php
             $resultado = substr($row[$i][4], 0,13);
             $row[$i][4]=$resultado;
          ?>
            <td align="center" width="80">{{$row[$i][4]}}</td>
            <td align="center" width="80">{{$row[$i][5]}}</td>
            <td align="center" width="80">{{$row[$i][6]}}</td>
            <td align="center" width="80">{{$row[$i][7]}}</td>
            <td align="center" width="80">{{$row[$i][8]}}</td>
            <td align="center" width="80">{{$row[$i][9]}}</td>
            <td align="center" width="80">{{$row[$i][10]}}</td>
            <?php
            $first=explode(".", $row[$i][11]); 
            $var=$first[0].".".substr($first[1],0,2);
            $row[$i][11]=$var;
            ?>
            <td align="center" width="80">{{$row[$i][11]}}</td>
            <?php
            $first=explode(".", $row[$i][12]); 
            $var=$first[0].".".substr($first[1],0,2);
            $row[$i][12]=$var;
            ?>
            <td align="center" width="80">{{$row[$i][12]}}</td>
            <?php
            $first=explode(".", $row[$i][13]); 
            $var=$first[0].".".substr($first[1],0,2);
            $row[$i][13]=$var;
            ?>
            <td align="center" width="80">{{$row[$i][13]}}</td>
            <?php
            if($row[$i][18]=="x" || $row[$i][18]=="c" ||$row[$i][18]=="X" || $row[$i][18]=="C" ){
                $row[$i][14]=0;     
            }else{$row[$i][14]=$row[$i][13]-$row[$i][14];}
            ?>
            <td align="center" width="80">{{$row[$i][14]}}</td>
            <td align="center" width="80">{{$row[$i][15]}}</td>
            <td align="center" width="110">{!! \Carbon\Carbon::parse($row[$i][16])->format("d-m-Y") !!}</td>
            <td align="center" width="80">{{$row[$i][17]}}</td>
            <td align="center" width="80">{{$row[$i][18]}}</td>
            <td align="center" width="110">{!! \Carbon\Carbon::parse($row[$i][19])->format("d-m-Y") !!}</td>
            <td align="center" width="80">{{$row[$i][20]}}</td>
            <td align="center" width="80">{{$row[$i][21]}}</td>
            <td align="center" width="200">{{$row[$i][22]}}</td>
            
        </tr>
       @endif

       <?php
       $j=$i;
       ?>
        @endfor 
    
</table>


{{-- {{ $row->appends($_GET)->links() }} --}}
</div>

@stop