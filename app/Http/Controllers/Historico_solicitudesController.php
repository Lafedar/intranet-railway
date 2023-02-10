<?php

namespace App\Http\Controllers;

use App\Historico_solicitud;
use App\Http\Request;

class Historico_solicitudesController extends Controller
{
    public function index()
    {
        $historico_solicitudes = Historico_solicitud::all();
        return view('historico_solicitudes.index', compact('historico_solicitudes'));
    }

    public function estado_actual($id_solicitud)
    {
        $estado_actual = DB::table('Historico_solicitud')->where($id_solicitud);
    }
}
