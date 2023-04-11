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

    public function show_store_area()
    {
        return view('areas.create');       
    }

    public function store_area(Request $request)
    {        
        //consulta en bd si existe el id
        $aux = DB::table('area')->where('area.id_a', $request['id_a'])->first(); 

        //mensaje de id existente
        if($aux){
            Session::flash('message','ID ingresado ya se encuentra asignado');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back()->withInput();
        }
        
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
        Session::flash('message','Area modificada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('areas');
    }
    
    public function show_update_area($id_a)
    {
        $area = DB::table('area')
        ->select('area.id_a as id_a', 'area.nombre_a as nombre_a')
        ->where('area.id_a', $id_a)
        ->first();

        return view('areas.update', ['area' => $area]);       
    }
}
