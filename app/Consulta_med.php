<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Consulta_med extends Model
{
    protected $table='consultas_med';

public function scopeBusca($query){

	return $query ->join('personas','consultas_med.paciente','personas.id_p')
            		->join('motivos_consultas','consultas_med.motivo', 'motivos_consultas.id')
            		->select('personas.nombre_p as nombre_paciente','personas.apellido as apellido_paciente','consultas_med.obs as obs', 'consultas_med.peso as peso','consultas_med.talla as talla','consultas_med.tension as tension','consultas_med.imc as imc', 'consultas_med.fecha as fecha', 'motivos_consultas.desc_motivo as motivo','personas.id_p as ip_paciente', 'consultas_med.id as id')
            ->orderBy('fecha','DESC');
}

public function scopePaciente($query, $paciente){
	if($paciente){
	return $query -> where(DB::raw("CONCAT(nombre_p,' ',apellido)"), 'LIKE',"%$paciente%");
	}
}

public function scopeFecha($query, $fecha){
	if($fecha != null){
	return $query -> where('consultas_med.fecha',$fecha);
	}
}

public function scopeReporte($query, $id){

	return $query ->join('personas','consultas_med.paciente','personas.id_p')
            		->join('motivos_consultas','consultas_med.motivo', 'motivos_consultas.id')
            		->where('personas.id_p',$id)
            		->select('consultas_med.obs as obs', 'consultas_med.peso as peso','consultas_med.talla as talla','consultas_med.tension as tension','consultas_med.imc as imc', 'consultas_med.fecha as fecha', 'motivos_consultas.desc_motivo as motivo','personas.id_p as ip_paciente')
            ->orderBy('fecha','DESC');
}

}
