<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Localizacion;
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
        try{
            $search = $request->search;

            $areas = $this->areaService->getAreas();
    
            $localizaciones = $this->localizacionService->getLocalizacionesFiltradas($search)
                ->withQueryString();
    
            if($localizaciones == null){
                Session::flash('message', 'Error al obtener las localizaciones');
                Session::flash('alert-class', 'alert-danger');
            }
            return view('localizaciones.index', ['localizaciones' => $localizaciones, 'areas' => $areas]);
        }catch(Exception $e){
            Session::flash('message', 'Error al obtener las localizaciones');
            Session::flash('alert-class', 'alert-danger');
            
        }
        
    }


    public function show_store_localizacion()
    {
        return view('localizaciones.create');
    }

    public function store_localizacion(Request $request)
    {
        try{
            $id = $request['id'];
            $area= $request['area'];
            $nombre= $request['nombre'];
            $interno= $request['interno'];
            $resultado = $this->localizacionService->storeLocalizacion( $id, $area, $nombre, $interno);
    
            if ($resultado) {
                Session::flash('message', 'Localización agregada con éxito');
                Session::flash('alert-class', 'alert-success');
            } else {
                Session::flash('message', 'El ID de localización ya existe');
                Session::flash('alert-class', 'alert-danger');
            }
    
            return redirect('localizaciones');
        }
        catch(Exception $e){
            Session::flash('message', 'Error al agregar la localización');
            Session::flash('alert-class', 'alert-danger');
        }
        
    }

    public function show_update_localizacion($id_a)
    {
        try{
            $localizacion = $this->localizacionService->show_update_loc($id_a);
            return view('localizaciones.update', ['localizacion' => $localizacion]);
        }
        catch(Exception $e){
            Session::flash('message', 'Error al obtener la localización');
            Session::flash('alert-class', 'alert-danger');
        }
        
    }

    public function update_localizacion(Request $request)
    {
        try{
            $id = $request['id'];
            $nombre = $request['nombre'];
            $interno = $request['interno'];
         
            $resultado=$this->localizacionService->update($id, $nombre, $interno);
     
             if($resultado){
                 Session::flash('message', 'Localizacion modificada con éxito');
                 Session::flash('alert-class', 'alert-success');
             }else{
                 Session::flash('message', 'Error al modificar la localización');
                 Session::flash('alert-class', 'alert-danger');
             }
            
             return redirect('localizaciones');
        }
        catch(Exception $e){
            Session::flash('message', 'Error al modificar la localización');
            Session::flash('alert-class', 'alert-danger');
        }
      
    }

    public function select_area()
    {
        return $this->areaService->getAreas();
    }
       
        
}
