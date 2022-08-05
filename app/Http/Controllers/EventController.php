<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Event;
use DB;
Use Redirect;
use Illuminate\Support\Facades\Input;
Use Session;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use App\Persona;

class EventController extends Controller
{
    public function form(){
 	 return view("evento/form");
	
	}

	public function create(Request $request){

		 // guardar evento
      Event::insert([
        'titulo'       => $request->input("titulo"),
        'descripcion'  => $request->input("descripcion"),
        'fecha'        => $request->input("fecha"),
        'hora'         => $request->input("hora"),
        'ubicacion'    => $request ->input("ubicacion"), 
        'solicitado'   => $request ->input("solicitado") ,
        'activo'       => $request->input("activo")
            ]);
       
        Session::flash('message','Evento Creado con éxito');
        Session::flash('alert-class', 'alert-success');

           return redirect('/Evento');

     //return back()->with('success', 'Enviado exitosamente!');

    }

    public function quien_reserva(){
        

        $solicita = DB::table('persona')->get();

        return view ('evento.form', array('perona' => $solicita));
    }

    public function details($id){

      $evento = Event::find($id);

      return view("evento/evento",[
        "evento" => $evento
      ]);

    }

    public function updates (Request $request)
    {
    	$evento = DB::table('evento')->where('evento.id',$request['id'])
      ->update([
            
            'activo' => 0

  
          ]); 
          
             

        Session::flash('message','Evento cancelado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('/Evento');
    }
//===========================Calendario==========================

    public function index(){

      $month = date("Y-m");
      $data = $this->calendar_month($month);
      $mes = $data['month'];
      // obtener mes en espanol
      $mespanish = $this->spanish_month($mes);
      $mes = $data['month'];

      return view('evento.index',[
        'data' => $data,
        'mes' => $mes,
        'mespanish' => $mespanish
      ]);

  }


  public function index_month($month){

      $data = $this->calendar_month($month);
      $mes = $data['month'];
      // obtener mes en espanol
      $mespanish = $this->spanish_month($mes);
      $mes = $data['month'];

      return view("evento.index",[
        'data' => $data,
        'mes' => $mes,
        'mespanish' => $mespanish
      ]);

    }

    public static function calendar_month($month){
      //$mes = date("Y-m");
      $mes = $month;

      //sacar el ultimo de dia del mes
      $daylast =  date("Y-m-d", strtotime("last day of ".$mes));
      //sacar el dia de dia del mes
      $fecha      =  date("Y-m-d", strtotime("first day of ".$mes));
      $daysmonth  =  date("d", strtotime($fecha));
      $montmonth  =  date("m", strtotime($fecha));
      $yearmonth  =  date("Y", strtotime($fecha));
      // sacar el lunes de la primera semana
      //el lines o el primer dia
      $nuevaFecha = mktime(0,0,0,$montmonth,$daysmonth,$yearmonth);
   
       
      $diaDeLaSemana = date("w", $nuevaFecha);
      

      $nuevaFecha = $nuevaFecha - ($diaDeLaSemana*24*3600); //Restar los segundos totales de los dias transcurridos de la semana
      
      $dateini = date ("Y-m-d",$nuevaFecha);
      
      //$dateini = date("Y-m-d",strtotime($dateini."+ 1 day"));
      // numero de primer semana del mes
      $semana1 = date("W",strtotime($fecha));
      // numero de ultima semana del mes
      $semana2 = date("W",strtotime($daylast));
        
      //dd($semana1, $semana2);
      // semana todal del mes
      // en caso si es diciembre


      if (date("m", strtotime($mes))==12) {
          $semana = 5;
          }
      //semanas del mes si es enero

      elseif (date("m", strtotime($mes))==1 ) {
          $semana = 6;
        
      }
      else {
        $semana = ($semana2-$semana1)+1;
        

      }
      // semana todal del mes
      $datafecha = $dateini;

      $calendario = array();
      
      $iweek = 0;
      
      while ($iweek < $semana):
          $iweek++;
          //echo "Semana $iweek <br>";
          
          $weekdata = [];
          for ($iday=0; $iday < 7 ; $iday++){
            
            // code...
            $datafecha = date("Y-m-d",strtotime($datafecha."+ 1 day"));
             
            $datanew['mes'] = date("M", strtotime($datafecha));
           
            $datanew['dia'] = date("d", strtotime($datafecha));
 
            $datanew['fecha'] = 
            //AGREGAR CONSULTAS EVENTO
            //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			   
            $datanew['evento'] = Event::where("fecha",$datafecha)->get();

            array_push($weekdata,$datanew);
          }
          $dataweek['semana'] = $iweek;
          $dataweek['datos'] = $weekdata;
           
          //$datafecha['horario'] = $datahorario;
          array_push($calendario,$dataweek);
      endwhile;
      $nextmonth = date("Y-M",strtotime($mes."+ 1 month"));
      $lastmonth = date("Y-M",strtotime($mes."- 1 month"));
      $month = date("M",strtotime($mes));
      $yearmonth = date("Y",strtotime($mes));
      //$month = date("M",strtotime("2019-03"));
      $data = array(
        'next' => $nextmonth,
        'month'=> $month,
        'year' => $yearmonth,
        'last' => $lastmonth,
        'calendar' => $calendario,
      );
      
      return $data;
    }

    public static function spanish_month($month)
    {

        $mes = $month;
        if ($month=="Jan") {
          $mes = "Enero";
        }
        elseif ($month=="Feb")  {
          $mes = "Febrero";
        }
        elseif ($month=="Mar")  {
          $mes = "Marzo";
        }
        elseif ($month=="Apr") {
          $mes = "Abril";
        }
        elseif ($month=="May") {
          $mes = "Mayo";
        }
        elseif ($month=="Jun") {
          $mes = "Junio";
        }
        elseif ($month=="Jul") {
          $mes = "Julio";
        }
        elseif ($month=="Aug") {
          $mes = "Agosto";
        }
        elseif ($month=="Sep") {
          $mes = "Septiembre";
        }
        elseif ($month=="Oct") {
          $mes = "Octubre";
        }
        elseif ($month=="Oct") {
          $mes = "December";
        }
        elseif ($month=="Dec") {
          $mes = "Diciembre";
        }
        else {
          $mes = $month;
        }
        return $mes;
    }

}
