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
use Session;
use Auth;
use DB;
use App\Services\EnrolamientoCursoService;
use App\Services\CursoInstanciaService;
use App\Services\PersonaService;
use App\Services\CursoService;
use App\Services\EmpleadoService;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Exception;


class EmpleadoController extends Controller
{
    private $enrolamientoCursoService;
    private $cursoInstanciaService;
    private $personaService;
    private $cursoService;

    private $empleadoService;

    private $userService;

    public function __construct(EnrolamientoCursoService $enrolamientoCursoService, CursoInstanciaService $cursoInstanciaService, PersonaService $personaService, CursoService $cursoService, EmpleadoService $empleadoService, UserService $userService)
    {
        $this->enrolamientoCursoService = $enrolamientoCursoService;
        $this->cursoInstanciaService = $cursoInstanciaService;
        $this->personaService = $personaService;
        $this->cursoService = $cursoService;
        $this->empleadoService = $empleadoService;
        $this->userService = $userService;
    }
    public function index(Request $request)
    {

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
    }



    public function store(Request $request)
    {
        $aux=$this->personaService->getByDni(($request['dni']));

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
            $password=Hash::make($request['password']);

            $usuario = $this->userService->createUser(
                $nombre,
                $apellido,
                $correo,
                $password
            );
            $usuario->dni = $empleado->dni;
            $usuario->save();

            $id_user = DB::table('users')->where('users.email', $empleado->correo)->value('id');
            $persona=$this->personaService->updateUserByDni($empleado->dni, $id_user);
           

            Session::flash('message', 'Empleado agregado con éxito');
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('error', 'Las contraseñas no coinciden');
            Session::flash('alert-class', 'alert-error');
        }

        return redirect('empleado');
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $datos = $this->empleadoService->obtenerDatosParaEditar($id);
        
        return view('empleado.edit', [
            'empleado' => $datos['empleado'],
            'area' => $datos['areas'],
            'usuario' => $datos['usuarios'],
            'password' => $datos['usuario']->password,
            ]);
        
    }

    public function update(Request $request, $id)
    {
        $result = $this->empleadoService->updateEmpleado($request, $id);

        if (isset($result['error'])) {
            return back()->with(['error' => $result['error']]);
        }

        Session::flash('message', $result['message']);
        Session::flash('alert-class', $result['alert-class']);
        return redirect('empleado');
    }


    /*public function destroy_empleado(Request $request, $id)
    {
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

        
    }*/
    public function destroy_empleado(Request $request, $id)
    {
        try {
            
            $mensaje = $this->empleadoService->destroyEmpleado($id);

            return response()->json([
                'message' => $mensaje
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function selectAreaEmpleados()
    {
        return DB::table('area')->get();
    }

    public function selectTurnosEmpleados()
    {
        return DB::table('turnos')->get();
    }

    public function selectAreasTurnos()
    {
        return [
            Empleado::selectAreas(),
            Empleado::selectTurnos(),
            Empleado::selectJefeXArea()
        ];
    }

    public function showUpdateAreaXJefe($id)
    {
        $idsJAs = Empleado::showAreaXJefeUpdate($id);
        return view('empleado.update', ['idsJAs' => $idsJAs, 'idJefe' => $id]);
    }

    public function deleteAreaXJefe($idJA)
    {
        $deletedRows = DB::table('jefe_area')->where('id_ja', $idJA)->delete();
    }

    public function storeRelacionJefeXArea($idJefe, $areaId, $turnoId)
    {
        $jefeXArea = new JefeXArea;
        $jefeXArea->jefe = $idJefe;
        $jefeXArea->area = $areaId;
        $jefeXArea->turno = $turnoId;
        $jefeXArea->save();
    }

    public function obtenerNuevoListadoAreaXJefe($id)
    {
        $idsJAs = Empleado::showAreaXJefeUpdate($id);
        return view('empleado.update', ['idsJAs' => $idsJAs, 'idJefe' => $id]);
    }


    
}