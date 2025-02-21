<?php

namespace App\Services;

use App\Localizacion;
use Exception;
use Log;
use Illuminate\Database\Eloquent\Collection;

class LocalizacionService
{

    // En LocalizacionService.php
    public function getLocalizacionesFiltradas($search)
    {
       
        $query = Localizacion::query(); //prepara la consulta

        if ($search) {
            $query->where(function ($query) use ($search) {
                
                $query->where('nombre', 'like', '%' . $search . '%')
                    ->orWhereHas('area', function ($query) use ($search) {
                        $query->where('area.nombre_a', 'like', '%' . $search . '%');
                    });
            });
        }

        return $query->orderBy('nombre')
            ->paginate(20);
    }


}
