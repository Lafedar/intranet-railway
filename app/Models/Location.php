<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Area;

class Location extends Model
{
    public $table = "localizaciones";
    public $timestamps = false;

    protected $fillable = ['id', 'nombre', 'id_area', 'interno'];
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }
}