<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Equipamiento extends Model
{
    protected $table='equipamientos';
    protected $primaryKey = 'id_e';

    public function scopeRelaciones($query, $tipo, $subred)
{
   
    $query = $query
        ->leftjoin('relaciones', function($join) {
            $join->on('equipamientos.id_e', 'relaciones.equipamiento')
                ->on('relaciones.estado', DB::raw("1")); // Solo toma los estados activos
        })
        ->leftjoin('ips', 'equipamientos.subred', 'ips.id')
        ->leftjoin('puestos', 'puestos.id_puesto', 'relaciones.puesto')
        ->leftjoin('personas', 'puestos.persona', 'personas.id_p')
        ->leftjoin('localizaciones', 'localizaciones.id', 'puestos.id_localizacion')
        ->leftjoin('area', 'area.id_a', 'localizaciones.id_area');

    

    if ($tipo != 0 && $subred == 0) {
        $query->where('tipo', $tipo);
    } elseif ($tipo == 0 && $subred != 0) {
        $query->where('subred', $subred);
    } elseif ($tipo != 0 && $subred != 0) {
        $query->where('subred', $subred)
              ->where('tipo', $tipo);
    }

    
    $query->orderBy('equipamientos.id_e', 'asc')
        ->select(
            'equipamientos.id_e as id_equipamiento',
            'equipamientos.activo', 
            'puestos.desc_puesto as puesto',
            'equipamientos.subred as subred',
            'equipamientos.ip as ip',
            'area.nombre_a as area',
            'personas.nombre_p as nombre',
            'personas.apellido as apellido',
            'relaciones.id_r as relacion',
            'equipamientos.obs as obs',
            'equipamientos.marca as marca',
            'equipamientos.modelo as modelo',
            'equipamientos.tipo as tipo',
            'equipamientos.num_serie as num_serie',
            'equipamientos.procesador as procesador',
            'equipamientos.disco as disco',
            'equipamientos.memoria as memoria',
            'equipamientos.pulgadas as pulgadas',
            'equipamientos.toner as toner',
            'equipamientos.unidad_imagen as unidad_imagen',
            'equipamientos.obs as obs',
            'equipamientos.oc as oc',
            'ips.nombre as nombre_subred',
            'localizaciones.nombre as localizacion'
        );

    return $query;
}


    public  function scopePuesto ($query, $puesto)
    {
    	if($puesto)
        {
    	    return $query -> where('desc_puesto','LIKE', "%$puesto%");
    	}
    }
  
    public  function scopeArea ($query, $area)
    {
    	if($area)
        {
    	    return $query ->where('nombre_a', 'LIKE', "%$area%");
    	}
    }
    public  function scopeIp ($query, $ip)
    {
    	if($ip)
        {
    	    return $query -> where('ip', 'LIKE', "%$ip%");
    	}
	}
    public  function scopeEquipo ($query, $equipo)
    {
    	if($equipo)
        {
    	    return $query -> where('id_e', 'LIKE', "%$equipo%");
    	}
    }
    public  function scopeUsuario ($query, $usuario)
    {
    	if($usuario)
        {
    	    return $query -> where(DB::raw("CONCAT(nombre_p,' ',apellido)"), 'LIKE',"%$usuario%");
    	}
    }

    
    public function index()
    {
        // Filtrar los equipos con IDs 1, 5 y 7
        $tipo_equipamiento = Equipamiento::whereIn('id', [2, 3])->get();

        // Pasar los datos a la vista
        return view('equipamiento.inicio', compact('tipo_equipamiento'));
    }

    public static function scopeListadoEquipamientos($query) //devuelve todos los ip
    {
        return $query->leftJoin('relaciones', function($join) {
                $join->on('equipamientos.id_e', '=', 'relaciones.equipamiento')
                    ->where('relaciones.estado', '=', 1);
            })
            ->leftJoin('puestos', 'puestos.id_puesto', '=', 'relaciones.puesto')
            ->leftJoin('personas', 'puestos.persona', '=', 'personas.id_p')
            ->leftJoin('localizaciones', 'localizaciones.id', '=', 'puestos.id_localizacion')
            ->leftJoin('area', 'area.id_a', '=', 'localizaciones.id_area')
            ->leftJoin('tipo_equipamiento', 'equipamientos.tipo', '=', 'tipo_equipamiento.id')
            ->leftJoin('ips', 'equipamientos.subred', '=', 'ips.id')
            ->whereNotNull('equipamientos.subred')
            ->select('equipamientos.id_e as id_equipamiento', 'equipamientos.ip as ip', 'ips.nombre as nombre_red', 'personas.nombre_p as nombre',
                    'personas.apellido as apellido', 'equipamientos.obs as obs', 'tipo_equipamiento.equipamiento as tipo')
            ->orderBy('equipamientos.ip', 'asc');
    }

    public static function obtenerListado($searchTerm = '')
    {
        $ipsPosibles = self::generarIpsPosibles();
        $equipamientos = self::listadoEquipamientos()->get();
        $ipsEnUso = $equipamientos->pluck('ip')->toArray();

        $listado = [];

        foreach ($ipsPosibles as $ip) {
            if (in_array($ip, $ipsEnUso)) {
                $equipamiento = $equipamientos->firstWhere('ip', $ip);
                $listado[] = [
                    'ip' => $ip,
                    'estado' => $equipamiento->nombre_red,
                    'id_equipamiento' => $equipamiento->id_equipamiento,
                    'tipo' => $equipamiento->tipo,
                    'nombre' => $equipamiento->nombre . " " . $equipamiento->apellido,
                    'obs' => $equipamiento->obs,
                    'nombre_red' => $equipamiento->nombre_red
                ];
            } else {
                $listado[] = [
                    'ip' => $ip,
                    'estado' => '',
                    'id_equipamiento' => 'Libre',
                    'tipo' => '',
                    'nombre' => '',
                    'apellido' => '',
                    'obs' => '',
                    'nombre_red' => ''
                ];
            }
        }

        
        if ($searchTerm) {//filtrar las ip
            $searchTerm = strtolower($searchTerm);
            $listado = array_filter($listado, function($item) use ($searchTerm) {
                return stripos(strtolower($item['ip']), $searchTerm) !== false ||
                       stripos(strtolower($item['nombre']), $searchTerm) !== false ||
                       stripos(strtolower($item['id_equipamiento']), $searchTerm) !== false ||
                       stripos(strtolower($item['tipo']), $searchTerm) !== false;
            });
        }

        return collect($listado);
    }

    public static function generarIpsPosibles()
    {
        $mascaras = ['10.41.20', '10.41.30', '10.41.40', '10.41.50', '10.41.60', '10.41.70'];
        $ipsPosibles = [];
    
        foreach ($mascaras as $mascara) {
            for ($i = 1; $i <= 254; $i++) {
                $ipsPosibles[] = "{$mascara}.{$i}";
            }
        }
    
        return $ipsPosibles;
    }

    
}


