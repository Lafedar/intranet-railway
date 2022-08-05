<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historia_clinica extends Model
{
      protected $table='historia_clinica';

	public function scopeReporte($query, $id){

	return $query ->join('personas','historia_clinica.paciente','personas.id_p')
					->join('educacion','historia_clinica.educacion','educacion.id')
            		->where('personas.id_p',$id)
            		->select('personas.nombre_p as nombre_paciente','personas.apellido as apellido_paciente','historia_clinica.grupo_sang as grupo_sang','historia_clinica.educacion', 'historia_clinica.tabaco as tabaco', 'historia_clinica.alcohol as alcohol', 'historia_clinica.droga as droga', 'historia_clinica.act_fisica as act_fisica','historia_clinica.desc_act_fisica as desc_act_fisica','historia_clinica.ant_per as ant_per','historia_clinica.ant_fam as ant_fam','historia_clinica.ant_quir as ant_quir', 'historia_clinica.obs as obs', 'educacion.desc_edu as educacion' );
}

}
