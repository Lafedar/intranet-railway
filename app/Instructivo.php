<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Instructivo extends Model{
    public $table = "instructivo";
    public function scopeID($query, $id_instructivo){
        if($id_instructivo){
            return $query -> where('instructivo.id','LIKE',"%$id_instructivo%");
        }
    }
    public function scopeTitulo ($query, $titulo){
    	if($titulo){
    	    return $query -> where('titulo','LIKE', "%$titulo%");
    	}
    }
    public function scopeRelaciones_index($query, $id_tipo_instructivo){
        $query->leftJoin('tipo_instructivos', 'tipo_instructivos.id', 'instructivo.tipo')
            ->select('instructivo.id as id',
            'instructivo.titulo as titulo',
            'instructivo.archivo as archivo',
            'tipo_instructivos.nombre as tipo');

        if ($id_tipo_instructivo != 0) {
            $query->where('tipo', $id_tipo_instructivo);
        }

        return $query;
    }
    public static function getTiposDeInstructivos(){
        return DB::table('tipo_instructivos')->get();
    }
    public static function showInstructivoUpdate($id) {
        $instructivo = Instructivo::leftjoin('tipo_instructivos', 'tipo_instructivos.id', 'instructivo.tipo')
            ->select('instructivo.id as id')
            ->find($id);
        return $instructivo;
    }
}
