<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PublicDocumentation extends Model
{
    public $table = "documentacion_publica";
    protected $fillable = ['titulo','fecha','pdf'];

    
}
