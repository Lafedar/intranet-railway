<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Empleado;
use App\Persona;
use App\Permiso;
use App\User;
Use Session;
use Auth;
use Mail;
use DB;

class PermisosController extends Controller
{
    public function index(Request $request)
    {
        $tipo_permisos = DB::table('tipo_permiso')->get();
        $jefe = null;
    
        if (auth()->user()->id == 44)
            $jefe = DB::table('personas')->where('personas.usuario', 19)->first();
        else {
            $jefe = DB::table('personas')->where('personas.usuario', auth()->user()->id)->first();
        }
    
        
        $motivo = $request->input('motivo');
        $nombreEmpleado = $request->input('empleado');
    
        
        if ($motivo != 0) {
            $permisosCollection = Permiso::Relaciones($jefe->id_p, $motivo);
        } else {
            $permisosCollection = Permiso::Relaciones($jefe->id_p, null);
        }
    
        
        if (!empty($nombreEmpleado)) {  
            $nombreEmpleado = strtolower($nombreEmpleado);
            $nombres = explode(" ", $nombreEmpleado); // Dividir la cadena en palabras separadas
        
            $permisosCollection = $permisosCollection->filter(function ($permiso) use ($nombres) {
                $nombre = strtolower($permiso->nombre_autorizado);
                $apellido = strtolower($permiso->apellido_autorizado);
        
                if (count($nombres) == 1) {
                    // Buscar solo en el nombre o solo en el apellido
                    $nombreCompleto = $nombre . ' ' . $apellido;
                    return $nombre === $nombres[0] || $apellido === $nombres[0] || $nombreCompleto === $nombres[0];
                } else {
                    // Buscar en el nombre y el apellido juntos
                    $nombreCompleto = $nombre . ' ' . $apellido;
                    $nombreEmpleadoCompleto = implode(" ", $nombres);
                    return $nombreCompleto === $nombreEmpleadoCompleto;
                }
            });
        }
        
        $permisosArray = $permisosCollection->toArray(); 
    
        // Para la paginación
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20; // Número de elementos por página
        $offset = ($currentPage - 1) * $perPage;
        $permisosPaginados = new LengthAwarePaginator(
            array_slice($permisosArray, $offset, $perPage, true),
            count($permisosArray),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        return view('permisos.index', [
            'permisos' => $permisosPaginados,
            'empleado' => $request->input('empleado'),
            'tipo_permisos' => $tipo_permisos
        ]);
    }


    public function show($id)
    {
        $permisos = DB::table('permisos')
        ->join('tipo_permiso', 'permisos.motivo','tipo_permiso.id_tip')
        ->where('permisos.id',$id)
        ->first();

        $autorizado = DB::table('personas')
        ->join('area','personas.area','area.id_a')
        ->where('personas.id_p',$permisos->autorizado)->first();

        $autorizante = DB::table('personas')->where('personas.id_p',$permisos->autorizante)->first();

        return view ('permisos.show', ['permiso' => $permisos, 'autorizado' => $autorizado,'autorizante' => $autorizante]);
    }


    public function store(Request $request)
    {

        $jefe = DB::table('personas')->where('personas.usuario', auth()->user()->id )->first();

        $permiso_nuevo = new Permiso;
        $permiso_nuevo->autorizado = $request['autorizado'];
        $permiso_nuevo->autorizante = $jefe->id_p;
        $permiso_nuevo->motivo = $request['motivo'];
        $permiso_nuevo->fecha_desde = $request['fecha_desde'];
        $permiso_nuevo->fecha_hasta = $request['fecha_hasta']; 
        $permiso_nuevo->descripcion = $request['descripcion'];
        $permiso_nuevo->hora_desde = $request['hora_desde'];
        $permiso_nuevo->hora_hasta = $request['hora_hasta'];
        $permiso_nuevo->save();

        $permiso = Permiso::Buscapermiso($permiso_nuevo->id)->first();

        Mail::send('permisos.mail',array('permiso' => $permiso, 'jefe' => $jefe), function($message) use ($jefe,$permiso){
            $message->from('notificaciones@lafedar.com', 'Notificaciones');
            $message->to('gustavo.lecman@lafedar.com')->subject('Solicitud de permiso'.' '.$permiso->area);
            $message->cc($jefe->correo);
            $message->cc('laura.cersofio@lafedar.com');
        });
        
        Session::flash('message','Permiso agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('permisos');
    }  

    public function destroy_permiso(Request $request, $id)
    {
        $jefe = DB::table('personas')->where('personas.usuario', auth()->user()->id )->first();

        $permiso = Permiso::Buscapermiso($id)->first();
        
        Mail::send('permisos.mail_cancelado',array('permiso' => $permiso, 'jefe' => $jefe), function($message) use ($permiso){
            $message->from('notificaciones@lafedar.com', 'Notificaciones');
            $message->to('gustavo.lecman@lafedar.com')->subject('Permiso cancelado'.' '.$permiso->area);
            $message->cc('laura.cersofio@lafedar.com');
        });
        
        $permiso_borrar = Permiso::find($id);
        $permiso_borrar->delete();

        Session::flash('message','Permiso cancelado con éxito');
        Session::flash('alert-class', 'alert-success');     
        return redirect('permisos');
    }

    public function select_autorizado(){

        $persona = DB::table('personas')->where('personas.usuario', auth()->user()->id )->first();
        //dd($persona);
        if($persona->jefe == 1){
            return Empleado::Busca_personas($persona->id_p);
        }
        else{
            return Empleado::Busca_personas($persona->id_p)
            ->Rango()
            ->get();
        }
    }

    public function select_tipo_permiso(){
        return DB::table('tipo_permiso')->where('tipo_permiso.desc', '!=', 'Licencia de cumpleaños')->get();
    }
}