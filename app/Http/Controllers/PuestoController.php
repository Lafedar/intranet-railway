<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Equipamiento;
use App\Puesto;
use App\Relacion;
use App\Persona;
use App\Incidente;
use App\User;
use Auth;
use DB;
Use Redirect;
use Illuminate\Support\Facades\Input;
Use Session;
use Illuminate\Routing\Controller;
use Carbon\Carbon;


class PuestoController extends Controller{
   public function puestos(Request $request){
        $puestos = Puesto::Relaciones()            
        ->Puesto($request->get('puesto'))
        ->Usuario($request->get('usuario'))
        ->Area($request->get('area'))
        ->Localizacion($request->get('localizacion'))
        ->orderBy('desc_puesto','asc')
        ->paginate(20);

        return view ('puestos.puestos', array('puestos'=>$puestos, 'puesto'=>$request->get('puesto'),'usuario'=>$request->get('usuario'),
         'area'=>$request->get('area'), 'localizacion'=>$request->get('localizacion')));
    }

    public function select_localizaciones(){
        return DB::table('localizaciones')->get();
    }

    public function select_area(){
        return DB::table('area')->get();
    }

    public function select_persona(){
        return DB::table('personas')->orderBy('personas.nombre_p', 'asc')->get();
    }

    public function select_localizaciones_by_area($areaId){
        return DB::table('localizaciones')
            ->where('id_area', $areaId)
            ->get();
    }

    public function select_area_by_localizacion($localizacionId){
        $localizacion = DB::table('localizaciones')
            ->where('id', $localizacionId)
            ->first();
    
        if ($localizacion) {
            $area = DB::table('area')
                ->where('id_a', $localizacion->id_area)
                ->first();
            return response()->json($area);
        } else {
            return response()->json(['error' => 'Localización no encontrada'], 404);
        }
    }

    public function show_store_puesto(){
        return view('puestos.create');       
    }

    public function store_puesto(Request $request){
        $puesto= new Puesto;
        $puesto->desc_puesto = $request['desc_puesto'];
        $puesto->id_localizacion = $request['localizacion'];
        $puesto->persona = $request['persona'];
        $puesto->obs = $request['obs'];
        $puesto->telefono_ip = $request['telefono_ip'];
        $puesto->save();

        Session::flash('message','Puesto agregado con éxito');
        Session::flash('alert-class', 'alert-success');

        return redirect('puestos');
    }

    public static function getPuesto($id) {
        $puesto = Puesto::leftjoin('localizaciones', 'localizaciones.id', 'puestos.id_localizacion')
        ->leftjoin('area','area.id_a', 'localizaciones.id_area')  
        ->select('puestos.id_puesto as idPuesto', 'puestos.desc_puesto as nombrePuesto', 'puestos.id_localizacion as idLocalizacion',
        'localizaciones.id_area as idArea', 'puestos.persona as idPersona', 'puestos.obs as observaciones')  
        ->find($id);
        return $puesto;
     }

    public function show_update_puesto($id){
        $puesto = Puesto::showPuestoUpdate($id);
        return view('puestos.update', ['puesto' => $puesto]);       
    }

    /*public function edit_puesto($id){   
        $puestos = DB::table('puestos')
        ->leftjoin('personas','puestos.persona','personas.id_p')
        ->leftjoin('localizaciones', 'localizaciones.id', 'puestos.id_localizacion')
        ->leftjoin('area','area.id_a', 'localizaciones.id_area')            
        ->where('puestos.id_puesto',$id)
        ->first();
        $areas = DB::table('area')->get();
        $personas = DB::table('personas')->get();
        return view ('puestos.edit_puesto', array('puesto' => $puestos,'area' => $areas, 'personas' => $personas));
    }*/
    
    public function destroy_puesto($id){
        $activos = 1;
        $relaciones = DB::table('relaciones')
        ->where('relaciones.puesto',$id)
        ->where('relaciones.estado',$activos)
        ->first();

        if($relaciones)
        {
            Session::flash('message','No se puede eliminar este puesto ya que tiene equipos asignados');
            Session::flash('alert-class', 'alert-warning');
        }
        else
        {
            $puesto = Puesto::find($id);
            $puesto ->delete();
            Session::flash('message','Puesto eliminado con éxito');
            Session::flash('alert-class', 'alert-success');
        }
        return redirect('puestos');
    }

    public function update_puesto(Request $request){   
        $puesto = DB::table('puestos')
        ->where('puestos.id_puesto',$request['id_puesto'])
        ->update([
            'desc_puesto' => $request['desc_puesto'],
            'id_localizacion' => $request['localizacion'],
            'persona' => $request['persona'],
            'obs' => $request['obs'],
        ]);      

        Session::flash('message','Puesto modificado con éxito');
        Session::flash('alert-class', 'alert-success');
    
        return redirect('puestos');
    }
}
