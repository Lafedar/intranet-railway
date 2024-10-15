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
        
        
    }

    public function getAll(): Collection
    {
        return Novedad::orderBy('id', 'desc')->get();
    }

    public function getById($id): ?Novedad
    {
        return Novedad::find($id);
    }

    public function create(array $data): Novedad
    {
        $this->validateData($data);
        $imagenPaths = [];
        if (isset($data['imagenes'])) {
            foreach ($data['imagenes'] as $imagen) {
                $path = $imagen->store('images', 'public');
                $imagenPaths[] = $path;
            }
        }
        
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

    public function getUltimasNovedades()
    {
        // Recupera las últimas 4 novedades
        return Novedad::orderBy('id', 'desc')->take(4)->get();
    }
}

