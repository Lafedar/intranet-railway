<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Frecuencia;
use App\Produccion;
Use Session;
use DB;


class ProduccionController extends Controller
{
    public function index(Request $request)
    {
        $producciones = Produccion::ID($request->get('id_produccion'))
        ->Titulo($request->get('titulo_produccion'))
        ->Obs($request->get('obs_produccion'))
        ->Fecha($request->get('fecha_produccion'))
        ->Categoria($request->get('categoria_produccion'))
        ->orderBy('id','desc')
        ->paginate(20);
        $frecuencias = Frecuencia::all();

        return view ('producciones.index', array('producciones' => $producciones,'id_produccion' => $request->
        get('id_produccion'),'titulo_produccion' => $request->get('titulo_produccion'), 'categoria_produccion' => $request->get('categoria_produccion'), 'obs_produccion' => $request->
        get('obs_produccion'), 'fecha_produccion' => $request->get('fecha_produccion')), compact('frecuencias'));
    }

    public function store_produccion(Request $request)
    {        
        $aux = Produccion::get()->max('id');
        if($aux==null)
        {
            $aux = 0;
        }

        $produccion = new Produccion;
        $produccion->titulo = $request['titulo'];
        $produccion->obs = $request['obs'];
        $produccion->fecha = $request['fecha'];
        $produccion->frecuencia = $request['frecuencia'];
        $produccion->categoria = '6';

        if($request->file('pbix'))
        {
            $file = $request->file('pbix');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();         
            Storage::disk('public')->put('powerbi/'.$name, \File::get($file));
            $produccion->pbix = 'powerbi\\'.$name;
        }

        $produccion->save();
        Session::flash('message','Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('producciones');
    }

    public function destroy_produccion($id)
    {
        $produccion = Produccion::find($id);
        if($produccion->pbix != null){
            unlink(storage_path('app\\public\\'.$produccion->pbix));
        }

        $produccion -> delete(); 
        Session::flash('message','Archivo eliminado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('producciones');
    }

    public function update_produccion(Request $request){

        if($request['titulo'] or $request['fecha'] or $request['obs'] or $request['frecuencia'])
        {
            $produccion = DB::table('powerbi')
            ->where('powerbi.id',$request['id'])
            ->update(['titulo' => $request['titulo'], 'fecha' => $request['fecha'], 'obs' => $request['obs'], 'frecuencia' => $request['frecuencia'],]);        
        }
        $aux = Produccion::find($request['id']);
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
                $produccion = DB::table('powerbi')
                ->where('powerbi.id',$request['id'])
                ->update(['pbix' => 'powerbi\\'.$name,]);
            }
        }
        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('producciones');
    }
}