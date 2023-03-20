<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Area extends Model
{
    public $table = "area";
    public $timestamps = false;
    public function scopeIndex ($query)
    {
        return $query->select('area.id_a as id_a', 'area.nombre_a as nombre_a');
    }

}

