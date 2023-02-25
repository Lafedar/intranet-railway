<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Historico_solicitudes;
Use Session;
use DB;

class Historico_solicitudesController extends Controller
{
    public function index(Request $request)
    {
        $historico_solicitudes = Historico_solicitudes::Detalle($request->get('detalle'))
        ->paginate(20);

        return view('historico_solicitudes.show', [
            'historico_solicitudes' => $historico_solicitudes,
        ]);
    }

}
