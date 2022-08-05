<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Proyecto;
Use Session;
use DB;

class ProyectoController extends Controller
{
    public function proyectos (Request $request){
   
    	$proyectos = Proyecto::ID($request->get('id_proyecto'))
        ->Titulo($request->get('titulo_proyecto'))
        ->Obs($request->get('obs_proyecto'))
        ->Fecha($request->get('fecha_proyecto'))
        ->orderBy('id','desc')
        ->paginate(20);
        
        return view ('proyectos.proyectos', array('proyectos' => $proyectos, 'id_proyecto' => $request->get('id_proyecto'), 'titulo_proyecto' => $request->get('titulo_proyecto'), 'obs_proyecto' => $request->get('obs_proyecto'),'fecha_proyecto' => $request->get('fecha_proyecto')));
    }

     public function store_proyectos(Request $request){
     	$aux = Proyecto::get()->max('id');

        if($aux==null){
            $aux = 0;
        }

        $proyecto = new Proyecto;
        $proyecto->titulo = $request['titulo'];
        $proyecto->obs = $request['obs'];
        $proyecto->fecha = $request['fecha'];
        
        if($request->file('asm')){
            $file = $request->file('asm');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
            Storage::disk('public')->put('proyectos/'.$name, \File::get($file));
            $proyecto->asm = 'proyectos\\'.$name;
        }
        if($request->file('dwg')){
            $file = $request->file('dwg');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
            Storage::disk('public')->put('proyectos/'.$name, \File::get($file));
            $proyecto->dwg = 'proyectos\\'.$name;
        }
        if($request->file('par')){
            $file = $request->file('par');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
            Storage::disk('public')->put('proyectos/'.$name, \File::get($file));
            $proyecto->par = 'proyectos\\'.$name;
        }
        if($request->file('stl')){
            $file = $request->file('stl');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
            Storage::disk('public')->put('proyectos/'.$name, \File::get($file));
            $proyecto->stl = 'proyectos\\'.$name;
        }
        if($request->file('pdf')){
            $file = $request->file('pdf');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
            Storage::disk('public')->put('proyectos/'.$name, \File::get($file));
            $proyecto->pdf = 'proyectos\\'.$name;
        }
        if($request->file('mpp')){
            $file = $request->file('mpp');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
            Storage::disk('public')->put('proyectos/'.$name, \File::get($file));
            $proyecto->mpp = 'proyectos\\'.$name;
        }
        $proyecto->save();

		Session::flash('message','Proyecto agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('proyectos');
     }

     public function update_proyectos(Request $request){
     	if($request['titulo'] or $request['fecha'] or $request['obs']){
         $proyecto = DB::table('proyectos')
         ->where('proyectos.id',$request['id'])
         ->update([
            'titulo' => $request['titulo'],
            'fecha' => $request['fecha'],
            'obs' => $request['obs'],
        ]);        
     }
     $aux = Proyecto::find($request['id']);
     if($request['asm'] != null){
        if($request->file('asm')){
            if ($aux->asm != null){
                unlink(storage_path('app\\public\\'.$aux->asm));
            }
            $file = $request->file('asm');
            $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
            Storage::disk('public')->put('proyectos/'.$name, \File::get($file));
            $proyecto = DB::table('proyectos')
            ->where('proyectos.id',$request['id'])
            ->update([
                'asm' => 'proyectos\\'.$name,
            ]);
        }
    };
    if($request['dwg'] != null){
        if($request->file('dwg')){
            if ($aux->dwg != null){
                unlink(storage_path('app\\public\\'.$aux->dwg));
            }
            $file = $request->file('dwg');
            $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
            Storage::disk('public')->put('proyectos/'.$name, \File::get($file));
            $proyecto = DB::table('proyectos')
            ->where('proyectos.id',$request['id'])
            ->update([
                'dwg' => 'proyectos\\'.$name,
            ]);
        }
    };
    if($request['par'] != null){
        if($request->file('par')){
            if ($aux->par != null){
                unlink(storage_path('app\\public\\'.$aux->par));
            }
            $file = $request->file('par');
            $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
            Storage::disk('public')->put('proyectos/'.$name, \File::get($file));
            $proyecto = DB::table('proyectos')
            ->where('proyectos.id',$request['id'])
            ->update([
                'par' => 'proyectos\\'.$name,
            ]);
        }
    };
    if($request['stl'] != null){
        if($request->file('stl')){
            if ($aux->stl != null){
                unlink(storage_path('app\\public\\'.$aux->stl));
            }
            $file = $request->file('stl');
            $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
            Storage::disk('public')->put('proyectos/'.$name, \File::get($file));
            $proyecto = DB::table('proyectos')
            ->where('proyectos.id',$request['id'])
            ->update([
                'stl' => 'proyectos\\'.$name,
            ]);
        }
    };
    if($request['pdf'] != null){
        if($request->file('pdf')){
            if ($aux->pdf != null){
                unlink(storage_path('app\\public\\'.$aux->pdf));
            }
            $file = $request->file('pdf');
            $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
            Storage::disk('public')->put('proyectos/'.$name, \File::get($file));
            $proyecto = DB::table('proyectos')
            ->where('proyectos.id',$request['id'])
            ->update([
                'pdf' => 'proyectos\\'.$name,
            ]);
        }
    };
    if($request['mpp'] != null){
        if($request->file('mpp')){
            if ($aux->mpp != null){
                unlink(storage_path('app\\public\\'.$aux->mpp));
            }
            $file = $request->file('mpp');
            $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();
            Storage::disk('public')->put('proyectos/'.$name, \File::get($file));
            $proyecto = DB::table('proyectos')
            ->where('proyectos.id',$request['id'])
            ->update([
                'mpp' => 'proyectos\\'.$name,
            ]);
        }
    };
    if($request['eliminar_asm'] == 1){
        if($aux->asm != null){
            unlink(storage_path('app\\public\\'.$aux->asm));
            $proyecto = DB::table('proyectos')
            ->where('proyectos.id',$request['id'])
            ->update([
                'asm' => null,
            ]);
        }
    }
    if($request['eliminar_dwg'] == 1){
        if($aux->dwg != null){
            unlink(storage_path('app\\public\\'.$aux->dwg));
            $proyecto = DB::table('proyectos')
            ->where('proyectos.id',$request['id'])
            ->update([
                'dwg' => null,
            ]);
        }
    }
    if($request['eliminar_par'] == 1){
        if($aux->par != null){
            unlink(storage_path('app\\public\\'.$aux->par));
            $proyecto = DB::table('proyectos')
            ->where('proyectos.id',$request['id'])
            ->update([
                'par' => null,
            ]);
        }
    }
    if($request['eliminar_stl'] == 1){
        if($aux->stl != null){
            unlink(storage_path('app\\public\\'.$aux->stl));
            $proyecto = DB::table('proyectos')
            ->where('proyectos.id',$request['id'])
            ->update([
                'stl' => null,
            ]);
        }
    }
    if($request['eliminar_pdf'] == 1){
        if($aux->pdf != null){
            unlink(storage_path('app\\public\\'.$aux->pdf));
            $proyecto = DB::table('proyectos')
            ->where('proyectos.id',$request['id'])
            ->update([
                'pdf' => null,
            ]);
        }
    }
    if($request['eliminar_mpp'] == 1){
        if($aux->mpp != null){
            unlink(storage_path('app\\public\\'.$aux->mpp));
            $proyecto = DB::table('proyectos')
            ->where('proyectos.id',$request['id'])
            ->update([
                'mpp' => null,
            ]);
        }
    }
    Session::flash('message','Proyecto modificado con éxito');
    Session::flash('alert-class', 'alert-success');

    return redirect('proyectos');
    }

        public function destroy_proyecto($id)
    {
        $proyecto = Proyecto::find($id);
        if($proyecto->asm != null){
            unlink(storage_path('app\\public\\'.$proyecto->asm));
        }
        if($proyecto->dwg != null){
            unlink(storage_path('app\\public\\'.$proyecto->dwg));
        }
        if($proyecto->par != null){
            unlink(storage_path('app\\public\\'.$proyecto->par));
        }
        if($proyecto->stl != null){
            unlink(storage_path('app\\public\\'.$proyecto->stl));
        }
        if($proyecto->pdf != null){
            unlink(storage_path('app\\public\\'.$proyecto->pdf));
        }
        if($proyecto->mpp != null){
            unlink(storage_path('app\\public\\'.$proyecto->mpp));
        }
        $proyecto -> delete();

        return response()->json([
        'message' => 'Proyecto eliminado con éxito'
        ]);     
    }
}