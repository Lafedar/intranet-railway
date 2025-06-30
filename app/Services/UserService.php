<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use Log;
use DB;
use Illuminate\Support\Str;
use App\Models\RegistroUser;
use Illuminate\Support\Carbon;


class UserService
{

    public function createUser(string $nombre, string $apellido, string $correo, string $password)
    {
        /*validar si ya existe el usuario*/
        try {
            return User::on('mysql_write')->create([
                'name' => $nombre . ' ' . $apellido,
                'email' => $correo,
                'password' => $password
            ]);

        } catch (Exception $e) {
            Log::error("Error in class: " . get_class($e) . " Error: " . $e->getMessage());

        }

    }
    public function createUserApi(int $dni, $name, string $correo, string $password)
    {
        /*validar si ya existe el usuario*/
        try {
            return User::on('mysql_write')->create([
                'dni' => $dni,
                'name' => $name,
                'email' => $correo,
                'password' => $password,
                'remember_token' => null,
                'email_verified_at' => 1,
            ]);

        } catch (Exception $e) {
            Log::error("Error in class: " . get_class($e) . " Error: " . $e->getMessage());

        }

    }

    public function createRegisterUserApi(int $dni, string $nombre, string $apellido, string $correo, string $password)
    {
        try {
            return RegistroUser::on('mysql_write')->create([
                'dni' => $dni,
                'name' => $nombre . ' ' . $apellido,
                'email' => $correo,
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
                'remember_token_expires_at' => now()->addDay(),
                'email_verified_at' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        } catch (Exception $e) {
            Log::error("Error in class: " . get_class($e) . " Error: " . $e->getMessage());

        }

    }
    public function getByDni(int $dni)
    {
        return User::on('mysql_read')->where('dni', $dni)
            ->first();
    }
    public function getByDniWrite(int $dni)
    {
        return User::on('mysql_write')->where('dni', $dni)
            ->first();
    }

    public function validate($email, $password)
    {
        $user = User::on('mysql_read')->where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        } else {
            return null;
        }

    }

    public function validateMail($email)
    {
        try {
            if (User::on('mysql_read')->where('email', $email)->exists()) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            return false;
        }

    }

    public function createNewToken($dni)
    {
        $token = Str::random(60);
        $user = RegistroUser::on('mysql_write')->where('dni', $dni)->first();

        if (!$user) {
            throw new Exception("Usuario no encontrado para el DNI $dni");
        }

        $user->remember_token = $token;
        $user->remember_token_expires_at = now()->addDay();
        $user->save();

        return $user->remember_token;
    }


    public function createNewTokenUser($dni)
    {
        $token = Str::random(60);
        $user = User::on('mysql_write')->where('dni', $dni)->first();

        if (!$user) {
            throw new Exception("Usuario no encontrado para el DNI $dni");
        }
        if ($user->activo == 0) {
            throw new Exception("El usuario no está activo");
        }

        $user->remember_token = $token;
        $user->remember_token_expires_at = now()->addDay();
        $user->save();

        return $user->remember_token;
    }

    public function resetPassword($dni, $password)
    {
        try {
            $user = User::on('mysql_write')->where('dni', $dni)->first();
            if (!$user) {
                throw new Exception("Usuario no encontrado para el DNI $dni");
            }
            if ($user->activo == 0) {
                throw new Exception("El usuario no está activo");
            }
            $user->password = Hash::make($password);
            $user->remember_token = null;
            $user->remember_token_expires_at = null;
            $user->save();

            return $user;
        } catch (Exception $e) {
            Log::error("Error in class: " . get_class($e) . " Error: " . $e->getMessage());
            return false;
        }
    }

    public function cleanTokens($dni)
    {
        try {
            $user = User::on('mysql_write')->where('dni', $dni)->first();
            if (!$user) {
                throw new Exception("Usuario no encontrado para el DNI $dni");
            }
            $user->remember_token = null;
            $user->remember_token_expires_at = null;
            $user->save();
            return true;
        } catch (Exception $e) {
            Log::error("Error in class: " . get_class($e) . " Error: " . $e->getMessage());
            return false;
        }
    }




}