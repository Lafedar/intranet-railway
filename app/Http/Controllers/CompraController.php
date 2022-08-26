<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Frecuencia;
use App\Compra;
Use Session;
use DB;


class CompraController extends Controller
{
    public function index(Request $request)
    {
        $compras = Compra::ID($request->get('id_compra'))
        ->Titulo($request->get('titulo_compra'))
        ->Obs($request->get('obs_compra'))
        ->Fecha($request->get('fecha_compra'))
        ->Categoria($request->get('categoria_compra'))
        ->orderBy('id','desc')
        ->paginate(20);
        $frecuencias = Frecuencia::all();

        return view ('compras.index', array('compras' => $compras,'id_compra' => $request->
        get('id_compra'),'titulo_compra' => $request->get('titulo_compra'), 'obs_compra' => $request->get('obs_compra'), 'fecha_compra' =>
        $request->get('fecha_compra'), 'categoria_compra' => $request->get('categoria_compra')), compact('frecuencias'));
    }

    public function store_compra(Request $request)
    {        
        $aux = Compra::get()->max('id');
        if($aux==null)
        {
            $aux = 0;
        }

        $compra = new Compra;
        $compra->titulo = $request['titulo'];
        $compra->obs = $request['obs'];
        $compra->fecha = $request['fecha'];
        $compra->frecuencia = $request['frecuencia'];
        $compra->categoria = '2';

        if($request->file('pbix'))
        {
            $file = $request->file('pbix');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();         
            Storage::disk('public')->put('powerbi/'.$name, \File::get($file));
            $compra->pbix = 'powerbi\\'.$name;
        }

        $compra->save();

        Session::flash('message','Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('compras');
    }

    public function destroy_compra($id)
    {
        $compra = Compra::find($id);
        if($compra->pbix != null){
            unlink(storage_path('app\\public\\'.$compra->pbix));
        }

        $compra -> delete(); 
        Session::flash('message','Archivo eliminado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('compras');
    }

    public function update_compra(Request $request){

        if($request['titulo'] or $request['fecha'] or $request['obs'] or $request['frecuencia'])
        {
            $compra = DB::table('powerbi')
            ->where('powerbi.id',$request['id'])
            ->update(['titulo' => $request['titulo'], 'fecha' => $request['fecha'], 'obs' => $request['obs'], 'frecuencia' => $request['frecuencia'],]);        
        }
        $aux = Compra::find($request['id']);
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
                $compra = DB::table('powerbi')
                ->where('powerbi.id',$request['id'])
                ->update(['pbix' => 'powerbi\\'.$name,]);
            }
        }
        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('compras');
    }
}