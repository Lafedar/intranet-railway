<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroUser extends Model
{
    use HasFactory;

    protected $table = 'registros_users';
    protected $fillable = [
        'name',
        'email',
        'dni',
        'password',
        'remember_token',
        'remember_token_expires_at',
        'email_verified_at',
    ];
}
