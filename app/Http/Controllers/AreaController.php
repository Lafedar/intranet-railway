<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Area;
use App\User;
Use Session;
use DB;


class AreaController extends Controller
{
    public function index(Request $request)
    {
        $areas = Area::Index($request->get('areas'))
        ->paginate(20);

        return view ('areas.index',   
            array('areas' => $areas));
    }

    public function store_area(Request $request)
    {        
        $area = new Area;
        $area->id_a = $request['id_a'];
        $area->nombre_a = $request['nombre'];
 
        $area->save();

        Session::flash('message','Area agregada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('areas');
    }

    public function update_area(Request $request)
    {
        $area = DB::table('area')
        ->where('area.id_a',$request['id_a'])
        ->update([
            'nombre_a' => $request['nombre_a']
        ]);      
        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('equipos_mant');
    }
    
    public function show_update_area($id)
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
}
