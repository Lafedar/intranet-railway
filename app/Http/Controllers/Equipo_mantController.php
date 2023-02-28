<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Equipo_mant;
use App\User;
Use Session;
use DB;


class Equipo_mantController extends Controller
{
    public function index(Request $request)
    {
        $equipos_mant = Equipo_mant::
        Relaciones_index($request->get('relaciones'))
        ->paginate(20);

        return view ('equipos_mant.index',   
            array('equipos_mant' => $equipos_mant));
    }

    public function store_equipo_mant(Request $request)
    {        
        $equipo_mant = new Equipo_mant;
        $equipo_mant->id = $request['id_e'];
        $equipo_mant->id_tipo = $request['tipo'];
        $equipo_mant->marca = $request['marca'];
        $equipo_mant->modelo = $request['modelo'];
        $equipo_mant->num_serie = $request['num_serie'];
        $equipo_mant->descripcion = $request['descripcion'];
        $equipo_mant->id_localizacion = $request['localizacion'];
        if($request['uso'])
        {
            $equipo_mant->uso = 1;
        }
        else{$equipo_mant->uso = 0;}
        $equipo_mant->updated_at = null;
        $equipo_mant->created_at = null;
        $equipo_mant->save();

        Session::flash('message','Equipo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('equipos_mant');
    }

    public function select_tipo_equipo()
    {
        return DB::table('tipos_equipos')->get();
    }   

    public function select_area_localizacion()
    {
        return [DB::table('area')->get(), DB::table('localizaciones')->get()];
    }   
 
}