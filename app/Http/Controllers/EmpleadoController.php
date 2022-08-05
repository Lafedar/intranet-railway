<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Persona;
use App\Empleado;
use App\User;
use Auth;
use DB;
Use Session;
use Illuminate\Routing\Controller;
use Krucas\Notification\Facades\Notification;
use Krucas\Notification\Middleware\NotificationMiddleware;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $empleados = Empleado::Relacion()->get();

        return view ('empleado.index', array('empleados' => $empleados));
    }

    
    public function store(Request $request)
    {
        $aux= DB::table('personas')->where('personas.dni',$request['dni'])->first();

        if($aux){
            Session::flash('message','DNI ingresado ya se encuentra asignado');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back()->withInput();
        }

        $empleado = new Empleado;
        $empleado->nombre_p = $request['nombre'];
        $empleado->apellido = $request['apellido'];
        $empleado->dni = $request['dni '];
        $empleado->interno = $request['interno'];
        $empleado->correo = $request['correo'];
        $empleado->fe_nac = $request['fe_nac'];
        $empleado->fe_ing = $request['fe_ing'];
        $empleado->area = $request['area'];
        $empleado->activo = 1;
        $empleado->rango = 3;
        $empleado->save();

        Session::flash('message','Empleado agregado con éxito');
        Session::flash('alert-class', 'alert-success');

        return redirect('empleado');
    }
    
    public function show($id)
    {

    }

    public function edit($id)
    {
        $empleados = DB::table('personas')
        ->leftjoin('area','personas.area','area.id_a')
        ->where('personas.id_p',$id)
        ->first();

        $area = DB::table('area')->get();
        
        return view ('empleado.edit', ['empleado' => $empleados], ['area' => $area]);
    }
    
    public function update(Request $request, $id)
    {

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
            
        ]);      

        Session::flash('message','Empleado modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('empleado');
    }

    public function destroy_empleado(Request $request, $id)
    {
        $empleado = Empleado::find($id);
        $empleado->activo = 0;
        $empleado->save();

         return response()->json([
        'message' => 'Empleado eliminado con éxito'
        ]);       
    }
}