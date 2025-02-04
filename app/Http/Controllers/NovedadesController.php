<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\NovedadService;
use App\Novedad;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Exception;
use Log;
use App\Models\Like;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;



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
            $novedades = Novedad::with('likes')->orderBy('id', 'desc')->get();


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
        try {
            $novedad = $this->novedadService->getById($id);

            $this->novedadService->update($request, $novedad);

            return redirect()->route('novedades.index')->with('success', 'Novedad actualizada correctamente.');
        } catch (Exception $e) {
            Log::error('Error en el controlador al actualizar la novedad: ' . $e->getMessage());
            return redirect()->route('novedades.index')->with('error', 'Novedad no actualizada.');
        }

    }

    public function edit(int $id)
    {
        try {
            $novedad = $this->novedadService->getById($id);
            return view('novedades.edit', compact('novedad'));
        } catch (Exception $e) {
            Log::error('Error en el controlador al abrir novedades.edit: ' . $e->getMessage());
            return redirect()->route('novedades.index')->with('error', 'novedades.edit no se pudo abrir.');
        }

    }

    public function like($id)
    {
        $novedad = Novedad::findOrFail($id);
        $cookieName = 'like_novedad_' . $novedad->id;

        // Obtener el user_id desde la cookie
        $userId = Cookie::get($cookieName);

        if ($userId) {
            return redirect()->back()->with('message', 'Ya diste like a esta novedad desde esta computadora.');
        }

        // Generar un nuevo identificador único y guardar el like
        $userId = uniqid('guest_', true);
        Like::create([
            'novedad_id' => $novedad->id,
            'user_id' => $userId,
        ]);

        // Guardar la cookie
        Cookie::queue($cookieName, $userId, 60 * 24 * 30);

        return redirect()->back()->with('message', 'Like registrado.');
    }

    public function unlike($id)
    {
        $novedad = Novedad::findOrFail($id);
        $cookieName = 'like_novedad_' . $novedad->id;


        $userId = Cookie::get($cookieName);

        if (!$userId) {
            return redirect()->back()->with('message', 'No has dado like a esta novedad.');
        }

        // Buscar el like y eliminarlo
        $like = Like::where('novedad_id', $novedad->id)
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            $like->delete();
            Cookie::queue(Cookie::forget($cookieName));
            return redirect()->back()->with('message', 'Like eliminado.');
        }

        return redirect()->back()->with('message', 'No has dado like a esta novedad.');
    }




}