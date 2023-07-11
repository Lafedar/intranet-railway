<?php

namespace App\Http\Controllers;

use DB;
Use Session;
use App\Instructivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructivoController extends Controller{

    public function index(Request $request){
        $instructivos = Instructivo::ID($request->get('id_instructivo'))
        ->Titulo($request->get('titulo'))
        ->Relaciones_index($request->get('id_tipo_instructivo'))
        ->paginate(20);

        $tiposInstructivos = DB::table('tipo_instructivos')->orderBy('nombre','asc')->get();

        return view ('instructivos.index', [
            'instructivos' => $instructivos,
            'id_instructivo' => $request->get('id_instructivo'),
            'titulo' => $request->get('titulo'),
            'id_tipo_instructivo' => $request->get('id_tipo_instructivo'),
            'tiposInstructivos' => $tiposInstructivos]);
    }

    public function show_store_instructivo(){
        return view('instructivos.create');       
    }

    public function store_instructivo(Request $request)
    {
        $maxFileSize = 41943040; // Tamaño máximo permitido en bytes (40 MB en este caso)
        $aux = Instructivo::get()->max('id');
        if ($aux == null) {
            $aux = 0;
        }
        
        $instructivo = new Instructivo;
        $instructivo->titulo = $request['tituloCreate'];
        $instructivo->tipo = $request['tipo_instructivo'];
        
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            
            if ($file->getSize() > $maxFileSize) {
                // El archivo excede el tamaño máximo permitido
                Session::flash('message', 'El archivo excede el tamaño máximo permitido de 40 MB');
                Session::flash('alert-class', 'alert-danger');
                return redirect('instructivos');
            }
            
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT) . $file->getClientOriginalName();
            Storage::disk('public')->put('instructivo/' . $name, \File::get($file));
            $instructivo->archivo = 'instructivo\\' . $name;
        }    
        
        $instructivo->save();
        
        Session::flash('message', 'Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('instructivos');
    }

    public function destroy_instructivo($id){
        $instructivo = Instructivo::find($id);
        if($instructivo->archivo != null){
            unlink(storage_path('app\\public\\'.$instructivo->archivo));
        }

        $instructivo->delete(); 
        Session::flash('message','Archivo eliminado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('instructivos');
    }

    public function show_update_instructivo($id){
        $instructivo = Instructivo::showInstructivoUpdate($id);
        return view('instructivos.update', ['instructivo' => $instructivo]);
    }

    public function update_instructivo(Request $request)
    {
        if ($request['tituloUpdate'] || $request['tipo_instructivo']) {
            $instructivo = DB::table('instructivo')
                ->where('instructivo.id', $request['id'])
                ->update(['titulo' => $request['tituloUpdate'], 'tipo' => $request['tipo_instructivo']]);
        }
        
        $aux = Instructivo::find($request['id']);
        
        if ($request['archivo'] != null) {
            if ($request->file('archivo')) {
                $maxFileSize = 41943040; // Tamaño máximo permitido en bytes (40 MB en este caso)
                $file = $request->file('archivo');
                
                if ($file->getSize() > $maxFileSize) {
                    // El archivo excede el tamaño máximo permitido
                    Session::flash('message', 'El archivo excede el tamaño máximo permitido de 40 MB');
                    Session::flash('alert-class', 'alert-danger');
                    return redirect('instructivos');
                }
                
                if ($aux->archivo != null) {
                    unlink(storage_path('app\\public\\' . $aux->archivo));
                }
                
                $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT) . $file->getClientOriginalName();
                Storage::disk('public')->put('instructivo/' . $name, \File::get($file));
                
                $instructivo = DB::table('instructivo')
                    ->where('instructivo.id', $request['id'])
                    ->update(['archivo' => 'instructivo\\' . $name]);
            }
        }
        
        Session::flash('message', 'Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('instructivos');
    }

    public function select_tipo_instructivos(){
        return Instructivo::getTiposDeInstructivos();
    }
}