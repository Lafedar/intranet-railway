<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Solicitud;
use App\Historico_solicitudes;
use App\Falla;
use App\Tipo_solicitud;
use App\User;
Use Session;
use DB;


class SolicitudController extends Controller
{
    public function index(Request $request)
    {
        $solicitudes = Solicitud::ID($request->get('id_solicitud'))
        ->Equipo($request->get('id_equipo'))
        ->Titulo($request->get('id_titulo'))
        ->Relaciones_index($request->get('relaciones'))
        ->orderBy('id_solicitud', 'desc')
        ->paginate(20);

        return view('solicitudes.index', [
        'solicitudes' => $solicitudes
        ]);
    }
    public function show_solicitud($id)
    {
        $solicitud = Solicitud::find($id);
        $historico_solicitudes = DB::table('historico_solicitudes')
            ->where('id_solicitud', $id)
            ->get();
        return view('solicitudes.show', [
            'solicitud' => $solicitud,
            'historico_solicitudes' => $historico_solicitudes,
        ]);
    }
    //trae tabla de tipos de solicitudes 
    public function select_tipo_solicitud()
    {
        return DB::table('tipo_solicitudes')->get();
    }   

    public function select_area()
    {
        return [DB::table('area')->get(), DB::table('localizaciones')->get()];
    }  

    public function select_localizacion()
    {
        return DB::table('localizaciones')->get();
    }  

    public function select_equipo()
    {
        return DB::table('equipos_mant')->get();
    }  

    public function select_falla()
    {
        return DB::table('fallas')->get();
    }  

    public function store_solicitud(Request $request)
    {        
        $aux = Solicitud::get()->max('id');
        if($aux==null)
        {
            $aux = 0;
        }

        $solicitud = new Solicitud;
        $solicitud->titulo = $request['titulo'];
        $solicitud->obs = $request['obs'];
        $solicitud->fecha = $request['fecha'];
        $solicitud->frecuencia = $request['frecuencia'];
        $solicitud->categoria = '2';

        if($request->file('pbix'))
        {
            $file = $request->file('pbix');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT).$file->getClientOriginalName();         
            Storage::disk('public')->put('powerbi/'.$name, \File::get($file));
            $solicitud->pbix = 'powerbi\\'.$name;
        }

        $solicitud->save();

        Session::flash('message','Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect ('solicitudes');
    }

    public function destroy_solicitud($id)
    {
        $solicitud = Solicitud::find($id);
        if($solicitud->pbix != null){
            unlink(storage_path('app\\public\\'.$solicitud->pbix));
        }

        $solicitud -> delete(); 
        Session::flash('message','Archivo eliminado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }

    public function update_solicitud(Request $request){

        if($request['titulo'] or $request['fecha'] or $request['obs'] or $request['frecuencia'])
        {
            $solicitud = DB::table('powerbi')
            ->where('powerbi.id',$request['id'])
            ->update(['titulo' => $request['titulo'], 'fecha' => $request['fecha'], 'obs' => $request['obs'], 'frecuencia' => $request['frecuencia'],]);        
        }
        $aux = Solicitud::find($request['id']);
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
                $solicitud = DB::table('powerbi')
                ->where('powerbi.id',$request['id'])
                ->update(['pbix' => 'powerbi\\'.$name,]);
            }
        }
        Session::flash('message','Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('solicitudes');
    }
}