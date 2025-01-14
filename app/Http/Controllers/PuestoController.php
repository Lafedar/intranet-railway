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
use App\Localizacion;


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


    public function select_localizaciones()
    {
        $localizaciones = $this->puestoService->getLocalizaciones();
        return $localizaciones;
         
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

    public function select_localizaciones_by_area($areaId)
    {
        
        $localizaciones = $this->puestoService->getLocalizacionesByArea($areaId);
        return $localizaciones;
        
        
    }

    public function select_area_by_localizacion($localizacionId)
    {
       
        $area = $this->puestoService->getAreaByLocalizacion($localizacionId);

        if ($area) {
            return response()->json($area);
        } else {
            return response()->json(['error' => 'Localización no encontrada'], 404);
        }
    }

    public function show_store_puesto()
    {
        $areas = $this->areaService->getAll();
        $localizaciones = Localizacion::all();
        $personas = $this->personaService->getAll();
        return view('puestos.create', compact('areas', 'localizaciones', 'personas'));
    }

    public function store_puesto(Request $request)
    {
        // Llama al método del servicio para guardar el puesto
        $this->puestoService->storePuesto($request->all());

        // Mensaje de éxito
        Session::flash('message', 'Puesto agregado con éxito');
        Session::flash('alert-class', 'alert-success');

        // Redirige a la lista de puestos
        return redirect('puestos');
    }

    public function getPuesto(int $id)
    {
        // Llamamos al servicio para obtener el puesto
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
        $isDeleted = $this->puestoService->destroyPuesto($id);
        return redirect('puestos');
    }

    

    public function update_puesto(Request $request)
    {
        // Llamamos al servicio para actualizar el puesto
        $isUpdated = $this->puestoService->updatePuesto($request->all());

        // Redirige al listado de puestos después de la operación
        return redirect('puestos');
    }
}
