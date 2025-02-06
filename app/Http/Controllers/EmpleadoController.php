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
use Illuminate\Support\Facades\Hash;


class EmpleadoController extends Controller
{
    private $enrolamientoCursoService;
    private $cursoInstanciaService;
    private $personaService;
    private $cursoService;

    public function __construct(EnrolamientoCursoService $enrolamientoCursoService, CursoInstanciaService $cursoInstanciaService, PersonaService $personaService, CursoService $cursoService)
    {
        $this->enrolamientoCursoService = $enrolamientoCursoService;
        $this->cursoInstanciaService = $cursoInstanciaService;
        $this->personaService = $personaService;
        $this->cursoService = $cursoService;
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
        $aux = DB::table('personas')->where('personas.dni', $request['dni'])->first();

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


            $usuario = User::create([
                'name' => $nombre . ' ' . $apellido,
                'email' => $correo,
                'password' => Hash::make($request['password'])
            ]);

            $usuario->dni = $empleado->dni;
            $usuario->save();
            $id_user = DB::table('users')->where('users.email', $empleado->correo)->value('id');
            $persona = DB::table('personas')
                ->where('personas.dni', $empleado->dni)
                ->update(['usuario' => $id_user]);

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

        $empleados = DB::table('personas')
            ->leftjoin('area', 'personas.area', 'area.id_a')
            ->where('personas.id_p', $id)
            ->first();


        $usuario = DB::table('users')->where('id', $empleados->usuario)->first();


        $area = DB::table('area')->get();
        $usuarios = DB::table('users')->get();


        return view('empleado.edit', [
            'empleado' => $empleados,
            'area' => $area,
            'usuario' => $usuarios,
            'password' => $usuario->password
        ]);
    }

    public function update(Request $request, $id)
    {
        $activo = ($request->has('actividad')) ? 1 : 0;
        $jefe = ($request->has('esJefe')) ? 1 : 0;

        
        if (!$activo || !$jefe) {
            DB::table('jefe_area')->where('jefe', $request['id_p'])->delete();
        }

        if (!$activo) {
            // Eliminar todos los roles del usuario
            DB::table('model_has_roles')
                ->join('users', 'users.id', '=', 'model_has_roles.model_id')
                ->join('personas', 'personas.usuario', '=', 'users.id')
                ->where('personas.id_p', $request['id_p'])
                ->delete();

            // Liberar los puestos asociados a esta persona
            DB::table('puestos')->where('persona', $request['id_p'])->update(['persona' => null]);
        }

        // Buscar el usuario asociado a la persona
        $usuarioId = DB::table('personas')->where('id_p', $request['id_p'])->value('usuario');
        $usuario = User::find($usuarioId);

        // Validar si las contraseñas coinciden antes de actualizar cualquier dato
        if (!empty($request['password']) && !empty($request['password2'])) {
            if ($request['password'] !== $request['password2']) {
                return back()->with(['error' => 'Las contraseñas no coinciden.']);
            }
        }

        // Actualizar datos del empleado
        DB::table('personas')
            ->where('id_p', $request['id_p'])
            ->update([
                'nombre_p' => $request['nombre'],
                'apellido' => $request['apellido'],
                'dni' => $request['dni'],
                'interno' => $request['interno'],
                'correo' => $request['correo'],
                'legajo' => $request['legajo'],
                'fe_nac' => $request['fe_nac'],
                'fe_ing' => $request['fe_ing'],
                'area' => $request['area'],
                'turno' => $request['turnoEdit'],
                'activo' => $activo,
                'jefe' => $jefe,
            ]);

        // Si existe el usuario, actualizarlo
        if ($usuario) {
            $usuario->name = "{$request['nombre']} {$request['apellido']}";
            $usuario->email = $request['correo'];
            $usuario->dni = $request['dni'];
            $usuario->activo = $activo;

            // Si las contraseñas fueron validadas, actualizar la contraseña
            if (!empty($request['password'])) {
                $usuario->password = Hash::make($request['password']);
            }

            $usuario->save();
        }

        
        Session::flash('message', 'Empleado modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('empleado');
    }


    public function destroy_empleado(Request $request, $id)
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


    public function getCursos(int $userId)
    {
        $cursosConDetalles = $this->enrolamientoCursoService->getCursos($userId);
        $persona = $this->personaService->getById($userId);

        return view('empleado.cursos', compact('cursosConDetalles', 'persona'));
    }

    public function getCursosByDni(int $dni)
    {
        $persona = $this->personaService->getByDni($dni);
        $cursosConDetalles = $this->enrolamientoCursoService->getCursosByDni($dni);

        return view('empleado.cursos', compact('cursosConDetalles', 'persona'));
    }
}