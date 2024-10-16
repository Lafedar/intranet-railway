<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\NovedadService;
use App\Novedad;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class NovedadesController extends Controller
{
    protected $novedadService;

    public function __construct(NovedadService $novedadService)  /*inyecto dependencias*/
    {
        $this->novedadService = $novedadService;
    }

    public function index()
    {
        try{
            $novedades = $this->novedadService->getAll();
            return view('novedades.index', compact('novedades'));
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al obtener las novedades: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener las novedades.');
        }
    }

    public function store(Request $request)
    {
        try{
            $request->validate([
                'titulo' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            $this->novedadService->create($request->all());
    
            return redirect()->route('novedades.index')->with('success', 'Novedad creada con éxito');
        }catch(Exception $e)
        {
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al crear una novedad: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al crear la novedad.');
        }
       
    }

    public function show(int $id){
        try {
            $novedad = $this->novedadService->getById($id);
            return view('novedades.show', compact('novedad'));

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al mostrar las novedades: ' . $e->getMessage());
            return redirect()->route('novedades.index')->with('error', 'Novedad no encontrada.');
        }
    }

    public function delete(int $id){
        try {
            $novedad = $this->novedadService->getById($id);
            if($novedad){
                $elimninada = $this->novedadService->delete($novedad);
                return redirect()->route('novedades.index')->with('success', 'Novedad eliminada con exito.');
            }
            else{
                return redirect()->route('novedades.index')->with('error', 'Novedad no encontrada.');
            }
            
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error en el controlador al elimninar la novedad: ' . $e->getMessage());
            return redirect()->route('novedades.index')->with('error', 'Novedad no eliminada.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            
            $request->validate([
                'titulo' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $novedad = $this->novedadService->getById($id);
            $updated = $this->novedadService->update($novedad, $request->all());

            if ($updated) {
                return redirect()->route('novedades.index')->with('success', 'Novedad actualizada con éxito.');
            } else {
                return redirect()->back()->withErrors('Hubo un problema al actualizar la novedad.');
            }
        } catch (Exception $e) {
            Log::error('Error en la actualización de la novedad: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al actualizar la novedad.');
        }
    }
    
    public function edit($id)
    {
        $novedad = $this->novedadService->getById($id);
        return view('novedades.edit', compact('novedad'));
    }

}