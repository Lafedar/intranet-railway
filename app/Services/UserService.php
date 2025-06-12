<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Hash;


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
    public function getByDni(int $dni)
    {
        return User::where('dni', $dni)
            ->get();
    }

    public function validate($email, $password)
    {
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            return $user;
        } else {
            return null;
        }
    }



}