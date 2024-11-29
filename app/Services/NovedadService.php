<?php

namespace App\Services;

use App\Novedad;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
class NovedadService
{
    private function validateData(array $data): void
    {
        if (empty($data['titulo'])) {
            throw new \InvalidArgumentException('El titulo de la novedad es obligatorio.');
        }
        
    }

    public function getAll(): Collection
    {
        return Novedad::orderBy('id', 'desc')->get();
    }

    public function getById(int $id): ?Novedad
    {
        return Novedad::find($id);
    }

    
    public function create(array $data): Novedad
    {
        $this->validateData($data);
        $imagenPaths = [];

        // Manejar imagen principal
        if (isset($data['imagen_principal'])) {
            $principalPath = $data['imagen_principal']->store('images', 'public');
            $imagenPaths[] = $principalPath; 
            $data['portada'] = $principalPath; 
        } else {
            
            $data['portada'] = null; 
        }

        // Manejar imágenes secundarias
        if (isset($data['imagenes'])) {
            foreach ($data['imagenes'] as $imagen) {
                $path = $imagen->store('images', 'public');
                $imagenPaths[] = $path;
            }
            $data['imagenes_sec'] = implode(',', $imagenPaths); 
        }

        
        return Novedad::create($data);
    }
    


    public function update(Request $request, Novedad $novedad): Novedad
    {
        // Validar los datos
        $request->validate([
            'titulo' => 'required|max:100',
            'descripcion' => 'required|max:65530',
            'nueva_imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'imagenes.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'delete_images' => 'nullable|array', //valido que sea un array si se envía
            'delete_images.*' => 'string' //valido que cada item sea un string
        ]);
    
        $novedad->titulo = $request->titulo;
        $novedad->descripcion = $request->descripcion;
    
        // Manejar la nueva portada
        if ($request->hasFile('nueva_imagen')) {
            //elimino la imagen anterior si existe
            if ($novedad->portada) {
                Storage::disk('public')->delete($novedad->portada);
            }
            
            $path = $request->file('nueva_imagen')->store('portadas', 'public');
            $novedad->portada = $path; // Actualizar la portada
        }
    
        // Manejar la eliminación de imágenes secundarias
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imagen) {
                Storage::disk('public')->delete($imagen); 
            }
        }
    
        $imagenesSecundarias = [];
    
        // Agregar imágenes secundarias existentes, excepto la portada actual
        if ($novedad->imagenes_sec) {
            $imagenesExistentes = explode(',', $novedad->imagenes_sec);
            foreach ($imagenesExistentes as $imagen) {
                if ($imagen !== $novedad->portada && (!in_array($imagen, $request->delete_images ?? []))) {
                    $imagenesSecundarias[] = $imagen; // Solo agregar imágenes que no han sido eliminadas
                }
            }
        }
    
        // Agregar nuevas imágenes secundarias
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('imagenes_secundarias', 'public');
                $imagenesSecundarias[] = $path; // Agregar las nuevas imágenes secundarias
            }
        }
    
        $novedad->imagenes_sec = implode(',', $imagenesSecundarias);
        $novedad->save();
    
        return $novedad;
    }
public function delete(Novedad $novedad): ?bool
{
    return $novedad->delete();
}

public function getUltimasNovedades()
{
    // Recupera las últimas 4 novedades
    return Novedad::orderBy('id', 'desc')->take(4)->get();
}

}


