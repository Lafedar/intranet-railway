<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Almuerzo;
use App\Menus;
use App\Empleado;
use DateTime;
use Carbon\Carbon;
use App\Exports\AlmuerzoExport;
Use Illuminate\Support\Facades\Session;


class AlmuerzoController extends Controller
{
    
	public function inicio(Request $request){
		
		$almuerzo=Almuerzo::orderBy('id_sem', 'desc')
							->orderby('id_e', 'asc')
		->Nombre($request->get('nombre'))
		->Idsem($request->get('idsem'))
		->paginate(20);
		$comidam =DB::table('menuses')->orderBy('id','desc')->get();
		return view('almuerzo.index',compact('comidam') ,array('almuerzo'=> $almuerzo, 'nombre' => $request->get('nombre'), 'idsem' =>$request->get('idsem')));

	}

	public function nuevo(){
		
		return view('almuerzo.nuevo');



	}

	public function cargar(Request $request){

		//return $request->all();

		$nuevoalmuerzo = new Menus;
		$nuevoalmuerzo->tlun = $request->tlun;
		$nuevoalmuerzo->tmar = $request->tmar;
		$nuevoalmuerzo->tmier = $request->tmier;
		$nuevoalmuerzo->tjue = $request->tjue;
		$nuevoalmuerzo->tvie = $request->tvie;
		
		$nuevoalmuerzo->bclun = $request->bclun;
		$nuevoalmuerzo->bcmar = $request->bcmar;
		$nuevoalmuerzo->bcmier = $request->bcmier;
		$nuevoalmuerzo->bcjue = $request->bcjue;
		$nuevoalmuerzo->bcvie = $request->bcvie;
		
		$nuevoalmuerzo->mlun = $request->mlun;
		$nuevoalmuerzo->mmar = $request->mmar;
		$nuevoalmuerzo->mmier = $request->mmier;
		$nuevoalmuerzo->mjue = $request->mjue;
		$nuevoalmuerzo->mvie = $request->mvie;

		$nuevoalmuerzo->elun = $request->elun;
		$nuevoalmuerzo->emar = $request->emar;
		$nuevoalmuerzo->emier = $request->emier;
		$nuevoalmuerzo->ejue = $request->ejue;
		$nuevoalmuerzo->evie = $request->evie;

		
		$nuevoalmuerzo->merienda = $request->merienda;
		
		$nuevoalmuerzo->colacion = $request->colacion;

		$nuevoalmuerzo->fecha_desde = $request->fecha_desde;
		$nuevoalmuerzo->fecha_hasta = $request->fecha_hasta;
		
		$nuevoalmuerzo->activo = 1;

		$nuevoalmuerzo->save();

		//sleep(5);
		return redirect('nuev_sem');

	}


	public function Menu(Request $request){

		$comidam=Menus::orderBy('id', 'asc')->paginate(4);
		
		return view('almuerzo.Menu', compact('comidam'));

	}

	public function nuevasemana(){

		$comidam = Menus::where('activo',1)->get()->last();
		$personas = Empleado::where('activo',1)->orderBy('apellido','asc')->get();
		$comida = Almuerzo::orderBy('id_sem','desc')->get();
		return view('almuerzo.nuevasemana', compact('comida','comidam','personas'));

	}

	
	public function elegir(Request $request){
				
		$dni = $request->only("dni");
		$admin=0;
		$pers= DB::table('personas')->where('dni',$dni)->value('Ad_Alm');
	
			if( $pers == null ){
			//$personas=DB::table('personas')->where('dni', $dni)->get();
				Session::flash('error','El ususario no existe');
            
            	return redirect()->back()->withInput();}
		
				if($pers == 1 ){
					$personas = Empleado::where('activo',1)->orderBy('apellido','asc')->get();
					$almuerzo=Almuerzo::orderBy('id_sem', 'desc')->paginate(15);
					$comi=Almuerzo::orderBy('id','asc')->get()->last();
					$comidam = DB::table('menuses')->get();
					return view('almuerzo.index', compact('almuerzo','personas','comidam','comi'));

				}
		
					if($pers == 2){
						$personas = DB::table('personas')
						->where('activo',1)
						->where('dni',$dni)
						->get();
						$comidam = Menus::orderBy('id','asc')->get()->last();
						$comida = Almuerzo::where('activo',1)->get()->last();
						$comi=Almuerzo::orderBy('id','asc')->get()->last();
					}
			
				return view('almuerzo.seleccionp', compact('comida','comidam','personas','comi'))->with('admin',$admin);
		}
			

		public function selec (){

		$comidam = Menus::orderBy('id','asc')->get()->last();
		$comida = Almuerzo::where('activo',1)->get()->last();
		$personas = Empleado::where('activo',1)->orderBy('apellido','asc')->get();
		$comi=Almuerzo::orderBy('id','asc')->get()->last();
		$admin=1;
		return view('almuerzo.seleccionp', compact('comida','comidam','personas','comi'))->with('admin', $admin);

		}

	
    
    public function semana_cer(){

    	$almuerzo=Almuerzo::orderBy('id','asc')->get();

    	return view('almuerzo.cerrar_sem',compact('almuerzo'));
    }

    public function cerrar_semana(Request $request){

		$date_now = date('Y-m-d');

    	$idsem=DB::table('almuerzos')->where('almuerzos.fecha_desde','>=',$date_now)->value('id_sem');
    	$almu=DB::table('almuerzos')
    				->where('almuerzos.id_sem',$idsem)
                	->update([
    	            'activo'=>$request->get('activo'),
    ]);

               return redirect('almuerzo');
    }



    public function actualizar (Request $request){
	 		
			
    		$idp=$request->only('id_p');
			$date_now = date('Y-m-d');
			$comida = DB::table('almuerzos')
        		->where('almuerzos.id_e',$request['id_e'])
        		->where('almuerzos.id',$request['id'])
        		->update([
        	'id_e'=>$request->get('id_e'),
        	'lunes'=>$request->get('lunes'),
        	'martes'=>$request->get('martes'),
        	'miercoles'=>$request->get('miercoles'),
        	'jueves'=>$request->get('jueves'),
        	'viernes'=>$request->get('viernes'),
        	'dni'=>$request->get('dni'),
        	  ]);
	 		
        sleep(3);  
        Session::flash('message','Menu fue cargado');
        
		return redirect('clog');
	}

	public function carga_inicial(Request $request){

			$comida = Almuerzo::orderBy('id_sem','desc')->value('id_sem');
			$id_sem=$comida+1;
				
			for ($i=0; $i < count($request->input('id_e')); $i++) { 

						$comida =new Almuerzo;
						$comida->id_e=$request->id_e[$i];
						$comida->id_sem=$id_sem;
						$comida->fecha_desde=$request->fecha_desde;
						$comida->fecha_hasta=$request->fecha_hasta;
						$comida->dni=$request->dni[$i];
						$comida->activo=1;
						$comida->save();
					}

						return redirect('/almuerzo');

			
	}


	public function guardar (Request $request){
		
		for ($i=0; $i < count($request->input('id_e')); $i++) { 
		
       	$comida = new Almuerzo;

		$comida->id_e=$request->id_e[$i];
		
		$comida->lunes=$request->lunes[$i];
		$comida->martes=$request->martes[$i];
		$comida->miercoles=$request->miercoles[$i];
		$comida->jueves=$request->jueves[$i];
		$comida->viernes=$request->viernes[$i];
		$comida->id_sem=$request->id_sem;
		$comida->fecha_desde=$request->fecha_desde;
		$comida->fecha_hasta=$request->fecha_hasta;
		$comida->dni=$request->dni[$i];
		$comida->activo=1;
		$comida->save();
		}

		return redirect('/almuerzo');
    }

	public function mostrarsemana ($id){

		$id_se = Almuerzo::orderBy('id_sem','desc')->value('id_sem');
		$id_se_ac=$id_se-1;
		$dni = Empleado::where('activo',1)->value('dni');
		
		$semana=DB::table('menuses')->where('id',$id_se_ac)->get();
		
		//dd($id);
		$seleccion=DB::table('almuerzos')->where('id_sem',$id_se_ac)
										 ->where('id_e', $id)	
										 ->get();
		$lun=DB::table('almuerzos')->where('id_sem',$id_se_ac)
										 ->where('id_e', $id)
										 ->value('lunes');
		$mar=DB::table('almuerzos')->where('id_sem',$id_se_ac)
										 ->where('id_e', $id)
										 ->value('martes');
		$mier=DB::table('almuerzos')->where('id_sem',$id_se_ac)
										 ->where('id_e', $id)
										 ->value('miercoles');
		$jue=DB::table('almuerzos')->where('id_sem',$id_se_ac)
										 ->where('id_e', $id)
										 ->value('jueves');
		$vie=DB::table('almuerzos')->where('id_sem',$id_se_ac)
										 ->where('id_e', $id)
										 ->value('viernes');
		$nom=DB::table('almuerzos')->where('id_sem',$id_se_ac)
										 ->where('id_e', $id)
										 ->value('id_e');
		$dni=DB::table('almuerzos')->where('id_sem', $id_se_ac)
								  ->where('id_e', $id)
								  ->value('dni');
								  

		if($lun == 1){ $lun = "tlun";}
		if($lun == 2){ $lun = "bcun";}
		if($lun == 3){ $lun = "elun";}
		if($lun == 4){ $lun = "y_y_f";}
		if($lun == 5){ $lun = "mlun";}
		if($lun == 6){ $lun = "merienda";}
		if($lun == 7){ $lun = "colacion";}

		if($mar == 1){ $mar = "tlun";}
		if($mar == 2){ $mar = "bcun";}
		if($mar == 3){ $mar = "elun";}
		if($mar == 4){ $mar = "y_y_f";}
		if($mar == 5){ $mar = "mlun";}
		if($mar == 6){ $mar = "merienda";}
		if($mar == 7){ $mar = "colacion";}

		if($mier == 1){ $mier = "tlun";}
		if($mier == 2){ $mier = "bcun";}
		if($mier == 3){ $mier = "elun";}
		if($mier == 4){ $mier = "y_y_f";}
		if($mier == 5){ $mier = "mlun";}
		if($mier == 6){ $mier = "merienda";}
		if($mier == 7){ $mier = "colacion";}

		if($jue == 1){ $jue = "tlun";}
		if($jue == 2){ $jue = "bcun";}
		if($jue == 3){ $jue = "elun";}
		if($jue == 4){ $jue = "y_y_f";}
		if($jue == 5){ $jue = "mlun";}
		if($jue == 6){ $jue = "merienda";}
		if($jue == 7){ $jue = "colacion";}

		if($vie == 1){ $vie = "tlun";}
		if($vie == 2){ $vie = "bcun";}
		if($vie == 3){ $vie = "elun";}
		if($vie == 4){ $vie = "y_y_f";}
		if($vie == 5){ $vie = "mlun";}
		if($vie == 6){ $vie = "merienda";}
		if($vie == 7){ $vie = "colacion";}

		$lun=DB::table('menuses')->where('id',$id_se_ac)->value($lun);
		$mar=DB::table('menuses')->where('id',$id_se_ac)->value($mar);
		$mier=DB::table('menuses')->where('id',$id_se_ac)->value($mier);
		$jue=DB::table('menuses')->where('id',$id_se_ac)->value($jue);
		$vie=DB::table('menuses')->where('id',$id_se_ac)->value($vie);


		
		

		$arreglo = ['almuerzo'=> [$nom,$lun,$mar,$mier,$jue,$vie,$dni]];
		//dd($arreglo);
		return view('almuerzo.semanaactual',compact('arreglo'));
    	//return view ('almuerzo.semanaactual', compact('seleccion','personas','semana'));
	} 

    public function download(){

    	return view('almuerzo.download');
    }


    public function export(Request $request){

    		$dia=$request->input("fecha_desde");
    		$day = strtotime($dia);
    		$day = date("d", $day);
    	    		
		return (new AlmuerzoExport)->forDay($day)->download('almuerzo.xlsx');

		}

	public function byAlmuerzo($id){

			return Almuerzo::where('id_e', $id)->get();
		}

	public function byComida($id){

		return Menus::where('id',$id)->get();

	}

	public function aloguin(){

		$comidam = Menus::orderBy('id','asc')->get()->last();
		$personas = Empleado::where('activo',1)->orderBy('apellido','asc')->get();
		
		return view ('almuerzo.loginalmuerzo', compact('comidam', 'personas'));
	}
			
}
