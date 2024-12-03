<?php

namespace App\Models;
use App\Area;
use Empresa;
use DB;


//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiAgenda extends Model
{
    //use HasFactory;
    protected $table = 'mi_agenda';
    protected $primaryKey = 'id';

    
    public function scopeEmpresa($query, $empresa){
        if($empresa){
        return $query -> where('mi_agenda.empresa','LIKE',"%$empresa%");
        }
    }
    public function scopeNombre($query, $nombre){
        if($nombre){
            return $query -> where(DB::raw("CONCAT(nombre,' ',apellido)"), 'LIKE',"%$nombre%");
        }
    }
}
