<?php

namespace App\Http\Controllers;

use Krucas\Notification\Middleware\NotificationMiddleware;
use Krucas\Notification\Facades\Notification;
use Illuminate\Routing\Controller;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use App\JefeXArea;
use App\Empleado;
use App\Persona;
use App\User;
use PHPUnit\Framework\Constraint\IsEmpty;
use Session;
use Auth;
use DB;
use App\Services\EnrolamientoCursoService;
use App\Services\CourseInstanceService;
use App\Services\PersonaService;
use App\Services\courseService;
use App\Services\EmpleadoService;
use App\Services\AreaService;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Exception;
use Log;


class EmpleadoController extends Controller
{
    private $enrolamientoCursoService;
    private $courseInstanceService;
    private $personaService;
    private $courseService;

    private $empleadoService;

    private $userService;

    private $areaService;

    public function __construct(EnrolamientoCursoService $enrolamientoCursoService, CourseInstanceService $courseInstanceService, PersonaService $personaService, courseService $courseService, EmpleadoService $empleadoService, UserService $userService, AreaService $areaService)
    {
        $this->enrolamientoCursoService = $enrolamientoCursoService;
        $this->courseInstanceService = $courseInstanceService;
        $this->personaService = $personaService;
        $this->courseService = $courseService;
        $this->empleadoService = $empleadoService;
        $this->userService = $userService;
        $this->areaService = $areaService;
    }
    public function index(Request $request)
    {
        try {
            $search = $request->input('search', '');
            $filtroJefe = $request->has('filtroJefe');
            $filtroActividad = $request->has('filtroActividad');

            $query = Empleado::Relacion();

            if (!empty($search) && preg_match('/^[a-zA-Z0-9 ]*$/', $search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('apellido', 'like', "%{$search}%")
                        ->orWhere('nombre_p', 'like', "%{$search}%")
                        ->orWhere('nombre_a', 'like', "%{$search}%")
                        ->orWhere('dni', 'like', "%{$search}%");
                });
            }

            if ($filtroJefe) {
                $query->where('jefe', 1);
            }

            if ($filtroActividad) {
                $query->where('activo', 1);
            }

            $perPage = $request->input('per_page', 20);
            $empleados = $query->paginate($perPage)->withQueryString();

            return view('empleado.index', [
                'empleados' => $empleados,
                'search' => $search,
                'filtroJefe' => $filtroJefe,
                'filtroActividad' => $filtroActividad
            ]);
        } catch (Exception $e) {
            Log::error('Error al mostrar empleados: ' . $e->getMessage());
            return response()->json([
                'error' => 'Lo siento, algo salió mal al mostrar los empleados.'
            ], 400);



        }

    }



    public function store(Request $request)
    {
        try {
            if (strlen($request['dni']) < 8) {
                Session::flash('error', 'DNI ingresado no tiene una longitud válida');
                Session::flash('alert-class', 'alert-warning');
                return redirect()->back()->withInput();
            }

            $aux = $this->personaService->getByDni(($request['dni']));

            if ($aux) {
                Session::flash('error', 'DNI ingresado ya se encuentra asignado');
                Session::flash('alert-class', 'alert-warning');
                return redirect()->back()->withInput();
            }
            if ($request['password'] === $request['password2']) {

                $activo = ($request['actividadCreate'] == 'on') ? 1 : 0;
                $jefe = ($request['esJefeCreate'] == 'on') ? 1 : 0;
                $empleado = new Empleado;
                $empleado->nombre_p = $request['nombre'];
                $empleado->apellido = $request['apellido'];
                $empleado->dni = $request['dni'];
                $empleado->interno = $request['interno'];
                $empleado->correo = $request['correo'];
                $empleado->fe_nac = $request['fe_nac'];
                $empleado->fe_ing = $request['fe_ing'];
                $empleado->area = $request['area'];
                $empleado->turno = $request['turno'];
                $empleado->activo = $activo;
                $empleado->jefe = $jefe;
                $empleado->legajo = $request['legajo'];
                $empleado->activo = 1;
                $empleado->save();


                $nombre = $empleado->nombre_p;
                $apellido = $empleado->apellido;
                $correo = $empleado->correo;
                $password = Hash::make($request['password']);

                $usuario = $this->userService->createUser(
                    $nombre,
                    $apellido,
                    $correo,
                    $password
                );
                $usuario->dni = $empleado->dni;
                $usuario->save();

                $id_user = DB::table('users')->where('users.email', $empleado->correo)->value('id');
                $persona = $this->personaService->updateUserByDni($empleado->dni, $id_user);


                Session::flash('message', 'Empleado agregado con éxito');
                Session::flash('alert-class', 'alert-success');
            } else {
                Session::flash('error', 'Las contraseñas no coinciden');
                Session::flash('alert-class', 'alert-error');
            }

            return redirect('empleado');
        } catch (Exception $e) {
            Log::error('Error al crear el empleado: ' . $e->getMessage());
            return response()->json([
                'error' => 'Lo siento, algo salió mal al crear el empleado.'
            ], 400);
        }

    }
    public function edit(int $id)
    {
        try {
            if (empty($id)) {
                return response()->json([
                    'error' => 'No se ha seleccionado ningún empleado'
                ], 404);
            } else {
                $datos = $this->empleadoService->obtenerDatosParaEditar($id);

                return view('empleado.edit', [
                    'empleado' => $datos['empleado'],
                    'area' => $datos['areas'],
                    'usuario' => $datos['usuarios'],
                    'password' => $datos['usuario']->password,
                ]);
            }
        } catch (Exception $e) {
            Log::error('Error al obtener los datos para editar el empleado ' . $e->getMessage());
            return response()->json([
                'error' => 'Lo siento, algo salió mal al editar el empleado.'
            ], 400);
        }



    }

    public function update(Request $request)
    {
        try {

            if (strlen($request['dni']) < 8) {
                Session::flash('error', 'DNI ingresado no es válido');
                Session::flash('alert-class', 'alert-warning');
                return redirect()->back()->withInput();
            }

            $activo = $request->has('actividad') ? 1 : 0;
            $jefe = $request->has('esJefe') ? 1 : 0;
            $id_p = $request['id_p'];
            $password = $request['password'];
            $password2 = $request['password2'];
            $nombre = $request['nombre'];
            $apellido = $request['apellido'];
            $dni = $request['dni'];
            $interno = $request['interno'];
            $correo = $request['correo'];
            $legajo = $request['legajo'];
            $fe_nac = $request['fe_nac'];
            $fe_ing = $request['fe_ing'];
            $area = $request['area'];
            $turnoEdit = $request['turnoEdit'];


            $result = $this->empleadoService->updateEmpleado($activo, $jefe, $id_p, $password, $password2, $nombre, $apellido, $dni, $interno, $correo, $legajo, $fe_nac, $fe_ing, $area, $turnoEdit);

            if (!$result) {
                return back()->with(['error' => 'Error al actualizar empleado']);
            }

            Session::flash('message', 'Empleado actualizado correctamente');
            Session::flash('alert-class', 'alert-success');
            return redirect('empleado');
        } catch (Exception $e) {
            Log::error('Error al editar el empleado: ' . $e->getMessage());
            return response()->json([
                'error' => 'Lo siento, algo salió mal al actualizar el empleado.'
            ], 400);
        }
    }

    public function destroy_empleado($id)
    {
        try {
            $empleado = Empleado::find($id);
            $empleado->activo = 0;
            $empleado->save();

            $usuarioId = DB::table('personas')->where('id_p', $id)->value('usuario');
            $usuario = User::find($usuarioId);
            $usuario->activo = 0;
            $usuario->save();


            return response()->json([
                'message' => 'Empleado eliminado con éxito'
            ]);

        } catch (Exception $e) {
            Log::error('Error al eliminar el empleado: ' . $e->getMessage());
            return response()->json([
                'error' => 'Lo siento, algo salió mal al borrar el empleado'
            ], 400);
        }
    }

    public function selectAreaEmpleados()
    {
        return $this->areaService->getAll();
    }

    public function selectTurnosEmpleados()
    {
        return DB::table('turnos')->get();
    }

    public function selectAreasTurnos()
    {
        try {
            return [
                Empleado::selectAreas(),
                Empleado::selectTurnos(),
                Empleado::selectJefeXArea()
            ];
        } catch (Exception $e) {
            Log::error('Error en el metodo: selectAreasTurnos: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }

    }

    public function showUpdateAreaXJefe($id)
    {
        try {
            if (!empty($id)) {
                $idsJAs = Empleado::showAreaXJefeUpdate($id);
                return view('empleado.update', ['idsJAs' => $idsJAs, 'idJefe' => $id]);
            } else {
                return response()->json([
                    'error' => 'No se ha seleccionado ningún empleado'
                ], 404);
            }
        } catch (Exception $e) {
            Log::error('Error en el metodo: showUpdateAreaXJefe: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }


    }

    public function deleteAreaXJefe($idJA)
    {
        try {
            if (!empty($idJA)) {
                $deletedRows = DB::table('jefe_area')->where('id_ja', $idJA)->delete();
            } else {
                return response()->json([
                    'error' => 'No se ha seleccionado ningún empleado'
                ], 404);
            }
        } catch (Exception $e) {
            Log::error('Error en el metodo: deleteAreaXJefe: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }

    }

    public function storeRelacionJefeXArea($idJefe, $areaId, $turnoId)
    {
        try {
            if (empty($idJefe) || empty($areaId) || empty($turnoId)) {
                return response()->json([
                    'error' => 'No se ha seleccionado ningún empleado'
                ], 404);
            } else {
                $jefeXArea = new JefeXArea;
                $jefeXArea->jefe = $idJefe;
                $jefeXArea->area = $areaId;
                $jefeXArea->turno = $turnoId;
                $jefeXArea->save();
            }
        } catch (Exception $e) {
            Log::error('Error en el metodo: storeRelacionJefeXArea: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }

    }

    public function obtenerNuevoListadoAreaXJefe($id)
    {
        try {
            if (!empty($id)) {
                $idsJAs = Empleado::showAreaXJefeUpdate($id);
                return view('empleado.update', ['idsJAs' => $idsJAs, 'idJefe' => $id]);
            } else {
                return response()->json([
                    'error' => 'No se ha seleccionado ningún empleado'
                ], 404);
            }
        } catch (Exception $e) {
            Log::error('Error en el metodo: obtenerNuevoListadoAreaXJefe: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }

    }



}