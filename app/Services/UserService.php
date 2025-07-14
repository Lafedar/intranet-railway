<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\RegistroUser;
use Illuminate\Support\Carbon;


class UserService
{
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
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error creating a new user: ' . $e->getMessage());

        }

    }
    public function validateUserExists($dni, $email)
    {
        try {
            $exists = User::on('mysql_read')
                ->where('dni', $dni)
                ->orWhere('email', $email)
                ->exists();

            return $exists;

        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error validating if user exists: ' . $e->getMessage());
            return false;
        }
    }

    public function createRegisterUserApi(int $dni, string $nombre, string $apellido, string $correo, string $password)
    {
        try {
            $data = [
                'name' => $nombre . ' ' . $apellido,
                'email' => $correo,
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
                'remember_token_expires_at' => now()->addDay(),
                'email_verified_at' => 0,
                'updated_at' => now(),
            ];

            $registerUser = RegistroUser::on('mysql_write')->updateOrCreate(
                ['dni' => $dni],
                array_merge($data, ['created_at' => now()])
            );

            return $registerUser;

        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error creating register user: ' . $e->getMessage());
            return false;
        }
    }

    public function getByDni(int $dni)
    {
        try {
            return User::on('mysql_read')->where('dni', $dni)
                ->first();
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error getting user by dni: ' . $e->getMessage());
            return null;
        }

    }
    public function getByDniWrite(int $dni)
    {
        try {
            return User::on('mysql_write')->where('dni', $dni)
                ->first();
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error getting user by dni (write): ' . $e->getMessage());
            return null;
        }

    }

    public function validate($email, $password)
    {
        try {
            $user = User::on('mysql_read')->where('email', $email)->first();
            if ($user && Hash::check($password, $user->password)) {
                return $user;
            } else {
                return null;
            }
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error validating user credentials: ' . $e->getMessage());
            return null;
        }


    }
    public function validateRegisterUser($email, $password)
    {
        try {
            $user = RegistroUser::on('mysql_read')->where('email', $email)->first();
            if ($user && Hash::check($password, $user->password)) {
                return $user;
            } else {
                return null;
            }
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error validating register user credentials: ' . $e->getMessage());
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
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error validating user email: ' . $e->getMessage());
            return false;
        }

    }

    public function createNewToken($dni)
    {
        try {
            $token = Str::random(60);
            $user = RegistroUser::on('mysql_write')->where('dni', $dni)->first();

            if (!$user) {
                throw new Exception("Usuario no encontrado para el DNI $dni");
            }

            $user->remember_token = $token;
            $user->remember_token_expires_at = now()->addDay();
            $user->save();

            return $user->remember_token;
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error creating a new token to RegisterUser: ' . $e->getMessage());
            return false;
        }

    }


    public function createNewTokenUser($dni)
    {
        try {
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
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error creating a new token to user: ' . $e->getMessage());
            return false;
        }

    }

    public function resetPassword($dni, $password)
    {
        try {
            $user = User::on('mysql_write')->where('dni', $dni)->first();
            $registerUser = RegistroUser::on('mysql_write')->where('dni', $dni)->first();
            if (!$user && !$registerUser) {
                throw new Exception("Usuario no encontrado para el DNI $dni");
            }
            if ($user->activo == 0 && $registerUser->activo == 0) {
                throw new Exception("El usuario no está activo");
            }
            $user->password = Hash::make($password);
            $user->remember_token = null;
            $user->remember_token_expires_at = null;
            $user->save();


            $registerUser->password = Hash::make($password);
            $registerUser->remember_token = null;
            $registerUser->remember_token_expires_at = null;
            $registerUser->save();


            return $user;
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error resetting user password: ' . $e->getMessage());
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
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error cleaning tokens: ' . $e->getMessage());
            return false;
        }
    }

    public function getRegisterUserByDni($dni)
    {
        try {
            $registerUser = RegistroUser::on('mysql_write')->where('dni', $dni)->first();
            if ($registerUser) {
                return $registerUser;
            } else {
                return null;
            }
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error getting register user by dni: ' . $e->getMessage());
            return null;
        }

    }

    public function delete($id)
    {
        try {
            if ($id != null) {
                User::on('mysql_write')->where('id', $id)->delete();
                return true;
            }

            return false;

        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error deleting a user: ' . $e->getMessage());
            return false;
        }
    }

    public function updateUser($dni_person, $activo)
    {
        try {
            User::on('mysql_write')
                ->where('dni', $dni_person)
                ->update(['activo' => $activo]);

            return true;
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error updating a user: ' . $e->getMessage());
            return false;
        }
    }




}