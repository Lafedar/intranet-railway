<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Externo extends Model
{
    protected $table='externos';

    protected $primaryKey = 'dni';


    public function scopeBusca($query){
        return $query->leftjoin('empresas', 'empresas.id_emp', 'externos.empresa_ext')
        ->orderBy('apellido_ext', 'ASC');
    }

}
