<?php

namespace App\Http\Controllers;

use App\Frecuencia;
use App\Http\Request;

class FrecuenciasController extends Controller
{
    public function index()
    {
        $frecuencias = Frecuencia::all();
        return view('frecuencias.index', compact('frecuencias'));
    }
}
