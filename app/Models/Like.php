<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Novedad;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'novedad_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function novedad()
{
    return $this->belongsTo(Novedad::class); 
}
}
