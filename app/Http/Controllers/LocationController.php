<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Location;
use App\User;
use Session;
use DB;
use App\Services\AreaService;
use App\Services\LocationService;



class LocationController extends Controller
{
    private AreaService $areaService;
    private LocationService $locationService;

    public function __construct(AreaService $areaService, LocationService $locationService)
    {
        $this->areaService = $areaService;
        $this->locationService = $locationService;
    }

    public function listLocations(Request $request)
    {
        try{
            $search = $request->search;

            $areas = $this->areaService->getAreas();
    
            $locations = $this->locationService->getFilteredLocations($search)->withQueryString();
                
    
            if($locations == null){
                Session::flash('message', 'Error al obtener las localizaciones');
                Session::flash('alert-class', 'alert-danger');
            }
            return view('locations.index', ['locations' => $locations, 'areas' => $areas]);
        }catch(Exception $e){
            Session::flash('message', 'Error al obtener las localizaciones');
            Session::flash('alert-class', 'alert-danger');
            
        }
        
    }


    public function show_creation_form()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        try{
            
            $data = [
                'id' => $request['id'],
                'area' => $request['area'],
                'name' => $request['name'],
                'internal' => $request['internal']
            ];

            $result = $this->locationService->store($data);
    
            if ($result) {
                Session::flash('message', 'Localización agregada con éxito');
                Session::flash('alert-class', 'alert-success');
            } else {
                Session::flash('message', 'El ID de localización ya existe');
                Session::flash('alert-class', 'alert-danger');
            }
    
            return redirect('locations');
        }
        catch(Exception $e){
            Session::flash('message', 'Error al agregar la localización');
            Session::flash('alert-class', 'alert-danger');
        }
        
    }

    public function show_update_form($id_a)
    {
        try{
            $location = $this->locationService->show_update($id_a);
            return view('locations.update', ['location' => $location]);
        }
        catch(Exception $e){
            Session::flash('message', 'Error al obtener la localización');
            Session::flash('alert-class', 'alert-danger');
        }
        
    }

    public function update(Request $request)
    {
        try{
          
            $data = [
                'id' => $request['id'],
                'name' => $request['name'],
                'internal' => $request['internal']
            ];

            $result = $this->locationService->update($data);
     
             if($result){
                 Session::flash('message', 'Localizacion modificada con éxito');
                 Session::flash('alert-class', 'alert-success');
             }else{
                 Session::flash('message', 'Error al modificar la localización');
                 Session::flash('alert-class', 'alert-warning');
             }
            
             return redirect('locations');
        }
        catch(Exception $e){
            Session::flash('message', 'Error al modificar la localización');
            Session::flash('alert-class', 'alert-warning');
        }
      
    }

    public function get_areas()
    {
        return $this->areaService->getAreas();
    }
       
        
}
