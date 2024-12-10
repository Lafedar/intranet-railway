<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\User;
use App\Empleado;
use App\Motivo;
use App\Consulta_med;
use App\Historia_clinica;
use Auth;
use DB;
use Session;
use Illuminate\Routing\Controller;
use Carbon\Carbon;

class MedicoController extends Controller
{
    public function index(Request $request)
    {
        $consultas = Consulta_med::Busca()
            ->Paciente($request->get('paciente'))
            ->Fecha($request->get('fecha'))
            ->orderBy('id', 'desc')
            ->paginate(20)->withQueryString();

        return view('medico.index', array('consultas' => $consultas, 'paciente' => $request->get('paciente'), 'fecha' => $request->get('fecha')));
    }


    public function create()
    {
        $personas = Empleado::where('activo', 1)->orderBy('apellido', 'asc')->get();


        $motivos = DB::table('motivos_consultas')->orderBy('desc_motivo', 'asc')->get();

        return view('medico.create_consulta', array('personas' => $personas, 'motivos' => $motivos));
    }

    public function store(Request $request)
    {
        $consulta_nueva = new Consulta_med;
        $consulta_nueva->paciente = $request['paciente'];
        $consulta_nueva->motivo = $request['motivo'];
        $consulta_nueva->obs = $request['observacion'];
        $consulta_nueva->fecha = $request['fecha'];
        $consulta_nueva->peso = $request['peso'];
        $consulta_nueva->talla = $request['talla'];
        $consulta_nueva->tension = $request['tension'];
        $consulta_nueva->imc = $request['imc'];
        $consulta_nueva->save();

        Session::flash('message', 'Consulta agregada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('medico');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $consulta_med = Consulta_med::where('id', $id)->first();

        $personas = Empleado::where('activo', 1)->orderBy('apellido', 'asc')->get();

        $motivos = DB::table('motivos_consultas')->orderBy('desc_motivo', 'asc')->get();

        return view('medico.edit', array('consulta_med' => $consulta_med, 'personas' => $personas, 'motivos' => $motivos));
    }
    public function update(Request $request, $id)
    {
        $consulta_med = Consulta_med::find($id);
        $consulta_med->paciente = $request['paciente'];
        $consulta_med->motivo = $request['motivo'];
        $consulta_med->obs = $request['observacion'];
        $consulta_med->fecha = $request['fecha'];
        $consulta_med->peso = $request['peso'];
        $consulta_med->talla = $request['talla'];
        $consulta_med->tension = $request['tension'];
        $consulta_med->imc = $request['imc'];
        $consulta_med->save();

        Session::flash('message', 'Consulta medica modificada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('medico');
    }
    public function destroy($id)
    {
        //
    }
    public function añadir_motivo(Request $request)
    {
        $aux = DB::table('motivos_consultas')->where('motivos_consultas.desc_motivo', $request['motivo'])->count();

        if ($aux == 0) {
            $motivo = new Motivo;
            $motivo->desc_motivo = $request['motivo'];
            $motivo->save();
        } else {
            Session::flash('message', 'Motivo ingresado ya existe');
            Session::flash('alert-class', 'alert-warning');
        }
        return redirect()->back();
    }

    public function historia_clinica()
    {

        $aux = DB::table('historia_clinica')->where('paciente', '!=', null)->get();

        if (count($aux) > 0) {
            foreach ($aux as $aux1) {
                $data[] = $aux1->paciente;
            }
            $personas = DB::table('personas')->where('activo', 1)->whereNotIn('id_p', $data)->orderBy('personas.apellido', 'asc')->get();
        } else {
            $personas = DB::table('personas')->where('activo', 1)->orderBy('personas.apellido', 'asc')->get();
        }

        $educacion = DB::table('educacion')->get();

        return view('medico.historia_clinica', array('personas' => $personas, 'educacion' => $educacion));
    }
    public function store_historia_clinica(Request $request)
    {

        $aux = DB::table('historia_clinica')->where('historia_clinica.paciente', $request['paciente']);

        $historia_clinica = new Historia_clinica;
        $historia_clinica->paciente = $request['paciente'];
        $historia_clinica->grupo_sang = $request['grupo_sang'];
        $historia_clinica->educacion = $request['educacion'];
        if ($request['tabaco'] == null) {
            $historia_clinica->tabaco = 0;
        } else {
            $historia_clinica->tabaco = $request['tabaco'];
        }
        if ($request['alcohol'] == null) {
            $historia_clinica->alcohol = 0;
        } else {
            $historia_clinica->alcohol = $request['alcohol'];
        }
        if ($request['droga'] == null) {
            $historia_clinica->droga = 0;
        } else {
            $historia_clinica->droga = $request['droga'];
        }
        if ($request['desc_act_fisica'] == null) {
            $historia_clinica->act_fisica = 0;
        } else {
            $historia_clinica->act_fisica = 1;
        }
        $historia_clinica->desc_act_fisica = $request['desc_act_fisica'];
        $historia_clinica->ant_per = $request['ant_per'];
        $historia_clinica->ant_fam = $request['ant_fam'];
        $historia_clinica->ant_quir = $request['ant_quir'];
        $historia_clinica->obs = $request['obs'];
        $historia_clinica->save();

        Session::flash('message', 'Historia clinica agregada con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('medico');
    }

    public function reporte_medico($id)
    {

        $consultas = Consulta_med::Reporte($id)->get();

        $historia_clinica = Historia_clinica::Reporte($id)->first();

        if ($historia_clinica) {
            return view('medico.reporte_medico', array('consultas' => $consultas, 'historia_clinica' => $historia_clinica));
        } else {
            Session::flash('message', 'No es posible generar el reporte para el paciente indicado');
            Session::flash('alert-class', 'alert-warning');
            return back();
        }


    }


}
