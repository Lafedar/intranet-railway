<?php

namespace App\Services;

use App\Novedad;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class NovedadService
{
    private function validateData(array $data): void
    {
        if (empty($data['titulo'])) {
            throw new \InvalidArgumentException('El titulo de la novedad es obligatorio.');
        }
        
        if (empty($data['descripcion'])) {
            throw new \InvalidArgumentException('La descripción de la novedad es obligatoria.');
        }
        
    }

    public function getAll(): Collection
    {
        return Novedad::all();
    }

    public function getById($id): ?Novedad
    {
        return Novedad::find($id);
    }

    public function create(array $data): Novedad
    {
        $this->validateData($data);
        
        // Manejo de imágenes
        $imagenPaths = [];
        if (isset($data['imagenes'])) {
            foreach ($data['imagenes'] as $imagen) {
                $path = $imagen->store('images', 'public');
                $imagenPaths[] = $path;
            }
        }
        
        // Almacena las rutas como un string separado por comas
        $data['imagen'] = implode(',', $imagenPaths);
        
        return Novedad::create($data);
    }

    public function update(Novedad $novedad, array $data): bool
    {
        $this->validateData($data);
        return $novedad->update($data);
    }

    public function delete(Novedad $novedad): ?bool
    {
        return $novedad->delete();
    }
}

