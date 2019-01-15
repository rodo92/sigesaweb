<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;

class RutasController extends Controller
{
    public function index()
    {
    	return view('archivo.ruta');
    }

    public function registrar_ruta(Request $Request)
    {
    	// print_r($Request->servicios);
    	// echo 'hola';
    }
}
