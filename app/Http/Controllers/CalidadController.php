<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Frecuencia;
use App\Calidad;
Use Session;
use DB;


class CalidadController extends Controller
{
    public function index(Request $request)
    {
        $calidades = Calidad::ID($request->get('id_calidad'))
        ->Titulo($request->get('titulo_calidad'))
        ->Obs($request->get('obs_calidad'))
        ->Fecha($request->get('fecha_calidad'))
        ->Categoria($request->get('categoria_calidad'))
        ->orderBy('id','desc')
        ->paginate(20);
        $frecuencias = Frecuencia::all();

        return view ('calidades.index', array('calidades' => $calidades,'id_calidad' => $request->
        get('id_calidad'),'titulo_calidad' => $request->get('titulo_calidad'), 'obs_calidad' => $request->get('obs_calidad'), 'fecha_calidad' =>
        $request->get('fecha_calidad'), 'categoria_calidad' => $request->get('categoria_calidad')), compact('frecuencias'));
    }

    public function store_calidad(Request $request)
    {        
        $aux = Calidad::get()->max('id');
        if($aux==null)
        {
            $aux = 0;
        }

        $calidad = new Calidad;
        $calidad->titulo = $request['titulo'];
        $calidad->obs = $request['obs'];
        $calidad->fecha = $request['fecha'];
        $calidad->frecuencia = $request['frecuencia'];
        $calidad->categoria = '4';

        if($request->file('pbix'))
        {
            $file = $request->file('pbix');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();         
            Storage::disk('public')->put('powerbi/'.$name, \File::get($file));
            $calidad->pbix = 'powerbi\\'.$name;
        }

        $calidad->save();

        Session::flash('message','Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('calidades');
    }

    public function destroy_calidad($id)
    {
        $calidad = Calidad::find($id);
        if($calidad->pbix != null){
            unlink(storage_path('app\\public\\'.$calidad->pbix));
        }

        $calidad -> delete(); 
        Session::flash('message','Archivo eliminado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('calidades');
    }

    public function update_calidad(Request $request){

        if($request['titulo'] or $request['fecha'] or $request['obs'] or $request['frecuencia'])
        {
            $calidad = DB::table('powerbi')
            ->where('powerbi.id',$request['id'])
            ->update(['titulo' => $request['titulo'], 'fecha' => $request['fecha'], 'obs' => $request['obs'], 'frecuencia' => $request['frecuencia'],]);        
        }
        $aux = Calidad::find($request['id']);
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
                $calidad = DB::table('powerbi')
                ->where('powerbi.id',$request['id'])
                ->update(['pbix' => 'powerbi\\'.$name,]);
            }
        }
        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('calidades');
    }
}