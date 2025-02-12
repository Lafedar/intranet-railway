<?php

namespace App\Services;



use Illuminate\Database\Eloquent\Collection;
use DB;
use App\Models\Empleado;
use App\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Models\Persona;



class EmpleadoService
{
    public function getById(int $id)
    {
        return DB::table('personas')->where('id_p', $id)->get();
    }
    public function updateEmpleado($activo, $jefe, $id_p, $password, $password2, $nombre, $apellido, $dni, $interno, $correo, $legajo, $fe_nac, $fe_ing, $area, $turnoEdit)
    {

        if (!$activo || !$jefe) {
            DB::table('jefe_area')->where('jefe', $id_p)->delete();
        }

        if (!$activo) {
            // Eliminar todos los roles del usuario
            DB::table('model_has_roles')
                ->join('users', 'users.id', '=', 'model_has_roles.model_id')
                ->join('personas', 'personas.usuario', '=', 'users.id')
                ->where('personas.id_p', $id_p)
                ->delete();

            // Liberar los puestos asociados a esta persona
            DB::table('puestos')->where('persona', $id_p)->update(['persona' => null]);
        }

        // Buscar el usuario asociado a la persona
        $usuarioId = DB::table('personas')->where('id_p', $id_p)->value('usuario');
        $usuario = User::find($usuarioId);

        // Validar si las contraseñas coinciden antes de actualizar cualquier dato
        if (!empty($password) && !empty($password2)) {
            if ($password !== $password2) {
                return ['error' => 'Las contraseñas no coinciden.'];
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

        // Si existe el usuario, actualizarlo
        if ($usuario) {
            $usuario->name = $nombre . ' ' . $apellido;
            $usuario->email = $correo;
            $usuario->dni = $dni;
            $usuario->activo = $activo;

            // Si las contraseñas fueron validadas, actualizar la contraseña
            if (!empty($password)) {
                $usuario->password = Hash::make($password);
            }

            $usuario->save();
        }

        return ['message' => 'Empleado modificado con éxito', 'alert-class' => 'alert-success'];

    }

    public function obtenerDatosParaEditar($id)
    {
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
    }


    public function destroyEmpleado(int $id): string
    {
        // Buscar el empleado por ID
        $empleado = Persona::where('id_p', $id)->first();
        if (!$empleado) {
            throw new Exception('Empleado no encontrado');
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

        return 'Empleado eliminado con éxito';
    }

}
