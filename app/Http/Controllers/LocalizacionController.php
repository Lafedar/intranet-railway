<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Localizacion;
use App\User;
use Session;
use DB;
use App\Services\AreaService;
use App\Services\LocalizacionService;



class LocalizacionController extends Controller
{
    private AreaService $areaService;
    private LocalizacionService $localizacionService;

    public function __construct(AreaService $areaService, LocalizacionService $localizacionService)
    {
        $this->areaService = $areaService;
        $this->localizacionService = $localizacionService;
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $areas = $this->areaService->getAreas();

        $localizaciones = $this->localizacionService->getLocalizacionesFiltradas($search)
            ->withQueryString(); 
            
        return view('localizaciones.index', ['localizaciones' => $localizaciones, 'areas' => $areas]);
    }


    public function show_store_localizacion()
    {
        return view('localizaciones.create');
    }

    public function store_localizacion(Request $request)
    {
        //consulta en bd si existe el id
        $aux = DB::table('localizaciones')->where('localizaciones.id', $request['id'])->first();

        $localizacion = new Localizacion;
        $localizacion->id_area = $request['area'];
        $localizacion->nombre = $request['nombre'];
        $localizacion->interno = $request['interno'];

        $localizacion->save();

        Session::flash('message', 'Localizacion agregada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('localizaciones');
    }

    public function show_update_localizacion($id_a)
    {
        $localizacion = DB::table('localizaciones')
            ->leftjoin('area', 'area.id_a', 'localizaciones.id_area')
            ->select('localizaciones.id as id', 'localizaciones.nombre as nombre', 'area.nombre_a', 'localizaciones.interno as interno')
            ->where('localizaciones.id', $id_a)
            ->first();

        return view('localizaciones.update', ['localizacion' => $localizacion]);
    }

    public function update_localizacion(Request $request)
    {
        $localizacion = DB::table('localizaciones')
            ->where('localizaciones.id', $request['id'])
            ->update([
                'nombre' => $request['nombre'],
                'interno' => $request['interno']
            ]);
        Session::flash('message', 'Localizacion modificada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('localizaciones');
    }

    public function select_area()
    {
        return DB::table('area')->get();
    }
}
