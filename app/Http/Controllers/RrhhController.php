<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Frecuencia;
use App\Rrhh;
Use Session;
use DB;


class RrhhController extends Controller
{
    public function index(Request $request)
    {
        $rrhhs = Rrhh::ID($request->get('id_rrhh'))
        ->Titulo($request->get('titulo_rrhh'))
        ->Obs($request->get('obs_rrhh'))
        ->Fecha($request->get('fecha_rrhh'))
        ->Categoria($request->get('categoria_rrhh'))
        ->orderBy('id','desc')
        ->paginate(20);
        $frecuencias = Frecuencia::all();

        return view ('rrhhs.index', array('rrhhs' => $rrhhs,'id_rrhh' => $request->
        get('id_rrhh'),'titulo_rrhh' => $request->get('titulo_rrhh'), 'categoria_rrhh' => $request->get('categoria_rrhh'), 'obs_rrhh' => $request->
        get('obs_rrhh'), 'fecha_rrhh' => $request->get('fecha_rrhh')), compact('frecuencias'));
    }

    public function store_rrhh(Request $request)
    {        
        $aux = Rrhh::get()->max('id');
        if($aux==null)
        {
            $aux = 0;
        }

        $rrhh = new Rrhh;
        $rrhh->titulo = $request['titulo'];
        $rrhh->obs = $request['obs'];
        $rrhh->fecha = $request['fecha'];
        $rrhh->frecuencia = $request['frecuencia'];
        $rrhh->categoria = '5';

        if($request->file('pbix'))
        {
            $file = $request->file('pbix');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();         
            Storage::disk('public')->put('powerbi/'.$name, \File::get($file));
            $rrhh->pbix = 'powerbi\\'.$name;
        }

        $rrhh->save();
        Session::flash('message','Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('rrhhs');
    }

    public function destroy_rrhh($id)
    {
        $rrhh = Rrhh::find($id);
        if($rrhh->pbix != null){
            unlink(storage_path('app\\public\\'.$rrhh->pbix));
        }

        $rrhh -> delete(); 
        Session::flash('message','Archivo eliminado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('rrhhs');
    }

    public function update_rrhh(Request $request){

        if($request['titulo'] or $request['fecha'] or $request['obs'] or $request['frecuencia'])
        {
            $rrhh = DB::table('powerbi')
            ->where('powerbi.id',$request['id'])
            ->update(['titulo' => $request['titulo'], 'fecha' => $request['fecha'], 'obs' => $request['obs'], 'frecuencia' => $request['frecuencia'],]);        
        }
        $aux = Rrhh::find($request['id']);
        if($request['pbix'] != null)
        {
            if($request->file('pbix'))
            {
                if ($aux->pbix != null)
                {
                    unlink(storage_path('app\\public\\'.$aux->pbix));
                }
                $file = $request->file('pbix');
                $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
                Storage::disk('public')->put('powerbi/'.$name, \File::get($file));
                $rrhh = DB::table('powerbi')
                ->where('powerbi.id',$request['id'])
                ->update(['pbix' => 'powerbi\\'.$name,]);
            }
        }
        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('rrhhs');
    }
}