<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Equipamiento;
use App\Puesto;
use App\Relacion;
use App\Persona;
use App\Incidente;
use App\User;
use Auth;
use DB;
Use Redirect;
use Illuminate\Support\Facades\Input;
Use Session;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
class EquipamientoController extends Controller
{   
    //crea el index
    public function index(Request $request)
    {
    // Obtener tipo de equipamiento e ips
    $tipo_equipamiento = DB::table('tipo_equipamiento')->orderBy('equipamiento', 'asc')->get();
    $ips = DB::table('ips')->orderBy('nombre', 'asc')->get();
    
    // Realiza la consulta con las condiciones y agrega la columna 'activo'
    $equipamientos = Equipamiento::Ip($request->get('ip'))
        ->Equipo($request->get('equipo'))
        ->Relaciones($request->get('tipo'), $request->get('subred'))
        ->Puesto($request->get('puesto'))
        ->Area($request->get('area'))
        ->Usuario($request->get('usuario'));
       

    if ($request->get('tipo') == 1) {  //gabinetes
        $equipamientos->where('tipo', 1);
    } elseif ($request->get('tipo') == 2) { //monitores
        $equipamientos->where('tipo', 2);
    } elseif ($request->get('tipo') == 3) { //impresoras
        $equipamientos->where('tipo', 3);
    }


    $activo = $request->get('activo');  //filtro para activo
    if ($activo === '1') {
        $equipamientos->where('equipamientos.activo', 1);
    } elseif ($activo === '0') {
        $equipamientos->where('equipamientos.activo', 0);
    }
    

        $equipamientos = $equipamientos->paginate(20)
        ->withQueryString();  //para aplicar el filtro en la paginacion
        
        return view('equipamiento.inicio', [
            'equipamientos' => $equipamientos,
            'equipo' => $request->get('equipo'),
            'puesto' => $request->get('puesto'),
            'ip' => $request->get('ip'),
            'tipo_equipamiento' => $tipo_equipamiento,
            'tipo' => $request->get('tipo'),
            'ips' => $ips,
            'subred' => $request->get('subred'),
            'usuario' => $request->get('usuario'),
            'area' => $request->get('area'),
            'tipo3' => $request->get('tipo3'),
            'activo' => $request->get('activo')
        ]);
       
    }
    //trae tabla de tipos de equipamientos 
    public function select_tipo_equipamiento()
    {
        return DB::table('tipo_equipamiento')->orderBy('equipamiento','asc')->get();
    }
    //trae tabla de subredes
    public function select_ips()
    {
        return DB::table('ips')->orderBy('nombre','asc')->get();
    }
    public function subred_busca(){
        $ips = DB::table('ips')->orderBy('nombre', 'asc')->get();
        
        return view('equipamiento.listado_ip', array( 'ips' => $ips, 'subred' => $request->get('subred')));
    }
    public function create()
    {
        $tipo_equipamiento = DB::table('tipo_equipamiento')->get();
        $ips = DB::table('ips')->get();
        return view ('equipamiento.create_equipamiento', array('tipo_equipamiento' => $tipo_equipamiento, 'ips' => $ips));
    }
    //Agregar equipo
    public function store(Request $request)
    {
        //consulta en bd si existe el id
        $aux_id=DB::table('equipamientos')->where('equipamientos.id_e',$request['id_e'])->first();
        //mensaje de id existente
        if($aux_id){
            Session::flash('message','ID ingresado ya se encuentra asignado');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back()->withInput();
        }
        
        $nueva_ip = null;
        
        //armo la nueva ip con la parte de la id de red traida de la tabla ips y la id de host de lo que ingreso el usuario y consulto en la bd si existe la ip
        if($request['ip'] != null)
        {   
            $puerta_enlace = DB::table('ips')->where('ips.id', $request['id_red'])->value('puerta_enlace');
            $pe_separada = explode("." , $puerta_enlace);
            $nueva_ip = $pe_separada[0].".".$pe_separada[1].".".$pe_separada[2].".".$request['ip'];
            $aux_ip = DB::table('equipamientos')->where('equipamientos.ip', $nueva_ip)->first();
            
            //mensaje de ip existente
            if($aux_ip)
            {
                Session::flash('message','Direccion IP ingresada ya se encuentra asignada');
                Session::flash('alert-class', 'alert-warning');
                return redirect()->back()->withInput();
            }
        }
        //crea un nuevo equipamiento y asigna los datos
        $equipamiento = new Equipamiento;
        $equipamiento->id_e = $request['id_e'];
        $equipamiento->marca = $request['marca'];
        $equipamiento->modelo = $request['modelo'];
        $equipamiento->num_serie = $request['num_serie'];
        $equipamiento->subred = $request['ips'];
        $equipamiento->ip = $nueva_ip;
        $equipamiento->obs = $request['obs'];
        $equipamiento->pulgadas = $request['pulgadas'];
        $equipamiento->procesador = $request['procesador'];
        $equipamiento->disco = $request['disco'];
        $equipamiento->memoria = $request['memoria'];
        $equipamiento->tipo = $request['tipo_equipamiento'];
        $equipamiento->toner = $request['toner'];
        $equipamiento->activo = $request['activo'];
        $equipamiento->unidad_imagen = $request['unidad_imagen'];
        $equipamiento->oc = $request['oc'];
        $equipamiento->save();
        //mensaje de equipamiento agregado
        Session::flash('message','Equipamiento agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('equipamiento');
    }
    public function modal_editar_equipamiento (Request $request,$id){
        return DB::table('equipamientos')
        ->leftjoin('tipo_equipamiento','equipamientos.tipo','tipo_equipamiento.id')
        ->leftjoin('ips','equipamientos.subred','ips.id')
        ->where('equipamientos.id_e',$id)
        ->first();
    }
    //modificar equipamiento
    public function update(Request $request)
    {   
        $nueva_ip = null;
        //armo la nueva ip con la parte de la id de red traida de la tabla ips y la id de host de lo que ingreso el usuario y consulto en la bd si existe la ip
        if($request['ip'] != null and $request['id_red'] != null)
        {   
            $puerta_enlace = DB::table('ips')->where('ips.id', $request['id_red'])->value('puerta_enlace');
            $pe_separada = explode("." , $puerta_enlace);
            $nueva_ip = $pe_separada[0].".".$pe_separada[1].".".$pe_separada[2].".".$request['ip'];
            //traigo de la bd la fila donde se encuentre la misma ip
            $aux_ip = DB::table('equipamientos')->where('equipamientos.ip', $nueva_ip)->first();
            //if para que no tome como dato la misma fila que se esta editando ya que dira que la ip esta duplicada
            if($aux_ip)
            {
                if($request['id_e'] == $aux_ip->id_e)
                {
                    $aux_ip = null;
                }
            }
            
            //si existe una ip igual
            if($aux_ip)
            {
                //mensaje de ip asignada
                Session::flash('message','Direccion IP ingresada ya se encuentra asignada');
                Session::flash('alert-class', 'alert-warning');
                return redirect()->back()->withInput();
            }
        }
        else if($request['ip'] == null and $request['id_red'] != null)
        {
            Session::flash('message','Si el id de red contiene datos, el id de host no puede estar vacio');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back()->withInput();
        }
        else if($request['ip'] != null and $request['id_red'] == null)
        {
            Session::flash('message','Si el id de host contiene datos, el id de red no puede estar vacio');
            Session::flash('alert-class', 'alert-warning');
            return redirect()->back()->withInput();
        }
        else
        {
            $nueva_ip = null;
        }


       $equipamiento = DB::table('equipamientos')
        ->where('equipamientos.id_e',$request['id_e'])
        ->update([
            'id_e' => $request['id_e'],
            'marca' => $request['marca'],
            'modelo' => $request['modelo'],
            'num_serie' => $request['num_serie'],
            'ip' => $nueva_ip,
            'subred' => $request['ips'],
            'obs' => $request['obs'],
            'pulgadas' => $request['pulgadas'],
            'procesador' => $request['procesador'],
            'disco' => $request['disco'],
            'memoria' => $request['memoria'],
            'tipo' => $request['tipo_equipamiento'],
            'toner' => $request['toner'],
            'activo' => $request['activo'],
            'unidad_imagen' => $request['unidad_imagen'],
            'oc' => $request['oc']
        ]);      
        //mensaje de equipamiento modificado
        Session::flash('message','Equipamiento modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        
        return redirect('equipamiento');
    }
    
    public function show($id)
    {
        //
    }
    public function subredes(Request $request)
    {
        
    }
    public function listado_ip(Request $request)
    {
        $listado= array();
        for($i=1; $i<255;$i++)
        {          
            $equipamiento = Equipamiento::ListadoIpLan($i)->first();
            if($equipamiento == null)
            {
                $listado[$i][0] = "10.41.20.".$i;
                $listado[$i][1] = 'Libre';
                $listado[$i][2] = '';
                $listado[$i][3] = '';
                $listado[$i][4] = '';
                $listado[$i][5] = 'Lan';
            }
            else
            {
                $listado[$i][0] = "10.41.20.".$i;
                $listado[$i][1] = $equipamiento->id_equipamiento;
                $listado[$i][2] = $equipamiento->tipo;
                $listado[$i][3] = $equipamiento->nombre.' '.$equipamiento->apellido;
                $listado[$i][4] = $equipamiento->obs;
                $listado[$i][5] = $equipamiento->nombre_red;
            }
        }
        for($i=1; $i<255;$i++)
        {          
            $equipamiento = Equipamiento::ListadoIpPLC($i)->first();
            if($equipamiento == null)
            {
                $listado[$i+254][0] = "10.41.30.".$i;
                $listado[$i+254][1] = 'Libre';
                $listado[$i+254][2] = '';
                $listado[$i+254][3] = '';
                $listado[$i+254][4] = '';
                $listado[$i+254][5] = 'PLC';
            }
            else
            {
                $listado[$i+254][0] = "10.41.30.".$i;
                $listado[$i+254][1] = $equipamiento->id_equipamiento;
                $listado[$i+254][2] = $equipamiento->tipo;
                $listado[$i+254][3] = $equipamiento->nombre.' '.$equipamiento->apellido;
                $listado[$i+254][4] = $equipamiento->obs;
                $listado[$i+254][5] = $equipamiento->nombre_red;
            }
        }
        for($i=1; $i<255;$i++)
        {          
            $equipamiento = Equipamiento::ListadoIpImp($i)->first();
            if($equipamiento == null)
            {
                $listado[$i+508][0] = "10.41.40.".$i;
                $listado[$i+508][1] = 'Libre';
                $listado[$i+508][2] = '';
                $listado[$i+508][3] = '';
                $listado[$i+508][4] = '';
                $listado[$i+508][5] = 'Impresoras';
            }
            else
            {
                $listado[$i+508][0] = "10.41.40.".$i;
                $listado[$i+508][1] = $equipamiento->id_equipamiento;
                $listado[$i+508][2] = $equipamiento->tipo;
                $listado[$i+508][3] = $equipamiento->nombre.' '.$equipamiento->apellido;
                $listado[$i+508][4] = $equipamiento->obs;
                $listado[$i+508][5] = $equipamiento->nombre_red;
            }
        }
        for($i=1; $i<255;$i++)
        {          
            $equipamiento = Equipamiento::ListadoIpWifiInv($i)->first();
            if($equipamiento == null)
            {
                $listado[$i+762][0] = "10.41.50.".$i;
                $listado[$i+762][1] = 'Libre';
                $listado[$i+762][2] = '';
                $listado[$i+762][3] = '';
                $listado[$i+762][4] = '';
                $listado[$i+762][5] = 'Wifi Invitados';
            }
            else
            {
                $listado[$i+762][0] = "10.41.50.".$i;
                $listado[$i+762][1] = $equipamiento->id_equipamiento;
                $listado[$i+762][2] = $equipamiento->tipo;
                $listado[$i+762][3] = $equipamiento->nombre.' '.$equipamiento->apellido;
                $listado[$i+762][4] = $equipamiento->obs;
                $listado[$i+762][5] = $equipamiento->nombre_red;
            }
        }
        for($i=1; $i<255;$i++)
        {          
            $equipamiento = Equipamiento::ListadoIpWifiInt($i)->first();
            if($equipamiento == null)
            {
                $listado[$i+1016][0] = "10.41.60.".$i;
                $listado[$i+1016][1] = 'Libre';
                $listado[$i+1016][2] = '';
                $listado[$i+1016][3] = '';
                $listado[$i+1016][4] = '';
                $listado[$i+1016][5] = 'Wifi Interno';
            }
            else
            {
                $listado[$i+1016][0] = "10.41.60.".$i;
                $listado[$i+1016][1] = $equipamiento->id_equipamiento;
                $listado[$i+1016][2] = $equipamiento->tipo;
                $listado[$i+1016][3] = $equipamiento->nombre.' '.$equipamiento->apellido;
                $listado[$i+1016][4] = $equipamiento->obs;
                $listado[$i+1016][5] = $equipamiento->nombre_red;
            }
        }
        for($i=1; $i<255;$i++)
        {          
            $equipamiento = Equipamiento::ListadoIpMant($i)->first();
            if($equipamiento == null)
            {
                $listado[$i+1270][0] = "10.41.70.".$i;
                $listado[$i+1270][1] = 'Libre';
                $listado[$i+1270][2] = '';
                $listado[$i+1270][3] = '';
                $listado[$i+1270][4] = '';
                $listado[$i+1270][5] = 'Terceros Mantenimiento';
            }
            else
            {
                $listado[$i+1270][0] = "10.41.70.".$i;
                $listado[$i+1270][1] = $equipamiento->id_equipamiento;
                $listado[$i+1270][2] = $equipamiento->tipo;
                $listado[$i+1270][3] = $equipamiento->nombre.' '.$equipamiento->apellido;
                $listado[$i+1270][4] = $equipamiento->obs;
                $listado[$i+1270][5] = $equipamiento->nombre_red;
            }
        }
        for($i=1; $i<6;$i++)
        {   
            $aux = $i+144;
            $equipamiento = Equipamiento::ListadoIpWan($aux)->first();
            if($equipamiento == null)
            {
                $listado[$i+1524][0] = "181.30.186.".$aux;
                $listado[$i+1524][1] = 'Libre';
                $listado[$i+1524][2] = '';
                $listado[$i+1524][3] = '';
                $listado[$i+1524][4] = '';
                $listado[$i+1524][5] = 'Wan Firtel';
            }
            else
            {
                $listado[$i+1524][0] = "181.30.186.".$aux;
                $listado[$i+1524][1] = $equipamiento->id_equipamiento;
                $listado[$i+1524][2] = $equipamiento->tipo;
                $listado[$i+1524][3] = $equipamiento->nombre.' '.$equipamiento->apellido;
                $listado[$i+1524][4] = $equipamiento->obs;
                $listado[$i+1524][5] = $equipamiento->nombre_red;
            }
        }
       return view ('equipamiento.listado_ip', array('listado'=> $listado));
    }
   //****************RELACIONES**********************
    public function select_puesto(){
        return DB::table('puestos')
        ->leftjoin('personas','puestos.persona','personas.id_p')
        ->leftjoin('localizaciones', 'localizaciones.id', 'puestos.id_localizacion')
        ->leftjoin('area', 'area.id_a', 'localizaciones.id_area')
        ->orderBy('area.nombre_a')
        ->orderBy('localizaciones.nombre')
        ->get();
    }
    public function store_relacion(Request $request)
    {
        $relacion= new Relacion;
        $relacion->equipamiento = $request['equipamiento'];
        $relacion->puesto = $request['puesto'];
        $relacion->estado = 1;
        $relacion->save();
        Session::flash('message','Relacion agregada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('equipamiento');
    }
    public function destroy_relacion(Request $request)
    {
        $relacion = DB::table('relaciones')->where('relaciones.id_r',$request['relacion'])->update(['estado'=> 0]);
        Session::flash('message','Relacion eliminada con éxito');
        Session::flash('alert-class', 'alert-success');
    
        return redirect('equipamiento');
    }
    public function select_soft()
    {    
        return DB::table('softinst')->orderBy('id_s','asc')->get();  
    }
}