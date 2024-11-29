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
        try {
            $novedades = $this->novedadService->getAll();
            return view('novedades.index', compact('novedades'));
        } catch (Exception $e) {
            Log::error('Error en el controlador al obtener las novedades: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al obtener las novedades.');
        }
    }

    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'titulo' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $this->novedadService->create($request->all());

            return redirect()->route('novedades.index')->with('success', 'Novedad creada con éxito');
        } catch (Exception $e) {
            Log::error('Error al crear una novedad: ' . $e->getMessage());
            return redirect()->back()->withErrors($e->getMessage()); 
        }
    }

    
    public function show(int $id)
    {
        try {
            $novedad = $this->novedadService->getById($id);

            if (!$novedad) {
                return redirect()->route('novedades.index')->withErrors('Novedad no encontrada.');
            }

            return view('novedades.show', compact('novedad'));
        } catch (Exception $e) {
            Log::error('Error al mostrar la novedad: ' . $e->getMessage());
            return redirect()->route('novedades.index')->withErrors('Hubo un problema al cargar la novedad.');
        }
    }
    public function delete(int $id)
    {
        try {
            $novedad = $this->novedadService->getById($id);
            if ($novedad) {
                $this->novedadService->delete($novedad);
                return redirect()->route('novedades.index')->with('success', 'Novedad eliminada con éxito.');
            } else {
                return redirect()->route('novedades.index')->with('error', 'Novedad no encontrada.');
            }
        } catch (Exception $e) {
            Log::error('Error en el controlador al eliminar la novedad: ' . $e->getMessage());
            return redirect()->route('novedades.index')->with('error', 'Novedad no eliminada.');
        }
    }

    
    public function update(Request $request, int $id)
    {
        try{
            $novedad = $this->novedadService->getById($id);
        
            $this->novedadService->update($request, $novedad);
    
            return redirect()->route('novedades.index')->with('success', 'Novedad actualizada correctamente.');
        }
        catch(Exception $e)
        {
            Log::error('Error en el controlador al actualizar la novedad: ' . $e->getMessage());
            return redirect()->route('novedades.index')->with('error', 'Novedad no actualizada.');
        }
       
    }

    public function edit(int $id)
    {
        try{ 
            $novedad = $this->novedadService->getById($id);
            return view('novedades.edit', compact('novedad'));
        }catch(Exception $e)
        {
            Log::error('Error en el controlador al abrir novedades.edit: ' . $e->getMessage());
            return redirect()->route('novedades.index')->with('error', 'novedades.edit no se pudo abrir.');
        }
       
    }
}