<?php

namespace App\Services;



use Illuminate\Database\Eloquent\Collection;
use DB;
use App\Models\Empleado;
use App\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Models\Persona;
use log;




class EmpleadoService
{
    public function getById(int $id)
    {
        try {
            if (!empty($id)) {
                return DB::table('personas')->where('id_p', $id)->get();
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error getting employee by id: ' . $e->getMessage());
            return false;
        }


    }
    public function updateEmpleado($activo, $jefe, $id_p, $nombre, $apellido, $dni, $interno, $correo, $legajo, $fe_nac, $fe_ing, $area, $turnoEdit)
    {
        try {
            if (!$activo || !$jefe) {
                DB::table('jefe_area')->where('jefe', $id_p)->delete();
            }

            $user = User::where('dni', $dni)->first();

            if (is_object($user)) {
                if ($activo == 0) {
                    // Eliminar todos los roles del usuario
                    DB::table('model_has_roles')
                        ->join('users', 'users.id', '=', 'model_has_roles.model_id')
                        ->join('personas', 'personas.usuario', '=', 'users.id')
                        ->where('personas.id_p', $id_p)
                        ->delete();

                    // Liberar los puestos asociados a esta persona
                    DB::table('puestos')->where('persona', $id_p)->update(['persona' => null]);

                    $user->activo = 0;
                    $user->save();


                } elseif ($activo == 1) {

                    $user->activo = 1;
                    $user->save();

                }
            }



            // Actualizar datos del empleado
            DB::table('personas')
                ->where('id_p', $id_p)
                ->update([
                    'nombre_p' => $nombre,
                    'apellido' => $apellido,
                    'dni' => $dni,
                    'interno' => $interno,
                    'correo' => $correo,
                    'legajo' => $legajo,
                    'fe_nac' => $fe_nac,
                    'fe_ing' => $fe_ing,
                    'area' => $area,
                    'turno' => $turnoEdit,
                    'activo' => $activo,
                    'jefe' => $jefe,
                ]);


            return true;
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error updating employee: ' . $e->getMessage());
            return false;
        }


    }

    public function obtenerDatosParaEditar(int $id)
    {
        try {
            // Obtener los datos del empleado
            $empleado = DB::table('personas')
                ->leftJoin('area', 'personas.area', 'area.id_a')
                ->where('personas.id_p', $id)
                ->first();

            if (!$empleado) {
                throw new Exception('Empleado no encontrado.');
            }

            // Obtener el usuario asociado al empleado
            $usuario = DB::table('users')->where('id', $empleado->usuario)->first();

            // Obtener todas las áreas y usuarios
            $areas = DB::table('area')->get();
            $usuarios = DB::table('users')->get();

            return [
                'empleado' => $empleado,
                'usuario' => $usuario,
                'areas' => $areas,
                'usuarios' => $usuarios,
            ];
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error retrieving data to update employee: ' . $e->getMessage());
            return null;
        }

    }


    public function destroyEmpleado(int $id) //no se usa ahora, es para mas adelante
    {
        try {

            $empleado = Empleado::where('id_p', $id)->first();

            if (!$empleado) {

                return false;
            }

            // Desactivar el empleado
            $empleado->activo = 0;
            $empleado->save();

            // Obtener el ID de usuario a partir de la tabla personas
            $usuarioId = DB::table('personas')->where('id_p', $id)->value('usuario');

            // Verificar si se obtuvo el usuario y desactivarlo
            if ($usuarioId) {
                $usuario = User::find($usuarioId);
                if ($usuario) {
                    $usuario->activo = 0;
                    $usuario->save();
                }
            }

            return true;
        } catch (Exception $e) {
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error deleting user: ' . $e->getMessage());
            return false;
        }

    }

}
