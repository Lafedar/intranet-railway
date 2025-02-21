<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Equipamiento;
use App\Puesto;
use App\Relacion;
use App\Persona;
use App\Incidente;
use App\User;
use Auth;
use DB;
use Redirect;
use Illuminate\Support\Facades\Input;
use Session;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use App\Services\AreaService;
use App\Services\PersonaService;
use App\Services\PuestoService;
use App\Models\Location;
use Exception;


class PuestoController extends Controller
{
    private AreaService $areaService;
    private PersonaService $personaService;

    private PuestoService $puestoService;

    public function __construct(AreaService $areaService, PersonaService $personaService, PuestoService $puestoService)
    {
        $this->areaService = $areaService;
        $this->personaService = $personaService;
        $this->puestoService = $puestoService;
    }
    public function puestos(Request $request)
    {
        $puestos = $this->puestoService->getPuestos($request->all());

        return view('puestos.puestos', [
            'puestos' => $puestos,
            'puesto' => $request->get('puesto'),
            'usuario' => $request->get('usuario'),
            'area' => $request->get('area'),
            'localizacion' => $request->get('localizacion')
        ]);
    }


    public function select_locations()
    {
        $locations = $this->puestoService->getLocations();
        return $locations;

    }

    public function select_area()
    {
        $areas = $this->areaService->getAll();
        return $areas;
    }

    public function select_persona()
    {
        $personas = $this->personaService->getAll();
        return $personas;
    }

    public function get_locations_by_area($areaId)
    {

        $localizaciones = $this->puestoService->getLocationsByArea($areaId);
        return $localizaciones;


    }

    public function select_area_by_location($locationId)
    {

        $area = $this->puestoService->getAreaByLocation($locationId);

        if ($area) {
            return response()->json($area);
        } else {
            return response()->json(['error' => 'Localización no encontrada'], 404);
        }
    }

    public function show_store_puesto()
    {
        $areas = $this->areaService->getAreas();
        $locations = Location::all();
        $personas = $this->personaService->getAll();
        return view('puestos.create', compact('areas', 'locations', 'personas'));
    }

    public function store_puesto(Request $request)
    {
        $this->puestoService->storePuesto($request->all());

        Session::flash('message', 'Puesto agregado con éxito');
        Session::flash('alert-class', 'alert-success');

        return redirect('puestos');
    }

    public function getPuesto(int $id)
    {
        $puesto = $this->puestoService->getPuesto($id);

        if ($puesto) {
            return $puesto;
        } else {
            return response()->json(['error' => 'Puesto no encontrado'], 404);
        }
    }

    public function show_update_puesto(int $id)
    {
        $puesto = Puesto::showPuestoUpdate($id);
        return view('puestos.update', ['puesto' => $puesto]);
    }


    public function destroy_puesto(int $id)
    {
        try {
            $result = $this->puestoService->destroyPuesto($id);

            if ($result) {

                Session::flash('message', 'Puesto eliminado con éxito');
                Session::flash('alert-class', 'alert-success');
            } else {

                Session::flash('message', 'No se puede eliminar este puesto, ya que tiene equipos asignados o no fue encontrado');
                Session::flash('alert-class', 'alert-warning');
            }

            return redirect('puestos');
        }catch(Exception $e){
            Session::flash('message', 'Error al eliminar el puesto: ' . $e->getMessage());
            Session::flash('alert-class', 'alert-danger');
        }

    }



    public function update_puesto(Request $request)
    {
        try {
            $result = $this->puestoService->updatePuesto($request->all());

            if ($result) {
                Session::flash('message', 'Puesto modificado con éxito');
                Session::flash('alert-class', 'alert-success');
            } else {
                Session::flash('message', 'Puesto no encontrado');
                Session::flash('alert-class', 'alert-warning');
            }
            return redirect('puestos');
            
        } catch (Exception $e) {
            Session::flash('message', 'Error al actualizar el puesto: ' . $e->getMessage());
            Session::flash('alert-class', 'alert-danger');
        }

        
    }
}
