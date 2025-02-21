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
                        ->orWhere('interno', 'like', '%' . $search . '%')
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


    public function store($data)
{
    try {
        $aux = DB::table('localizaciones')->where('id', $data['id'])->first();

        if ($aux) {
            return false;
        }

        Location::create([
            'id_area' => $data['area'],
            'nombre' => $data['name'],
            'interno' => $data['internal']
        ]);

        return true;

    } catch (Exception $e) {
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


    public function update($data)
    {
        try {
           
            $updated = DB::table('localizaciones')
                ->where('localizaciones.id', $data['id'])
                ->update([
                    'nombre' => $data['name'],
                    'interno' => $data['internal'],
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

    public function get_all_locations(){
        try{
            return Location::all();
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error getting all locations ' . $e->getMessage());
            return null;
        }
    }
    
    



}
