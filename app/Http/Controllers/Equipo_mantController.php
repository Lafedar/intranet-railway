<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Equipo_mant;
use App\User;
Use Session;
use DB;


class Equipo_mantController extends Controller{
    public function index(Request $request){
        $equipos_mant = Equipo_mant::ID($request->get('id_e'))
        ->Relaciones_index($request->get('tipo'), $request->get('id_area'), $request->get('id_localizacion'))
        ->orderBy('id_e')
        ->paginate(50);

        $tiposEquipos = DB::table('tipos_equipos')->orderBy('nombre','asc')->get();
    
        $areas = DB::table('area')->orderBy('nombre_a','asc')->get();
        $localizaciones = DB::table('localizaciones')->orderBy('nombre','asc')->get();

        return view ('equipos_mant.index',[
            'id_e' => $request->get('id_e'),
            'tipo' => $request->get('tipo'),
            'id_localizacion' => $request->get('id_localizacion'),
            'id_area' => $request->get('id_area'),
            'tiposEquipos' => $tiposEquipos,
            'areas' => $areas,
            'localizaciones' => $localizaciones,
            'equipos_mant' => $equipos_mant]);
    }

    public function show_store_equipo_mant(){
        return view('equipos_mant.create');       
    }

    public function store_equipo_mant(Request $request)
{        
    // Verificar si el ID ya existe
    if (Equipo_mant::where('id', $request['id'])->exists()) {
        // Si el ID ya existe, mostrar mensaje de error
        Session::flash('message', 'El ID ingresado ya existe. No se pudo agregar el equipo.');
        Session::flash('alert-class', 'alert-danger');
        return redirect('equipos_mant');
    }

    // Si el ID no existe, proceder a guardar el equipo mantenido
    $equipo_mant = new Equipo_mant;
    $equipo_mant->id = $request['id'];
    $equipo_mant->id_tipo = $request['tipo_e'];
    $equipo_mant->marca = $request['marca'];
    $equipo_mant->modelo = $request['modelo'];
    $equipo_mant->num_serie = $request['num_serie'];
    $equipo_mant->descripcion = $request['descripcion'];
    $equipo_mant->id_area = $request['area'];
    $equipo_mant->id_localizacion = $request['localizacion'];
    $equipo_mant->uso = $request['uso'] ? 1 : 0;
    $equipo_mant->save();

    // Mensaje de éxito
    Session::flash('message', 'Equipo agregado con éxito');
    Session::flash('alert-class', 'alert-success');
    return redirect('equipos_mant');
}

    public function show_update_equipo_mant($id){
        $equipoMantModel = new Equipo_mant();
        $equipo_mant = $equipoMantModel->getEquipoMantShowUpdate($id);

        return view('equipos_mant.update', ['equipo_mant' => $equipo_mant]);       
    }

    public function update_equipo_mant(Request $request){
        if($request['id'] != $request['id_vieja']){
            $aux = equipos_mant::getEquipmentToUpdate($request['id']);

            foreach ($aux as $equipo) {
                if($request['id'] == $equipo->id){
                    Session::flash('message','El ID ingresado ya se encuentra asignada');
                    Session::flash('alert-class', 'alert-warning');
                    return redirect ('equipos_mant');
                }
            }
        }
        $uso = 0;
        if($request['uso']){
            $uso = 1;
        }
        else{$uso = 0;}
        $equipo_mant = DB::table('equipos_mant_temp')
        ->where('equipos_mant_temp.id',$request['id_vieja'])
        ->update([
            'id' => $request['id'],
            'id_tipo' => $request['tipo_equipo_mant_editar'],
            'marca' => $request['marca'],
            'modelo' => $request['modelo'],
            'num_serie' => $request['num_serie'],
            'descripcion' => $request['descripcion'],
            'id_localizacion' => $request['localizacion_editar'],
            'uso' => $uso
        ]);      
        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('equipos_mant');
    }

 
 
}
