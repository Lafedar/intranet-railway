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
        $equipo_mant->id = $request['id'];
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

    public function show_update_equipo_mant($id)
    {
        $equipo_mant = DB::table('equipos_mant')
        ->leftjoin('localizaciones', 'localizaciones.id', 'equipos_mant.id_localizacion')
        ->leftjoin('area', 'area.id_a', 'localizaciones.id_area')
        ->leftjoin('tipos_equipos', 'tipos_equipos.id', 'equipos_mant.id_tipo')
        ->select('equipos_mant.id as id', 'equipos_mant.marca as marca', 'equipos_mant.modelo as modelo', 'equipos_mant.descripcion as descripcion', 
        'equipos_mant.uso as uso', 'localizaciones.nombre as localizacion', 'area.nombre_a as area', 'equipos_mant.uso as uso', 
        'tipos_equipos.nombre as nombre_tipo', 'equipos_mant.num_serie as num_serie')
        ->where('equipos_mant.id', $id)
        ->first();

        return view('equipos_mant.update', ['equipo_mant' => $equipo_mant]);       
    }

    public function update_equipo_mant(Request $request)
    {
        if($request['id'] != $request['id_vieja'])
        {
            $aux = DB::table('equipos_mant')
            ->select('equipos_mant.id as id')
            ->get();

            foreach ($aux as $equipo) 
            {
                if($request['id'] == $equipo->id)
                {
                    Session::flash('message','El ID ingresado ya se encuentra asignada');
                    Session::flash('alert-class', 'alert-warning');
                    return redirect ('equipos_mant');
                }
            }
        }
        $equipo_mant = DB::table('equipos_mant')
        ->where('equipos_mant.id',$request['id_vieja'])
        ->update([
            'id' => $request['id'],
            'id_tipo' => $request['tipo_equipo_mant_editar'],
            'marca' => $request['marca'],
            'modelo' => $request['modelo'],
            'num_serie' => $request['num_serie'],
            'descripcion' => $request['descripcion'],
            'id_localizacion' => $request['localizacion_editar'],
            'uso' => $request['uso']
        ]);      
        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('equipos_mant');
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