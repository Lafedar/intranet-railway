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
Use Session;
use Auth;
use DB;
use App\Services\EnrolamientoCursoService;
use App\Services\CursoInstanciaService;
use App\Services\PersonaService;
use App\Services\CursoService;

class EmpleadoController extends Controller{
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
    public function index(Request $request){
        /*$empleados = Empleado::Relacion()->get();

        return view ('empleado.index', array('empleados' => $empleados));*/
        $search = $request->input('search', '');
        $filtroJefe = $request->has('filtroJefe');
        $filtroActividad = $request->has('filtroActividad');
    
        $query = Empleado::Relacion();
    
        if (!empty($search) && preg_match('/^[a-zA-Z0-9 ]*$/', $search)) { 
            $query->where(function($q) use ($search) {
                $q->where('apellido', 'like', "%{$search}%")
                  ->orWhere('nombre_p', 'like', "%{$search}%")
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

    public function store(Request $request){
        $aux= DB::table('personas')->where('personas.dni',$request['dni'])->first();

        if($aux){
            Session::flash('message','DNI ingresado ya se encuentra asignado');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back()->withInput();
        }

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
        $empleado->activo = 1;
        $empleado->save();

        Session::flash('message','Empleado agregado con éxito');
        Session::flash('alert-class', 'alert-success');

        return redirect('empleado');
    }
    
    public function show($id){}

    public function edit($id){
        $empleados = DB::table('personas')
        ->leftjoin('area','personas.area','area.id_a')
        ->where('personas.id_p',$id)
        ->first();

        $area = DB::table('area')->get();
        
        return view ('empleado.edit', ['empleado' => $empleados], ['area' => $area]);
    }
    
    public function update(Request $request, $id){
        $activo = ($request['actividad'] == 'on') ? 1 : 0;
        $jefe = ($request['esJefe'] == 'on') ? 1 : 0;

        if(!$activo || !$jefe){
            DB::table('jefe_area')
                ->where('jefe', $request['id_p'])
                ->delete();
        }
        
        if(!$activo) {
            //elimino todas las filas en las que el usuario tenia permisos 
            DB::table('model_has_roles')
                ->leftjoin('users', 'users.id', 'model_has_roles.model_id')
                ->leftjoin('personas', 'personas.usuario', 'users.id')
                ->where('personas.id_p', $request['id_p'])
                ->delete();
            //elimino usuario si descativo la persona
            DB::table('users')
                ->join('personas', 'users.id', '=', 'personas.usuario')
                ->where('personas.id_p', $request['id_p'])
                ->delete();
            //pongo todos los puestos a lo que esta persona pertenecia en null
            DB::table('puestos')
                ->where('persona', $request['id_p'])
                ->update(['persona' => null]);    
        }
        
        $empleado = DB::table('personas')
            ->where('personas.id_p',$request['id_p'])
            ->update([
                'nombre_p' => $request['nombre'],
                'apellido' => $request['apellido'],
                'dni' => $request['dni'],
                'interno' => $request['interno'],
                'correo' => $request['correo'],
                'fe_nac' => $request['fe_nac'],
                'fe_ing' => $request['fe_ing'],
                'area' => $request['area'],
                'turno' => $request['turnoEdit'],
                'activo' => $activo,
                'jefe' => $jefe,
            ]);      
        Session::flash('message','Empleado modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('empleado');
    }

    public function destroy_empleado(Request $request, $id){
        $empleado = Empleado::find($id);
        $empleado->activo = 0;
        $empleado->save();

         return response()->json([
        'message' => 'Empleado eliminado con éxito'
        ]);       
    }
    
    public function selectAreaEmpleados(){
        return DB::table('area')->get();
    }  

    public function selectTurnosEmpleados(){
        return DB::table('turnos')->get();
    }  

    public function selectAreasTurnos(){
        return [Empleado::selectAreas(),
            Empleado::selectTurnos(),
            Empleado::selectJefeXArea()];
    }

    public function showUpdateAreaXJefe($id){
        $idsJAs = Empleado::showAreaXJefeUpdate($id);
        return view('empleado.update', ['idsJAs' => $idsJAs, 'idJefe' => $id]);
    }

    public function deleteAreaXJefe($idJA){
        $deletedRows = DB::table('jefe_area')->where('id_ja', $idJA)->delete();
    }

    public function storeRelacionJefeXArea($idJefe, $areaId, $turnoId){
        $jefeXArea = new JefeXArea;
        $jefeXArea->jefe = $idJefe;
        $jefeXArea->area = $areaId;
        $jefeXArea->turno = $turnoId;
        $jefeXArea->save();
    }

    public function obtenerNuevoListadoAreaXJefe($id){
        $idsJAs = Empleado::showAreaXJefeUpdate($id);
        return view('empleado.update', ['idsJAs' => $idsJAs, 'idJefe' => $id]);
    }

    
    public function getCursos(int $userId)
    {
        $cursos = $this->enrolamientoCursoService->getAllEnrolledCourses($userId);
        $persona = $this->personaService->getById($userId);
        // Inicializar un array para almacenar los cursos con la información adicional de la instancia
        $cursosConDetalles = [];

        foreach ($cursos as $curso) {
            
            $areas = $this->cursoService->getAreasByCourseId($curso->id);
            $curso->areas = $areas;
            
            $instancia = $this->cursoInstanciaService->getInstanceById($curso->pivot->id_instancia, $curso->id);
        
            if ($instancia) {
                $curso->fecha_inicio = $instancia->fecha_inicio;
                $curso->capacitador = $instancia->capacitador;
                $curso->modalidad = $instancia->modalidad;
            }

            $cursosConDetalles[] = $curso;
        }

        return view('empleado.cursos', compact('cursosConDetalles','persona'));
    }
}