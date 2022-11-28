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
    	if($tipo == 0 and $subred == 0)
        {
            return  $query -> leftjoin('relaciones', function($join)
            {
                $join->on('equipamientos.id_e','relaciones.equipamiento');
                $join->on('relaciones.estado',DB::raw("1"));
            })
            ->leftjoin('ips','equipamientos.subred','ips.id')
            ->leftjoin('puestos','puestos.id_puesto','relaciones.puesto')
            ->leftjoin('personas','puestos.persona','personas.id_p')
            ->leftjoin('area','puestos.area','area.id_a')
            ->orderBy('id_equipamiento','asc')
            ->select('equipamientos.id_e as id_equipamiento','puestos.desc_puesto as puesto' ,'equipamientos.subred as subred',
            'equipamientos.ip as ip','area.nombre_a as area', 'personas.nombre_p as nombre', 'personas.apellido as apellido', 'relaciones.id_r as relacion',
            'equipamientos.obs as obs','equipamientos.marca as marca','equipamientos.modelo as modelo','equipamientos.tipo as tipo',
            'equipamientos.num_serie as num_serie','equipamientos.procesador as procesador','equipamientos.disco as disco','equipamientos.memoria as memoria',
            'equipamientos.pulgadas as pulgadas','equipamientos.toner as toner', 'equipamientos.unidad_imagen as unidad_imagen','equipamientos.obs as obs',
            'equipamientos.oc as oc', 'ips.nombre as nombre_subred');
        }
        else if($tipo != 0 and $subred == 0)
        {
            return  $query -> leftjoin('relaciones', function($join)
            {
                $join->on('equipamientos.id_e','relaciones.equipamiento');
                $join->on('relaciones.estado',DB::raw("1"));
            })
            ->leftjoin('ips','ips.id','equipamientos.subred')
            ->leftjoin('puestos','puestos.id_puesto','relaciones.puesto')
            ->leftjoin('personas','puestos.persona','personas.id_p')
            ->leftjoin('area','puestos.area','area.id_a')
            ->where('tipo',$tipo)
            ->orderBy('id_equipamiento','asc')
            ->select('equipamientos.id_e as id_equipamiento','puestos.desc_puesto as puesto', 'equipamientos.subred as subred',
            'equipamientos.ip as ip','area.nombre_a as area', 'personas.nombre_p as nombre', 'personas.apellido as apellido', 'relaciones.id_r as relacion',
            'equipamientos.obs as obs','equipamientos.marca as marca','equipamientos.modelo as modelo','equipamientos.tipo as tipo',
            'equipamientos.num_serie as num_serie','equipamientos.procesador as procesador','equipamientos.disco as disco','equipamientos.memoria as memoria',
            'equipamientos.pulgadas as pulgadas','equipamientos.toner as toner', 'equipamientos.unidad_imagen as unidad_imagen','equipamientos.obs as obs',
            'equipamientos.oc as oc', 'ips.nombre as nombre_subred');
        }
        else if($tipo == 0 and $subred != 0)
        {
            return  $query -> leftjoin('relaciones', function($join)
            {
                $join->on('equipamientos.id_e','relaciones.equipamiento');
                $join->on('relaciones.estado',DB::raw("1"));
            })
            ->leftjoin('ips','ips.id','equipamientos.subred')
            ->leftjoin('puestos','puestos.id_puesto','relaciones.puesto')
            ->leftjoin('personas','puestos.persona','personas.id_p')
            ->leftjoin('area','puestos.area','area.id_a')
            ->where('subred',$subred)
            ->orderBy('id_equipamiento','asc')
            ->select('equipamientos.id_e as id_equipamiento','puestos.desc_puesto as puesto', 'equipamientos.subred as subred',
            'equipamientos.ip as ip','area.nombre_a as area', 'personas.nombre_p as nombre', 'personas.apellido as apellido', 'relaciones.id_r as relacion',
            'equipamientos.obs as obs','equipamientos.marca as marca','equipamientos.modelo as modelo','equipamientos.tipo as tipo',
            'equipamientos.num_serie as num_serie','equipamientos.procesador as procesador','equipamientos.disco as disco','equipamientos.memoria as memoria',
            'equipamientos.pulgadas as pulgadas','equipamientos.toner as toner', 'equipamientos.unidad_imagen as unidad_imagen','equipamientos.obs as obs',
            'equipamientos.oc as oc', 'ips.nombre as nombre_subred');
        }
        else if($tipo != 0 and $subred != 0)
        {
            return  $query -> leftjoin('relaciones', function($join)
            {
                $join->on('equipamientos.id_e','relaciones.equipamiento');
                $join->on('relaciones.estado',DB::raw("1"));
            })
            ->leftjoin('ips','ips.id','equipamientos.subred')
            ->leftjoin('puestos','puestos.id_puesto','relaciones.puesto')
            ->leftjoin('personas','puestos.persona','personas.id_p')
            ->leftjoin('area','puestos.area','area.id_a')
            ->where('subred',$subred)
            ->where('tipo',$tipo)
            ->orderBy('id_equipamiento','asc')
            ->select('equipamientos.id_e as id_equipamiento','puestos.desc_puesto as puesto', 'equipamientos.subred as subred',
            'equipamientos.ip as ip','area.nombre_a as area', 'personas.nombre_p as nombre', 'personas.apellido as apellido', 'relaciones.id_r as relacion',
            'equipamientos.obs as obs','equipamientos.marca as marca','equipamientos.modelo as modelo','equipamientos.tipo as tipo',
            'equipamientos.num_serie as num_serie','equipamientos.procesador as procesador','equipamientos.disco as disco','equipamientos.memoria as memoria',
            'equipamientos.pulgadas as pulgadas','equipamientos.toner as toner', 'equipamientos.unidad_imagen as unidad_imagen','equipamientos.obs as obs',
            'equipamientos.oc as oc', 'ips.nombre as nombre_subred');
        }
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
    public function scopteSubred ($query, $subred)
    {
        if($subred)
        {
            return $query -> where('nombre', 'LIKE', "%$subred");
        }
    }

    public function scopeListadoIpLan($query, $i)
    {
    	return  $query -> leftjoin('relaciones', function($join)
        {
            $join->on('equipamientos.id_e','relaciones.equipamiento');
            $join->on('relaciones.estado',DB::raw("1"));
        })
        ->leftjoin('puestos','puestos.id_puesto','relaciones.puesto')
        ->leftjoin('personas','puestos.persona','personas.id_p')
        ->leftjoin('area','puestos.area','area.id_a')
        ->leftjoin('tipo_equipamiento','equipamientos.tipo','tipo_equipamiento.id')
        ->leftjoin('ips', 'equipamientos.subred', 'ips.id')
        ->where('ip','10.41.20.'.$i)
        ->select('equipamientos.id_e as id_equipamiento', 'equipamientos.ip as ip', 'ips.nombre as nombre_red','personas.nombre_p as nombre', 
        'personas.apellido as apellido', 'equipamientos.obs as obs', 'tipo_equipamiento.equipamiento as tipo');
    }
    public function scopeListadoIpPLC($query, $i)
    {
    	return  $query -> leftjoin('relaciones', function($join)
        {
            $join->on('equipamientos.id_e','relaciones.equipamiento');
            $join->on('relaciones.estado',DB::raw("1"));
        })
        ->leftjoin('puestos','puestos.id_puesto','relaciones.puesto')
        ->leftjoin('personas','puestos.persona','personas.id_p')
        ->leftjoin('area','puestos.area','area.id_a')
        ->leftjoin('tipo_equipamiento','equipamientos.tipo','tipo_equipamiento.id')
        ->leftjoin('ips', 'equipamientos.subred', 'ips.id')
        ->where('ip','10.41.30.'.$i)
        ->select('equipamientos.id_e as id_equipamiento', 'equipamientos.ip as ip', 'ips.nombre as nombre_red','personas.nombre_p as nombre', 
        'personas.apellido as apellido', 'equipamientos.obs as obs', 'tipo_equipamiento.equipamiento as tipo');
    }
    public function scopeListadoIpImp($query, $i)
    {
    	return  $query -> leftjoin('relaciones', function($join)
        {
            $join->on('equipamientos.id_e','relaciones.equipamiento');
            $join->on('relaciones.estado',DB::raw("1"));
        })
        ->leftjoin('puestos','puestos.id_puesto','relaciones.puesto')
        ->leftjoin('personas','puestos.persona','personas.id_p')
        ->leftjoin('area','puestos.area','area.id_a')
        ->leftjoin('tipo_equipamiento','equipamientos.tipo','tipo_equipamiento.id')
        ->leftjoin('ips', 'equipamientos.subred', 'ips.id')
        ->where('ip','10.41.40.'.$i)
        ->select('equipamientos.id_e as id_equipamiento', 'equipamientos.ip as ip', 'ips.nombre as nombre_red','personas.nombre_p as nombre', 
        'personas.apellido as apellido', 'equipamientos.obs as obs', 'tipo_equipamiento.equipamiento as tipo');
    }
    public function scopeListadoIpWifiInv($query, $i)
    {
    	return  $query -> leftjoin('relaciones', function($join)
        {
            $join->on('equipamientos.id_e','relaciones.equipamiento');
            $join->on('relaciones.estado',DB::raw("1"));
        })
        ->leftjoin('puestos','puestos.id_puesto','relaciones.puesto')
        ->leftjoin('personas','puestos.persona','personas.id_p')
        ->leftjoin('area','puestos.area','area.id_a')
        ->leftjoin('tipo_equipamiento','equipamientos.tipo','tipo_equipamiento.id')
        ->leftjoin('ips', 'equipamientos.subred', 'ips.id')
        ->where('ip','10.41.50.'.$i)
        ->select('equipamientos.id_e as id_equipamiento', 'equipamientos.ip as ip', 'ips.nombre as nombre_red','personas.nombre_p as nombre', 
        'personas.apellido as apellido', 'equipamientos.obs as obs', 'tipo_equipamiento.equipamiento as tipo');
    }
    public function scopeListadoIpWifiInt($query, $i)
    {
    	return  $query -> leftjoin('relaciones', function($join)
        {
            $join->on('equipamientos.id_e','relaciones.equipamiento');
            $join->on('relaciones.estado',DB::raw("1"));
        })
        ->leftjoin('puestos','puestos.id_puesto','relaciones.puesto')
        ->leftjoin('personas','puestos.persona','personas.id_p')
        ->leftjoin('area','puestos.area','area.id_a')
        ->leftjoin('tipo_equipamiento','equipamientos.tipo','tipo_equipamiento.id')
        ->leftjoin('ips', 'equipamientos.subred', 'ips.id')
        ->where('ip','10.41.60.'.$i)
        ->select('equipamientos.id_e as id_equipamiento', 'equipamientos.ip as ip', 'ips.nombre as nombre_red','personas.nombre_p as nombre', 
        'personas.apellido as apellido', 'equipamientos.obs as obs', 'tipo_equipamiento.equipamiento as tipo');
    }
    public function scopeListadoIpMant($query, $i)
    {
    	return  $query -> leftjoin('relaciones', function($join)
        {
            $join->on('equipamientos.id_e','relaciones.equipamiento');
            $join->on('relaciones.estado',DB::raw("1"));
        })
        ->leftjoin('puestos','puestos.id_puesto','relaciones.puesto')
        ->leftjoin('personas','puestos.persona','personas.id_p')
        ->leftjoin('area','puestos.area','area.id_a')
        ->leftjoin('tipo_equipamiento','equipamientos.tipo','tipo_equipamiento.id')
        ->leftjoin('ips', 'equipamientos.subred', 'ips.id')
        ->where('ip','10.41.70.'.$i)
        ->select('equipamientos.id_e as id_equipamiento', 'equipamientos.ip as ip', 'ips.nombre as nombre_red','personas.nombre_p as nombre', 
        'personas.apellido as apellido', 'equipamientos.obs as obs', 'tipo_equipamiento.equipamiento as tipo');
    }
    public function scopeListadoIpWan($query, $i)
    {
    	return  $query -> leftjoin('relaciones', function($join)
        {
            $join->on('equipamientos.id_e','relaciones.equipamiento');
            $join->on('relaciones.estado',DB::raw("1"));
        })
        ->leftjoin('puestos','puestos.id_puesto','relaciones.puesto')
        ->leftjoin('personas','puestos.persona','personas.id_p')
        ->leftjoin('area','puestos.area','area.id_a')
        ->leftjoin('tipo_equipamiento','equipamientos.tipo','tipo_equipamiento.id')
        ->leftjoin('ips', 'equipamientos.subred', 'ips.id')
        ->Where('ip','181.30.186.'.$i)
        ->select('equipamientos.id_e as id_equipamiento', 'equipamientos.ip as ip', 'ips.nombre as nombre_red', 'personas.nombre_p as nombre', 
        'personas.apellido as apellido', 'equipamientos.obs as obs', 'tipo_equipamiento.equipamiento as tipo');
    }
}


