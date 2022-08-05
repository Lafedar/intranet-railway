<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Persona;
use App\User;
use Auth;
use DB;
Use Redirect;
use Illuminate\Support\Facades\Input;
Use Session;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsuarioController extends Controller
{
    public function usuarios (Request $request){

    $usuarios = DB::table('users')
    ->select('users.id as id', 'users.name as nombre_usuario','users.email as email_usuario')
    ->orderBy('users.name','asc')
    ->get();

    $roles = DB::table('model_has_roles')
    ->leftjoin('roles','model_has_roles.role_id','roles.id')
    ->leftjoin('users','model_has_roles.model_id','users.id')
    ->select('roles.name as nombre_rol','roles.id as id_rol','users.id as id_usuario')
    ->get();

    $permisos = DB::table('role_has_permissions')
    ->leftjoin('permissions','role_has_permissions.permission_id','permissions.id')
    ->leftjoin('roles','role_has_permissions.role_id','roles.id')
    ->select('role_has_permissions.role_id as rol', 'permissions.name as nombre_permiso')
    ->get();

    return view ('usuarios.usuarios', array('usuarios'=>$usuarios, 'permisos'=>$permisos, 'roles'=>$roles));
}

public function create_usuario(Request $request){

    Auth::logout();

    return redirect('/register');
}

public function destroy_usuario($id)
{
    $usuario = User::find($id);
    $usuario->delete();

      return response()->json([
        'message' => 'Usuario eliminado con éxito'
        ]); 
}

public function asignar_rol(Request $request){

    $usuario = User::find($request['id']);

    $usuario->assignRole($request['rol']);
    //dd($usuario);
    Session::flash('message','Rol asignado con éxito');
    Session::flash('alert-class', 'alert-success');

    return redirect('/usuarios');
}

public function revocar_rol(Request $request){

    $usuario = User::find($request['id']);

    $usuario->removeRole($request['rol']);

    Session::flash('message','Rol revocado con éxito');
    Session::flash('alert-class', 'alert-success');

    return redirect('/usuarios');
}

public function store_rol(Request $request){
    $role = Role::create(['name' => $request['nombre_rol']]);
    Session::flash('message','Rol agregado con éxito');
    Session::flash('alert-class', 'alert-success');
    return redirect('/usuarios');
}

public function store_permiso(Request $request){
    $permiso = Permission::create(['name'=> $request['nombre_permiso']]);
    Session::flash('message','Permiso agregado con éxito');
    Session::flash('alert-class', 'alert-success');
    return redirect('/usuarios');
}

public function select_roles($id){

    $aux1 = DB::table('roles')
    ->leftjoin('model_has_roles','roles.id','model_has_roles.role_id')
    ->where('model_has_roles.model_id',$id)
    ->select('roles.name as name')
    ->get();

    if(count($aux1)==0){
        return DB::table('roles')->get();
    }
    else {
    foreach ($aux1 as $aux) {
        $data[] = $aux->name;
    }
    return DB::table('roles')->whereNotIn('roles.name',$data)->orderBy('name','asc')->select('id','name')->get();
    }
}

public function select_revocar_roles($id){

    return  DB::table('roles')
    ->leftjoin('model_has_roles','roles.id','model_has_roles.role_id')
    ->where('model_has_roles.model_id',$id)
    ->select('id','name')
    ->get();
}
}