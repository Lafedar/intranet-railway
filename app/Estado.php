<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Estado extends Model
{
    public $table = "estados";
    public $timestamps = false;
    public function scopeIndex ($query)
    {
        return $query->select('estados.id as id', 'estados.nombre as nombre');
    }

}

