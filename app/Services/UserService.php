<?php

namespace App\Services;

use App\User;
use DB;


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
    public function getByDni(int $dni){
        return User::where('dni', $dni)
        ->get();
    }
  
    

}
