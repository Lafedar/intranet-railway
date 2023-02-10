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

    public function select_tipo_equipo()
    {
        return DB::table('tipos_equipos')->get();
    }   

    public function select_area_localizacion()
    {
        return [DB::table('area')->get(), DB::table('localizaciones')->get()];
    }   
 
}