<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Instructivo;
Use Session;
use DB;


class InstructivoController extends Controller
{
    public function index(Request $request)
    {
        $instructivos = Instructivo::ID($request->get('id_instructivo'))
        ->Titulo($request->get('titulo_instructivo'))
        ->Fecha($request->get('fecha_instructivo'))
        ->paginate(20);

        return view ('instructivos.index', array('instructivos' => $instructivos,'id_instructivo' => $request->get('id_instructivo'),
        'titulo_instructivo' => $request->get('titulo_instructivo'),'fecha_instructivo' =>
        $request->get('fecha_instructivo')));
    }

    public function store_instructivo(Request $request)
    {        
        $aux = Instructivo::get()->max('id');
        if($aux == null)
        {
            $aux = 0;
        }

        $instructivo = new Instructivo;
        $instructivo->titulo = $request['titulo'];
        $instructivo->fecha = $request['fecha'];

        if($request->file('archivo'))
        {
            $file = $request->file('archivo');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();         
            Storage::disk('public')->put('instructivo/'.$name, \File::get($file));
            $instructivo->archivo = 'instructivo\\'.$name;
        }

        $instructivo->save();

        Session::flash('message','Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('instructivos');
    }

    public function destroy_instructivo($id)
    {
        $instructivo = Instructivo::find($id);
        if($instructivo->archivo != null){
            unlink(storage_path('app\\public\\'.$instructivo->archivo));
        }

        $instructivo->delete(); 
        Session::flash('message','Archivo eliminado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('instructivos');
    }

    public function update_instructivo(Request $request)
    {
        if($request['titulo'] or $request['fecha'])
        {
            $instructivo = DB::table('instructivo')
            ->where('instructivo.id',$request['id'])
            ->update(['titulo' => $request['titulo'], 'fecha' => $request['fecha']]);        
        }
        $aux = Instructivo::find($request['id']);
        if($request['archivo'] != null)
        {
            if($request->file('archivo'))
            {
                if ($aux->archivo != null)
                {
                    unlink(storage_path('app\\public\\'.$aux->archivo));
                }
                $file = $request->file('archivo');
                $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
                Storage::disk('public')->put('instructivo/'.$name, \File::get($file));
                $instructivo = DB::table('instructivo')
                ->where('instructivo.id',$request['id'])
                ->update(['archivo' => 'instructivo\\'.$name]);
            }
        }
        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('instructivos');
    }
}