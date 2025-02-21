<?php

namespace App\Services;

use App\Models\Location;
use Exception;
use Log;
use Illuminate\Database\Eloquent\Collection;
use DB;

class LocationService
{

    // En LocalizacionService.php
    public function getFilteredLocations(?string $search)
    {
        try{
            $query = Location::query(); //prepara la consulta

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
            Log::error('Error in class: ' . get_class($this) . ' .Error getting filtered locations ' . $e->getMessage());
            return null;
        }
      
    }


    public function store(?int $id, string $area, ?string $name, ?int $internal)
    {
        try{
            
            $aux = DB::table('localizaciones')->where('id', $id)->first();

            if ($aux) {
                return false;
            }
    
            $location = new Location();
            $location->id_area = $area;
            $location->nombre = $name;
            $location->interno = $internal;
    
            $location->save();
    
            return true;
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error creating location ' . $e->getMessage());
            return false;
        }
       
    }

    public function show_update(int $id_a)
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
            Log::error('Error in class: ' . get_class($this) . ' .Error when obtaining location by ID ' . $e->getMessage());
            return null;
        }
        
    }


    public function update(?int $id, ?string $name, ?int $internal)
    {
        try {
            
            $updated = DB::table('localizaciones')
                ->where('localizaciones.id', $id)
                ->update([
                    'nombre' => $name,
                    'interno' => $internal,
                ]);
    
           
            if ($updated) {
                return true;  
            } else {
                return false; 
            }
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating location' . $e->getMessage());
            return false;
        }
    }
    
    



}
