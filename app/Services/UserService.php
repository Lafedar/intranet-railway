<?php

namespace App\Services;

use App\User;
use Exception;
use Log;
use Illuminate\Database\Eloquent\Collection;


class UserService
{

    public function createUser(string $nombre, string $apellido, string $correo, string $password): User
    {
        return User::create([
            'name' => $nombre . ' ' . $apellido,
            'email' => $correo,
            'password' => $password 
        ]);
    }

    

}
