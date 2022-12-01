<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Politica;
Use Session;
use DB;


class PoliticaController extends Controller
{
    public function index(Request $request)
    {
        $politicas = Politica::ID($request->get('id_politica'))
        ->Titulo($request->get('titulo_politica'))
        ->Fecha($request->get('fecha_politica'))
        ->paginate(20);

        return view ('politicas.index', array('politicas' => $politicas,'id_politica' => $request->get('id_politica'),
        'titulo_politica' => $request->get('titulo_politica'),'fecha_politica' =>
        $request->get('fecha_politica')));
    }

    public function store_politica(Request $request)
    {        
        $aux = Politica::get()->max('id');
        if($aux==null)
        {
            $aux = 0;
        }

        $politica = new Politica;
        $politica->titulo = $request['titulo'];
        $politica->fecha = $request['fecha'];

        if($request->file('pdf'))
        {
            $file = $request->file('pdf');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();         
            Storage::disk('public')->put('politica/'.$name, \File::get($file));
            $politica->pdf = 'politica\\'.$name;
        }

        $politica->save();

        Session::flash('message','Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('politicas');
    }

    public function destroy_politica($id)
    {
        $politica = Politica::find($id);
        if($politica->pdf != null){
            unlink(storage_path('app\\public\\'.$politica->pdf));
        }

        $politica -> delete(); 
        Session::flash('message','Archivo eliminado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('politicas');
    }

    public function update_politica(Request $request){

        if($request['titulo'] or $request['fecha'])
        {
            $politica = DB::table('politica')
            ->where('politica.id',$request['id'])
            ->update(['titulo' => $request['titulo'], 'fecha' => $request['fecha']]);        
        }
        $aux = Politica::find($request['id']);
        if($request['pdf'] != null)
        {
            if($request->file('pdf'))
            {
                if ($aux->pdf != null)
                {
                    unlink(storage_path('app\\public\\'.$aux->pdf));
                }
                $file = $request->file('pdf');
                $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
                Storage::disk('public')->put('politica/'.$name, \File::get($file));
                $politica = DB::table('politica')
                ->where('politica.id',$request['id'])
                ->update(['pdf' => 'politica\\'.$name,]);
            }
        }
        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('politicas');
    }
}