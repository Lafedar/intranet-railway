<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Frecuencia;
use App\Venta;
Use Session;
use DB;


class VentaController extends Controller
{
    public function index(Request $request)
    {
        $ventas = Venta::ID($request->get('id_venta'))
        ->Titulo($request->get('titulo_venta'))
        ->Obs($request->get('obs_venta'))
        ->Fecha($request->get('fecha_venta'))
        ->Categoria($request->get('categoria_venta'))
        ->orderBy('id','desc')
        ->paginate(20);
        $frecuencias = Frecuencia::all();

        return view ('ventas.index', array('ventas' => $ventas,'id_venta' => $request->
        get('id_venta'),'titulo_venta' => $request->get('titulo_venta'), 'categoria_venta' => $request->get('categoria_venta'), 'obs_venta' => $request->
        get('obs_venta'), 'fecha_venta' => $request->get('fecha_venta')), compact('frecuencias'));
    }

    public function store_venta(Request $request)
    {        
        $aux = Venta::get()->max('id');
        if($aux==null)
        {
            $aux = 0;
        }

        $venta = new Venta;
        $venta->titulo = $request['titulo'];
        $venta->obs = $request['obs'];
        $venta->fecha = $request['fecha'];
        $venta->frecuencia = $request['frecuencia'];
        $venta->categoria = '1';

        if($request->file('pbix'))
        {
            $file = $request->file('pbix');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();         
            Storage::disk('public')->put('powerbi/'.$name, \File::get($file));
            $venta->pbix = 'powerbi\\'.$name;
        }

        $venta->save();
        Session::flash('message','Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('ventas');
    }

    public function destroy_venta($id)
    {
        $venta = Venta::find($id);
        if($venta->pbix != null){
            unlink(storage_path('app\\public\\'.$venta->pbix));
        }

        $venta -> delete(); 
        Session::flash('message','Archivo eliminado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('ventas');
    }

    public function update_venta(Request $request){

        if($request['titulo'] or $request['fecha'] or $request['obs'] or $request['frecuencia'])
        {
            $venta = DB::table('powerbi')
            ->where('powerbi.id',$request['id'])
            ->update(['titulo' => $request['titulo'], 'fecha' => $request['fecha'], 'obs' => $request['obs'], 'frecuencia' => $request['frecuencia'],]);        
        }
        $aux = Venta::find($request['id']);
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
                $venta = DB::table('powerbi')
                ->where('powerbi.id',$request['id'])
                ->update(['pbix' => 'powerbi\\'.$name,]);
            }
        }
        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('ventas');
    }
}