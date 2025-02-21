<?php

namespace App\Services;

use App\Localizacion;
use Exception;
use Log;
use Illuminate\Database\Eloquent\Collection;
use DB;

class LocalizacionService
{

    // En LocalizacionService.php
    public function getLocalizacionesFiltradas(?string $search)
    {
        try{
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
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener las localizaciones filtradas ' . $e->getMessage());
            return null;
        }
      
    }


    public function storeLocalizacion(?int $id, string $area, string $nombre, ?int $interno)
    {
        try{
            
            $aux = DB::table('localizaciones')->where('id', $id)->first();

            if ($aux) {
                return false;
            }
    
            $localizacion = new Localizacion();
            $localizacion->id_area = $area;
            $localizacion->nombre = $nombre;
            $localizacion->interno = $interno;
    
            $localizacion->save();
    
            return true;
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al crear la localizacion ' . $e->getMessage());
            return false;
        }
       
    }

    public function show_update_loc(int $id_a)
    {
        try{
            return DB::table('localizaciones')
            ->leftjoin('area', 'area.id_a', 'localizaciones.id_area')
            ->select('localizaciones.id as id', 'localizaciones.nombre as nombre', 'area.nombre_a', 'localizaciones.interno as interno')
            ->where('localizaciones.id', $id_a)
            ->first();

        }
        catch(Exception $e)
        {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener la localizacion por Id ' . $e->getMessage());
            return null;
        }
        
    }


    public function update(?int $id, string $nombre, ?int $interno)
    {
        try {
            
            $updated = DB::table('localizaciones')
                ->where('localizaciones.id', $id)
                ->update([
                    'nombre' => $nombre,
                    'interno' => $interno,
                ]);
    
           
            if ($updated) {
                return true;  
            } else {
                return false; 
            }
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al actualizar la localizacion' . $e->getMessage());
            return false;
        }
    }
    
    



}
