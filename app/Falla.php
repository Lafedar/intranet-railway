<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Falla extends Model
{
    public $table = "fallas";
    public $timestamps = false;
    public function scopeIndex ($query)
    {
        return $query->select('fallas.id as id', 'fallas.nombre as nombre');
    }

}

