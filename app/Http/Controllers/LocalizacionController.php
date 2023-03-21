<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Localizacion;
use App\User;
Use Session;
use DB;


class LocalizacionController extends Controller
{
    public function index(Request $request)
    {
        $localizaciones = Localizacion::Index($request->get('localizaciones'))
        ->paginate(20);

        return view ('localizaciones.index',   
            array('localizaciones' => $localizaciones));
    }

    public function store_localizacion(Request $request)
    {        
        //consulta en bd si existe el id
        $aux = DB::table('localizaciones')->where('localizaciones.id', $request['id'])->first(); 

        //mensaje de id existente
        if($aux){
            Session::flash('message','ID ingresado ya se encuentra asignado');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back()->withInput();
        }
        
        $localizacion = new Localizacion;
        $localizacion->id = $request['id'];
        $localizacion->nombre = $request['nombre'];
 
        $localizacion->save();

        Session::flash('message','Localizacion agregada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('localizaciones');
    }

    public function update_localizacion(Request $request)
    {
        $localizacion = DB::table('localizaciones')
        ->where('localizaciones.id',$request['id'])
        ->update([
            'nombre' => $request['nombre']
        ]);      
        Session::flash('message','Localizacion modificada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('localizaciones');
    }
    
    public function show_update_localizacion($id_a)
    {
        $localizacion = DB::table('localizaciones')
        ->select('localizaciones.id as id', 'localizaciones.nombre as nombre')
        ->where('localizaciones.id', $id_a)
        ->first();

        return view('localizaciones.update', ['localizacion' => $localizacion]);       
    }
    
    public function select_area()
    {
        return DB::table('area')->get();
    }   
}
