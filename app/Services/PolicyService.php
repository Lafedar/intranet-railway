<?php

namespace App\Services;

use App\Models\Policy;
use Illuminate\Support\Facades\Storage;
use DB;
use Exception;
use Log;
use Carbon\Carbon;

class PolicyService
{
    public function storePolicy($request)
    {
        try {
            $aux = Policy::get()->max('id');
            if ($aux == null) {
                $aux = 0;
            }

            $policy = new Policy;
            $policy->titulo = $request->input('title');
            $policy->fecha = $request->input('date');
           
            if ($request->file('pdf')) {
                $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT) . $request->file('pdf')->getClientOriginalName();
                Storage::disk('public')->put('politica/' . $name, \File::get($request->file('pdf')));
                $policy->pdf = 'politica\\' . $name;
            }

            $policy->save();

            return true;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error creating policy' . $e->getMessage());
            return false;

        }

    }

    public function destroyPolicy(int $id)
    {
        try {
            $policy = Policy::find($id);

            if ($policy && $policy->pdf) {
                $pdfPath = 'public/' . $policy->pdf;
                if (Storage::exists($pdfPath)) {
                    Storage::delete($pdfPath);
                }
            }

            if ($policy) {
                $policy->delete();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error deleting policy' . $e->getMessage());
            return false;
        }

    }

    public function updatePolicy(int $id, string $title, $date, $pdf)
    {
        try {
            $updateData = [];
            
            if ($title) {
                $updateData['titulo'] = $title;
            }
            
            if ($date) {
                $updateData['fecha'] = $date;
            }

            // Solo actualizamos el título o fecha si hay algo que actualizar
            if (!empty($updateData)) {
                DB::table('politica')->where('id', $id)->update($updateData);
            }
    
            // Si no hay archivo PDF, simplemente retornamos true si se actualizó algún campo
            if (!$pdf) {
                return !empty($updateData);  
            }
    
            // Si hay archivo PDF, procedemos a actualizarlo
            $aux = Policy::find($id);
    
            // Eliminar el archivo antiguo si existe
            if ($aux->pdf && Storage::exists('public/' . $aux->pdf)) {
                Storage::delete('public/' . $aux->pdf);
            }
    
            // Subir el nuevo archivo PDF
            $name = str_pad($id, 5, '0', STR_PAD_LEFT) . $pdf->getClientOriginalName();
            Storage::disk('public')->put('politica/' . $name, \File::get($pdf));
    
            // Actualizar el campo 'pdf' en la base de datos
            $policy = Policy::find($id);

            if ($policy) {
                $policy->pdf = 'politica/' . $name;
                $policy->save();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error modifying the policy' . $e->getMessage());
            return false;
        }
    }
    

    public function get_paginated_policies(){
        try{
            return Policy::query();
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error getting policies paginated' . $e->getMessage());
            return null;
        }
        
    }
}
