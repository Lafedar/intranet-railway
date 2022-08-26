<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Frecuencia;
use App\Costo;
Use Session;
use DB;


class CostoController extends Controller
{
    public function index(Request $request)
    {
        $costos = Costo::ID($request->get('id_costo'))
        ->Titulo($request->get('titulo_costo'))
        ->Obs($request->get('obs_costo'))
        ->Fecha($request->get('fecha_costo'))
        ->Categoria($request->get('categoria_costo'))
        ->orderBy('id','desc')
        ->paginate(20);
        $frecuencias = Frecuencia::all();

        return view ('costos.index', array('costos' => $costos,'id_costo' => $request->
        get('id_costo'),'titulo_costo' => $request->get('titulo_costo'), 'obs_costo' => $request->get('obs_costo'), 'fecha_costo' =>
        $request->get('fecha_costo'), 'categoria_costo' => $request->get('categoria_costo')), compact('frecuencias'));
    }

    public function store_costo(Request $request)
    {        
        $aux = Costo::get()->max('id');
        if($aux==null)
        {
            $aux = 0;
        }

        $costo = new Costo;
        $costo->titulo = $request['titulo'];
        $costo->obs = $request['obs'];
        $costo->fecha = $request['fecha'];
        $costo->frecuencia = $request['frecuencia'];
        $costo->categoria = '3';

        if($request->file('pbix'))
        {
            $file = $request->file('pbix');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();         
            Storage::disk('public')->put('powerbi/'.$name, \File::get($file));
            $costo->pbix = 'powerbi\\'.$name;
        }

        $costo->save();

        Session::flash('message','Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('costos');
    }

    public function destroy_costo($id)
    {
        $costo = Costo::find($id);
        if($costo->pbix != null){
            unlink(storage_path('app\\public\\'.$costo->pbix));
        }

        $costo -> delete(); 
        Session::flash('message','Archivo eliminado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('costos');
    }

    public function update_costo(Request $request){

        if($request['titulo'] or $request['fecha'] or $request['obs'] or $request['frecuencia'])
        {
            $costo = DB::table('powerbi')
            ->where('costos.id',$request['id'])
            ->update(['titulo' => $request['titulo'], 'fecha' => $request['fecha'], 'obs' => $request['obs'], 'frecuencia' => $request['frecuencia'],]);        
        }
        $aux = Costo::find($request['id']);
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
                $costo = DB::table('powerbi')
                ->where('powerbi.id',$request['id'])
                ->update(['pbix' => 'powerbi\\'.$name,]);
            }
        }
        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('costos');
    }
}