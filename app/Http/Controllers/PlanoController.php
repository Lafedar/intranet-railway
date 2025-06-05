<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Plano;
use Session;
use DB;


class PlanoController extends Controller
{
    public function planos(Request $request)
    {
        $planos = Plano::ID($request->get('id_plano'))
            ->Titulo($request->get('titulo_plano'))
            ->Obs($request->get('obs_plano'))
            ->Fecha($request->get('fecha_plano'))
            ->Version($request->get('version'))
            ->orderBy('id', 'desc')
            ->paginate(20)->withQueryString();

        return view('planos.planos', array('planos' => $planos, 'id_plano' => $request->get('id_plano'), 'titulo_plano' => $request->get('titulo_plano'), 'obs_plano' => $request->get('obs_plano'), 'version' => $request->get('version'),  'fecha_plano' => $request->get('fecha_plano')));
    }

    public function store_planos(Request $request)
    {

        $aux = Plano::get()->max('id');
        if ($aux == null) {
            $aux = 0;
        }

        $plano = new Plano;
        $plano->titulo = $request['titulo'];
        $plano->obs = $request['obs'];
        $plano->fecha = $request['fecha'];
        $plano->version = $request['version'];

        if ($request->file('pdf')) {
            $file = $request->file('pdf');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT) . $file->getClientOriginalName();
            Storage::disk('public')->put('planos/' . $name, \File::get($file));
            $plano->pdf = 'planos\\' . $name;
        }
        if ($request->file('pdf_firmado')) {
            $file = $request->file('pdf_firmado');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT) . $file->getClientOriginalName();
            Storage::disk('public')->put('planos/' . $name, \File::get($file));
            $plano->pdf_firmado = 'planos\\' . $name;
        }

        if ($request->file('dwg')) {
            $file = $request->file('dwg');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT) . $file->getClientOriginalName();
            Storage::disk('public')->put('planos/' . $name, \File::get($file));
            $plano->dwg = 'planos\\' . $name;
        }
        if ($request->file('ctb')) {
            $file = $request->file('ctb');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT) . $file->getClientOriginalName();
            Storage::disk('public')->put('planos/' . $name, \File::get($file));
            $plano->ctb = 'planos\\' . $name;
        }
        $plano->save();

        Session::flash('message', 'Plano agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('planos');

    }

    public function destroy_planos($id)
    {
        $plano = Plano::find($id);
        if ($plano->pdf != null) {
            unlink(storage_path('app\\public\\' . $plano->pdf));
        }
        if ($plano->pdf_firmado != null) {
            unlink(storage_path('app\\public\\' . $plano->pdf_firmado));
        }
        if ($plano->dwg != null) {
            unlink(storage_path('app\\public\\' . $plano->dwg));
        }
        if ($plano->ctb != null) {
            unlink(storage_path('app\\public\\' . $plano->ctb));
        }
        $plano->delete();
        Session::flash('message', 'Plano eliminado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('planos');
    }

    public function update_planos(Request $request)
    {

        if ($request['titulo'] or $request['fecha'] or $request['obs'] or $request['version']) {
            $plano = DB::table('planos')
                ->where('planos.id', $request['id'])
                ->update([
                    'titulo' => $request['titulo'],
                    'fecha' => $request['fecha'],
                    'obs' => $request['obs'],
                    'version' => $request['version'],
                ]);
        }
        $aux = Plano::find($request['id']);
        if ($request['pdf'] != null) {
            if ($request->file('pdf')) {
                if ($aux->pdf != null) {
                    unlink(storage_path('app\\public\\' . $aux->pdf));
                }
                $file = $request->file('pdf');
                $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT) . $file->getClientOriginalName();
                Storage::disk('public')->put('planos/' . $name, \File::get($file));
                $plano = DB::table('planos')
                    ->where('planos.id', $request['id'])
                    ->update([
                        'pdf' => 'planos\\' . $name,
                    ]);
            }
        }
        ;
        if ($request['pdf_firmado'] != null) {
            if ($request->file('pdf_firmado')) {
                if ($aux->pdf_firmado != null) {
                    unlink(storage_path('app\\public\\' . $aux->pdf_firmado));
                }
                $file = $request->file('pdf_firmado');
                $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT) . $file->getClientOriginalName();
                Storage::disk('public')->put('planos/' . $name, \File::get($file));
                $plano = DB::table('planos')
                    ->where('planos.id', $request['id'])
                    ->update([
                        'pdf_firmado' => 'planos\\' . $name,
                    ]);
            }
        }
        ;
        if ($request['dwg'] != null) {
            if ($request->file('dwg')) {
                if ($aux->dwg != null) {
                    unlink(storage_path('app\\public\\' . $aux->dwg));
                }
                $file = $request->file('dwg');
                $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT) . $file->getClientOriginalName();
                Storage::disk('public')->put('planos/' . $name, \File::get($file));
                $plano = DB::table('planos')
                    ->where('planos.id', $request['id'])
                    ->update([
                        'dwg' => 'planos\\' . $name,
                    ]);
            }
        }
        ;
        if ($request['ctb'] != null) {
            if ($request->file('ctb')) {
                if ($aux->ctb != null) {
                    unlink(storage_path('app\\public\\' . $aux->ctb));
                }
                $file = $request->file('ctb');
                $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT) . $file->getClientOriginalName();
                Storage::disk('public')->put('planos/' . $name, \File::get($file));
                $plano = DB::table('planos')
                    ->where('planos.id', $request['id'])
                    ->update([
                        'ctb' => 'planos\\' . $name,
                    ]);
            }
        }
        ;

        if ($request['eliminar_pdf'] == 1) {
            if ($aux->pdf != null) {
                unlink(storage_path('app\\public\\' . $aux->pdf));
                $plano = DB::table('planos')
                    ->where('planos.id', $request['id'])
                    ->update([
                        'pdf' => null,
                    ]);
            }
        }
        if ($request['eliminar_pdf_firmado'] == 1) {
            if ($aux->pdf_firmado != null) {
                unlink(storage_path('app\\public\\' . $aux->pdf_firmado));
                $plano = DB::table('planos')
                    ->where('planos.id', $request['id'])
                    ->update([
                        'pdf_firmado' => null,
                    ]);
            }
        }
        if ($request['eliminar_dwg'] == 1) {
            if ($aux->dwg != null) {
                unlink(storage_path('app\\public\\' . $aux->dwg));
                $plano = DB::table('planos')
                    ->where('planos.id', $request['id'])
                    ->update([
                        'dwg' => null,
                    ]);
            }
        }

        if ($request['eliminar_ctb'] == 1) {
            if ($aux->ctb != null) {
                unlink(storage_path('app\\public\\' . $aux->ctb));
                $plano = DB::table('planos')
                    ->where('planos.id', $request['id'])
                    ->update([
                        'ctb' => null,
                    ]);
            }
        }

        Session::flash('message', 'Plano modificado con éxito');
        Session::flash('alert-class', 'alert-success');

        return redirect('planos');
    }

}
