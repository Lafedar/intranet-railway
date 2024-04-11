<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Empleado;
use App\User;
use App\Tarjeta;
use App\Visita;
use App\Empresa;
use App\Externo;
use Auth;
use DB;
Use Session;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;


class VisitaController extends Controller
{
    public function index()
    {
        $tarjetas = DB::table('tarjetas')->where('libre',0)->get();

        return view ('visita.index', array('tarjetas'=>$tarjetas));
    }
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $tarjeta = DB::table('tarjetas')->where('id_tar',$request['tarjeta'])->first();

        $tar = DB::table('tarjetas')
        ->where('id_tar',$request['tarjeta'])
        ->update(['libre' => 0]);

        $visita = new Visita;
        $visita->interno = $request['interno'];
        $visita->externo = $request['externo'];
        $visita->tarjeta = $request['tarjeta'];
        $visita->activa = 1;
        $visita->save();

        $externo = DB::table('externos')
        ->where('externos.dni',$request['externo'])
        ->update([
            'activo' => 0,
        ]);

        Session::flash('message','Tarjeta asignada con éxito');
        Session::flash('alert-class', 'alert-success');

        return redirect('visitas');
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        
    }

    public function asignar()
    {
        $empresas = DB::table('empresas')->orderBy('razon_social','asc')->get();

        $tarjetas = DB::table('tarjetas')->where('libre',1)->get();

        $internos = DB::table('personas')
        ->where('personas.activo',1)
        ->orderBy('apellido','asc')->get();

        return view ('visita.asignar', array('empresas'=>$empresas, 'internos'=>$internos, 'tarjetas'=>$tarjetas));
    }

    public function getExterno($id){

        return DB::table('externos')->where('empresa_ext',$id)->where('externos.activo',1)->get();
    }


    public function baja(Request $request)
    {
        $tarjeta = DB::table('tarjetas')->where('id_tar',$request['id'])->first();

        $aux = DB::table('visitas')->where('visitas.tarjeta',$tarjeta->id_tar)->where('visitas.activa',1)->first();

        $visita = Visita::find($aux->id_vis);
        $visita->activa = 0;
        $visita->save();

        $tarjeta = DB::table('tarjetas')
        ->where('id_tar',$request['id'])
        ->update([
            'libre' => 1,
        ]);

        $externo = DB::table('externos')
        ->where('externos.dni',$visita->externo)
        ->update([
            'activo' => 1,
        ]);

        Session::flash('message','Tarjeta dada de baja con éxito');
        Session::flash('alert-class', 'alert-success');
        
        return redirect('visitas');
    }

    public function consulta(Request $request)
    { 
        $visitas = Visita::Relaciones($request->get('estado'))
        ->Tarjeta($request->get('tarjeta'))
        ->Visita_a($request->get('visita_a'))
        ->Visitante($request->get('visitante'))
        ->Fecha($request->get('fecha'))
        ->paginate(20);
        
        return view ('visita.consulta', array('visitas'=>$visitas, 'tarjeta'=>$request->get('tarjeta'), 'visita_a'=>$request->get('visita_a'), 'visitante'=>$request->get('visitante'), 'estado'=>$request->get('estado'), 'fecha'=> $request->get('fecha')));
    }

    public function añadir_empresa(Request $request)
    {
        $aux=DB::table('empresas')->where('empresas.razon_social',$request['razon_social'])->count();

        if($aux == 0){
            $empresa = new Empresa;
            $empresa->razon_social = $request['razon_social'];
            $empresa->save();
        }
        else{
            Session::flash('message','Razon social ingresada ya existe');
            Session::flash('alert-class', 'alert-warning');
        }
        return redirect()->back()->withInput();
    }

    public function añadir_externo(Request $request)
    {

        $aux=DB::table('externos')->where('externos.dni',$request['dni'])->count();

        if($aux == 0){
            $externo = new Externo;
            $externo->dni = $request['dni'];
            $externo->nombre_ext = $request['nombre_ext'];
            $externo->apellido_ext = $request['apellido_ext'];
            $externo->telefono_ext = $request['telefono_ext'];
            $externo->empresa_ext = $request['empresa_ext'];
            $externo->activo = 1;

            if($request->file('foto')){
                $file = $request->file('foto');
                $name = $file->getClientOriginalName();
                Storage::disk('public')->put('fotos_externos/'.$name, \File::get($file));
                $externo->foto = 'fotos_externos\\'.$name;
            }

            $externo->save();
            Session::flash('message','Persona ingresada con éxito');
            Session::flash('alert-class', 'alert-success');
        }
        else{
            Session::flash('message','DNI ingresado ya existe');
            Session::flash('alert-class', 'alert-warning');
        }
        return redirect()->back()->withInput();
    }

    public function editar_externo(Request $request){

     $externo = DB::table('externos')
     ->where('externos.dni',$request['dni'])
     ->update([
        'nombre_ext' => $request['nombre_ext'],
        'apellido_ext' => $request['apellido_ext'],
        'telefono_ext' => $request['telefono_ext'],
    ]);

     $aux = Externo::find($request['dni']);
     if($request['foto '] != null){
        if($request->file('foto')){
            if ($aux->foto != null){
                unlink(storage_path('app\\public\\'.$aux->foto));
            }
            $file = $request->file('foto');
            $name = $file->getClientOriginalName();
            Storage::disk('public')->put('fotos_externos/'.$name, \File::get($file));
            $externo = DB::table('externos')
            ->where('externos.dni',$request['dni'])
            ->update([
                'foto' => 'fotos_externos\\'.$name,
            ]);
        }
    };

    Session::flash('message','Registro modificado con éxito');
    Session::flash('alert-class', 'alert-success');
    return redirect('listado');
}

public function fotoExterno ($id){
    return DB::table('externos')->where('externos.dni',$id)->select('externos.foto')->get();
}

public function listado (Request $request){
    $listado = Externo::Busca()->get();
    return view ('visita.listado', array('listado' => $listado));
}


public function destroy_externo(Request $request, $dni){
 $externo = DB::table('externos')
        ->where('externos.dni',$dni)
        ->update([
        'activo' => 0,
    ]);

 return response()->json([
    'message' => 'Registro eliminado con éxito'
]);
}


}
