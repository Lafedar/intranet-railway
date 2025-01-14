<?php

namespace App\Services;

use App\Puesto;
use Exception;
use Log;
use DB;
use Illuminate\Database\Eloquent\Collection;
use App\Localizacion;
use App\Area;
use Illuminate\Support\Facades\Session;

class PuestoService
{
    public function getPuestos($filters)
    {
        $puestos = Puesto::Relaciones();

        // Aplicar los filtros que se pasen como parámetros
        if (isset($filters['puesto'])) {
            $puestos = $puestos->Puesto($filters['puesto']);
        }

        if (isset($filters['usuario'])) {
            $puestos = $puestos->Usuario($filters['usuario']);
        }

        if (isset($filters['area'])) {
            $puestos = $puestos->Area($filters['area']);
        }

        if (isset($filters['localizacion'])) {
            $puestos = $puestos->Localizacion($filters['localizacion']);
        }

        return $puestos->orderBy('desc_puesto', 'asc')
            ->paginate(20)
            ->withQueryString();
    }
    public function getLocalizaciones()
    {

        try {
            return DB::table('localizaciones')->get();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener las localizaciones' . $e->getMessage());
            throw $e;
        }
    }


    public function getLocalizacionesByArea($areaId)
    {
        try {
            return Localizacion::where('id_area', $areaId)->get();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener las localizaciones por area' . $e->getMessage());
            throw $e;
        }

    }

    public function getAreaByLocalizacion($localizacionId)
    {
        try {
            $localizacion = Localizacion::find($localizacionId);

            if ($localizacion) {
                // Buscar el área asociada con la localización
                $area = Area::find($localizacion->id_area);
                return $area;
            }

            return null; // Devuelve null si no se encuentra la localización
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener las areas por localizacion' . $e->getMessage());
            throw $e;
        }

    }


    public function storePuesto($data)
    {
        try {
            $puesto = new Puesto;
            $puesto->desc_puesto = $data['desc_puesto'];
            $puesto->id_localizacion = $data['localizacion'];
            $puesto->persona = $data['persona'];
            $puesto->obs = $data['obs'];
            $puesto->telefono_ip = $data['telefono_ip'];
            $puesto->save();

            return $puesto;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al crear el puesto' . $e->getMessage());
            throw $e;
        }

    }

    public function getPuesto($id)
    {
        try {
            $puesto = Puesto::leftJoin('localizaciones', 'localizaciones.id', '=', 'puestos.id_localizacion')
                ->leftJoin('area', 'area.id_a', '=', 'localizaciones.id_area')
                ->select(
                    'puestos.id_puesto as idPuesto',
                    'puestos.desc_puesto as nombrePuesto',
                    'puestos.id_localizacion as idLocalizacion',
                    'localizaciones.id_area as idArea',
                    'puestos.persona as idPersona',
                    'puestos.obs as observaciones'
                )
                ->find($id);

            return $puesto;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener el puesto' . $e->getMessage());
            throw $e;
        }

    }


    public function destroyPuesto($id)
    {
        try {
            $activos = 1;
            $relaciones = DB::table('relaciones')
                ->where('relaciones.puesto', $id)
                ->where('relaciones.estado', $activos)
                ->first();

            // Si existen relaciones activas, no permitir la eliminación
            if ($relaciones) {
                Session::flash('message', 'No se puede eliminar este puesto ya que tiene equipos asignados');
                Session::flash('alert-class', 'alert-warning');
                return false;
            } else {
                // Si no hay relaciones activas, proceder con la eliminación del puesto
                $puesto = Puesto::find($id);
                if ($puesto) {
                    $puesto->delete();
                    Session::flash('message', 'Puesto eliminado con éxito');
                    Session::flash('alert-class', 'alert-success');
                    return true;
                }

                Session::flash('message', 'Puesto no encontrado');
                Session::flash('alert-class', 'alert-danger');
                return false;
            }
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al borrar el puesto' . $e->getMessage());
            throw $e;
        }


    }


    public function updatePuesto($data)
    {
        try {
            $puesto = Puesto::find($data['id_puesto']);

            if ($puesto) {
                $puesto->desc_puesto = $data['desc_puesto1'];
                $puesto->id_localizacion = $data['localizacion1'];
                $puesto->persona = $data['persona1'];
                $puesto->obs = $data['obs1'];
                $puesto->save();

                Session::flash('message', 'Puesto modificado con éxito');
                Session::flash('alert-class', 'alert-success');

                return true;
            }

            return false;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al actualizar el puesto' . $e->getMessage());
            throw $e;
        }

    }






}